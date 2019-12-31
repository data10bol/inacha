<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\GoalRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use App\Period;
use App\Doing;
use App\Action;
use App\Goal;

use Hashids;

class GoalController extends Controller
{

  use HasRoles;
  protected $guard_name = 'web';

  public function __construct()
  {
    $this->middleware(['role:Usuario|Responsable|Administrador|Supervisor']);
    $this->data = array(
      'active' => 'goal',
      'url1' => '/institution/goal',
      'url2' => '/institution/action'
    );
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\View\View
   */
  public function index(Request $request)
  {
    if(check_reconf())
        return view('layouts.partials.banner');
    $keyword = $request->get('search');
    $type = $request->get('type');

    $perPage = 25000;

    $header = ([
      [
        "text" => 'PERIODO',
        "align" => 'center'
      ], [
        "text" => 'GESTIÓN',
        "align" => 'center'
      ], [
        "text" => 'CÓDIGO',
        "align" => 'center'
      ], [
        "text" => 'ACCIÓN',
        "align" => 'left'
      ], [
        "text" => 'ACCIÓN M.P.',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $goal = Goal::orderBy('doing_id', 'ASC')
        ->orderBy('code', 'ASC')
        ->Where('description', 'LIKE', "%$keyword%")
        ->where('year',activeyear())
        ->paginate($perPage);
    } else {
      $goal = Goal::orderBy('doing_id', 'ASC')
        ->OrderBy('code', 'ASC')
        ->where('year',activeyear())
        ->paginate($perPage);
    }

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.institution.goal.report.index', compact(['goal', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());
      
      return view('layouts.institution.goal.index', compact(['goal', 'header']))
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
      $period = Period::Select('id')
        ->Where('status', true)
        ->first();

      $doing = Doing::Where('id', $request->get('id'))
        ->first();

      return view('layouts.institution.goal.create')
        ->with('doing', $doing)
        ->with('period', $period)
        ->with('data', $this->data);
    } else
      return view('layouts.institution.goal.create')
        ->with('data', $this->data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(GoalRequest $request)
  {

    $requestData = $request->all();

    $code = (int)Goal::OrderBy('code', 'DESC')->pluck('code')->first() + 1;

    $requestData['code'] = $code;

    $goal = Goal::create($requestData);

    \Toastr::success(
      "Una nuevo registro fue agregado",
      $title = 'CREACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]
    );
    logrec('html', \Route::currentRouteName());
    return redirect('institution/goal')
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

    $goal = Goal::findOrFail($id);

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.institution.goal.report.show',
        compact('goal'))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());
      return view('layouts.institution.goal.show',
        compact('goal'))
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
    $goal = Goal::findOrFail($id);
    logrec('html', \Route::currentRouteName());
    return view('layouts.institution.goal.edit',
      compact(['goal']))
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
  public function update(GoalRequest $request, $id)
  {

    $requestData = $request->all();

    $id = Hashids::decode($id)[0];
    $goal = Goal::findOrFail($id);
    $goal->update($requestData);

    \Toastr::warning(
      "El registro fue actualizado",
      $title = 'ACTUALIZACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]
    );
    logrec('html', \Route::currentRouteName());
    return redirect('institution/goal')
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

    if (!Action::Where('goal_id', $id)->get()->count()) {
      $goal = Goal::findOrFail($id);

      $goal->delete();

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
    return redirect('institution/goal')
      ->with('data', $this->data);
  }

}
