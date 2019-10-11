<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ActionRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use App\Action;
use App\Operation;
use App\Definition;
use App\Poa;
use App\Goal;
use App\Structure;
use App\Month;

use Hashids;

class ActionController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor']);
    $this->data = array(
      'active' => 'action',
      'url1' => '/institution/action',
      'url2' => '/institution/operation'
    );
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\View\View
   */
  public function index(Request $request)
  {
    // if(check_reconf())
    //     return view('layouts.partials.banner');
    $keyword = $request->get('search');
    $type = $request->get('type');
    if (isset($type) && $type == "pdf")
      $perPage = 25000;
    else
      $perPage = 25;

    $header = ([
      [
        "text" => 'CÓDIGO',
        "align" => 'center'
      ], [
        "text" => 'GESTIÓN',
        "align" => 'center'
      ], [
        "text" => 'DEPARTAMENTO',
        "align" => 'left'
      ], [
        "text" => 'ACCIÓN M.P.',
        "align" => 'left'
      ], [
        "text" => 'ACCIÓN C.P.',
        "align" => 'left'
      ], [
        "text" => 'P(%)',
        "align" => 'center'
      ], [
        "text" => '#OP',
        "align" => 'center'
      ], [
        "text" => 'SPO',
        "align" => 'center'
      ]
    ]);

    if (!empty($keyword)) {

      $ids = search_action($keyword);

      if (!empty($ids)) {
        $action = Action::WhereIn('id', $ids)
          ->Where('year', activeyear())
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      } else {
        $action = Action::Where('year', activeyear())
          ->OrderBy('goal_id', 'ASC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      }
    } else {
      $action = Action::Where('year', activeyear())
        ->OrderBy('goal_id', 'ASC')
        ->OrderBy('code', 'ASC')
        ->paginate($perPage);
    }

    $months = Month::OrderBy('id', 'ASC')
      ->pluck('name', 'id');


    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.institution.action.report.index',
        compact(['action', 'months', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());
      return view('layouts.institution.action.index',
        compact(['action', 'months', 'header']))
        ->with('data', $this->data);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\View\View
   */
  public function create(Request $request)
  {
    if(!permissions_check('create',$this->data["active"]))
      return redirect($this->data["url1"]);

    logrec('html', \Route::currentRouteName());
    if (!empty($request->get('id'))) {
      $goal = Goal::Select('id', 'code')->Where('id', $request->get('id'))->first();

      return view('layouts.institution.action.create')
        ->with('goal', $goal)
        ->with('data', $this->data);
    } else
      return view('layouts.institution.action.create')
        ->with('data', $this->data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(ActionRequest $request)
  {

    $requestData = $request->all();

    $requestData['year'] = activeyear();

    $ponderation = 0;

    $actions = Action::Where('goal_id', $requestData["goal_id"])
      ->Where('year', activeyear())
      ->get();

    foreach ($actions as $action)
      $ponderation += $action->definitions->pluck('ponderation')->first();

    if (($ponderation + $requestData["ponderation"]) > 100) {
      \Toastr::error("La ponderación supera el 100% de la Acción a Mediano Plazo",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
      return back()
        ->withInput($requestData)
        ->with('data', $this->data);
    }

    $code = (int)Action::Where('goal_id', $requestData["goal_id"])
        ->Where('year', activeyear())
        ->OrderBy('code', 'DESC')
        ->pluck('code')
        ->first() + 1;
    

    /*        if($requestData["code"] != $code)
            {
                \Toastr::error("El código para la Acción a Corto Plazo debe ser ". $code,
                $title = 'ERROR',
                $options = [
                    'closeButton' => 'true',
                    'hideMethod' => 'slideUp',
                    'closeEasing' => 'easeInBack',
                    ]);

                return back()
                    ->withInput($requestData)
                    ->with('data',$this->data);
            }*/

    $requestAction = $request->only(
      'department_id',
//                        'code',
      'goal_id'
//                        'year'
    );
    $requestAction['code'] = $code;
    $requestAction['year'] = activeyear();

    $requestDefinition = $request->only(
      'description',
      'measure',
      'ponderation',
      'base',
      'aim',
      'describe',
      'pointer',
      'validation'
    );
    $requestPoa = $request->only(
      'm1',
      'm2',
      'm3',
      'm4',
      'm5',
      'm6',
      'm7',
      'm8',
      'm9',
      'm10',
      'm11',
      'm12'
    );
    $requestStructure = $request->only(
      'scode',
      'name'
    );
    $action = Action::create($requestAction);
    $action->definitions()->create($requestDefinition);
    $action->poas()->create($requestPoa);
    $action->poas()->create(['state' => '1']);
    $action->structures()->create(['code' => $requestStructure['scode'],
      'name' => $requestStructure['name']]);

    \Toastr::success(
      "Una nuevo registro fue agregado",
      $title = 'CREACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);
    logrec('html', \Route::currentRouteName());
    return redirect('institution/action')
      ->with('data', $this->data);

  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   *
   * @return \Illuminate\View\View
   */
  public function show($id)
  {
    if(!permissions_check('read',$this->data["active"]))
      return redirect($this->data["url1"]);

    if (strpos($id, '-') === false)
      $id = Hashids::decode($id)[0];
    else {
      list($temp, $type) = explode("-", $id);
      $id = Hashids::decode($temp)[0];
      unset($temp);
    }

    $action = Action::findOrFail($id);

    $months = Month::OrderBy('id','ASC')->get();
/*    $ids = Operation::Select('id')
      ->Where('action_id',$action->id)
      ->pluck('id')
      ->all();
    $ponderation = Definition::Select('ponderation')
      ->WhereIn('definition_id',$ids)
      ->Where('definition_type','App\Operation')
      ->Pluck('ponderation')
      ->all(); */
    $ops = Operation::Where('action_id',$action->id)
      ->OrderBy('code')
      ->get();
      
    if (isset($type) && $type == "pdf") {
      
      logrec('pdf', \Route::currentRouteName());
      $view = \View::make('layouts.institution.action.report.show',
      //compact(['action','months','ponderation','ids','ops']))
        compact(['action','months','ops']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      
      $pdf->loadHTML($view);
      
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());
      return view('layouts.institution.action.show',
//        compact(['action','months','ponderation','ids','ops']))
        compact(['action','months','ops']))
        ->with('data', $this->data);
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int $id
   *
   * @return \Illuminate\View\View
   */
  public function edit($id)
  {
    if(!permissions_check('edit',$this->data["active"]))
      return redirect($this->data["url1"]);

    $id = Hashids::decode($id)[0];
    $action = Action::findOrFail($id);
    logrec('html', \Route::currentRouteName());
    return view('layouts.institution.action.edit', compact('action'))
      ->with('data', $this->data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param  int $id
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function update(ActionRequest $request, $id)
  {

    $requestData = $request->all();

    $id = Hashids::decode($id)[0];

    $ponderation = 0;

    $actions = Action::Where('goal_id', $requestData["goal_id"])
      ->Where('year', activeyear())
      ->Where('id', '!=', $id)
      ->get();

    foreach ($actions as $action)
      $ponderation += $action->definitions->pluck('ponderation')->first();

    if (($ponderation + $requestData["ponderation"]) > 100) {
      \Toastr::error("La ponderación supera el 100% de la Acción a Mediano Plazo",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);

      return back()
        ->withInput($requestData)
        ->with('data', $this->data);
    }

    $requestAction = $request->only(
      'department_id',
      'code',
      'goal_id',
      'year'
    );
    $requestDefinition = $request->only(
      'description',
      'measure',
      'ponderation',
      'base',
      'aim',
      'describe',
      'pointer',
      'validation'
    );
    $requestPoa = $request->only(
      'm1',
      'm2',
      'm3',
      'm4',
      'm5',
      'm6',
      'm7',
      'm8',
      'm9',
      'm10',
      'm11',
      'm12'
    );

    $requestStructure = $request->only(
      'scode',
      'name'
    );

    $action = Action::findOrFail($id);

    if ($action->goal->configuration->reconfigure) {
      $action = Action::Where('id', $action->id)->Update(['status' => '0',]);
      $action = Action::create($requestAction);

      $action->definitions()->create($requestDefinition);
      $action->poas()->create($requestPoa);
      $action->structures()->create(['code' => $requestStructure['scode'],
        'name' => $requestStructure['name']]);

      \Toastr::success("Acción a Corto Plazo Reprogramada",
        $title = 'REPROGRAMACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
    } else {
      $action->update($requestAction);

      $action->definitions()->update($requestDefinition);
      $action->poas()->Where('state', '0')->update($requestPoa);
      $action->structures()->update(['code' => $requestStructure['scode'],
        'name' => $requestStructure['name']]);

      \Toastr::warning(
        "El registro fue actualizado",
        $title = 'ACTUALIZACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
    }
    logrec('html', \Route::currentRouteName());
    return redirect('institution/action')
      ->with('data', $this->data);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function destroy($id)
  {
    if(!permissions_check('delete',$this->data["active"]))
      return redirect($this->data["url1"]);

    $id = Hashids::decode($id)[0];

    if (!Operation::Where('action_id', $id)->get()->count()) {
      $action = Action::findOrFail($id);

      foreach ($action->poas->pluck('id') as $poa) {
        Poa::where('id', $poa)->delete();
      }

      foreach ($action->definitions->pluck('id') as $definition) {
        Definition::where('id', $definition)->delete();
      }

      foreach ($action->structures->pluck('id') as $structure) {
        Structure::where('id', $structure)->delete();
      }

      $action->delete();

      \Toastr::error(
        "El registro fue eliminado",
        $title = 'ELIMINACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    } else {
      \Toastr::warning(
        "El registro NO se encuentra vacío",
        $title = 'ERROR EN BORRADO',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    }

    logrec('html', \Route::currentRouteName());
    return redirect('institution/action')
      ->with('data', $this->data);
  }
}
