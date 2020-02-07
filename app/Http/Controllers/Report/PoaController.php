<?php

namespace App\Http\Controllers\Report;

use App\Configuration;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Operation;
use App\Action;
use App\Poa;
use App\Goal;
use App\Month;

use Hashids;

class PoaController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor|Responsable']);
    $this->data = array(
      'active' => 'report.poa',
      'url1' => 'reportpoa',
      'url2' => ''
    );
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\View\View
   */
  public function index(Request $request)
  {
    
    $perPage = 1;
    $type = $request->get('type');

//    $goal = Goal::Join('configurations','goals.configuration_id','=','configurations.id')
//      ->Where('configurations.status',true)
//      ->paginate($perPage);

//    $goal = Goal::OrderBy('doing_id', 'ASC')->paginate($perPage);

    $header = ([
      [
        "text" => 'CÓDIGO',
        "align" => 'center'
      ], [
        "text" => 'ACCIÓN CORTO PLAZO',
        "align" => 'left'
      ], [
        "text" => 'POND.',
        "align" => 'center'
      ], [
        "text" => 'META',
        "align" => 'center'
      ], [
        "text" => 'INDICADOR',
        "align" => 'left'
      ], [
        "text" => 'A.P.',
        "align" => 'center'
      ], [
        "text" => 'A.E.',
        "align" => 'center'
      ], [
        "text" => '',
        "align" => 'center'
      ], [
        "text" => '%EF',
        "align" => 'center'
      ], [
        "text" => 'PROG',
        "align" => 'center'
      ], [
        "text" => 'EJEC',
        "align" => 'center'
      ], [
        "text" => '',
        "align" => 'center'
      ], [
        "text" => 'EFIC',
        "align" => 'center'
      ], [
        "text" => 'DEPARTAMENTO',
        "align" => 'center'
      ]
    ]);

    $actions = Action::Where('year',activeyear())->
                get();
    if (!empty($request->get('month')))
      $month = $request->get('month');
    else
      $month = activemonth();

    $months = Month::OrderBy('id', 'ASC')
      ->pluck('name', 'id');
//    return view('layouts.report.poa.index', compact(['goal', 'months']))
//      ->with('data', $this->data);

    if (isset($type)) {
      switch ($type){
        case 'pdf':
          logrec('pdf', \Route::currentRouteName());

          $view = \View::make('layouts.report.poa.report.index',
            compact(['actions', 'months','header']))
            ->with('currentmonth', $month);

          $pdf = \App::make('dompdf.wrapper');
          $pdf->loadHTML($view)->setPaper('letter', 'landscape');
          return $pdf->stream();
          break;
        case 'word':

          $phpWord = new \PhpOffice\PhpWord\PhpWord();
          $section = $phpWord->addSection();
          $text = $section->addText('Ramiro Mora');
          $text = $section->addText('rmora@ofep.gob.bo');
          $text = $section->addText('123456',array('name'=>'Arial','size' => 20,'bold' => true));
          //$section->addImage("img/bg-01.jpg");
          $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
          $objWriter->save('Appdividend.docx');
          return response()->download(public_path('Appdividend.docx'));

          break;
      }
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.report.poa.index',
                  compact(['actions', 'months','header']))
                ->with('data', $this->data)
                ->with('currentmonth', $month);
    }

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\View\View
   */
  public function create(Request $request)
  {
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(OperationRequest $request)
  {

    $requestData = $request->all();

    $ponderation = 0;

    $operations = Operation::Where('action_id', $requestData["action_id"])
      ->get();

    foreach ($operations as $operation)
      $ponderation += $operation->definitions->pluck('ponderation')->first();

    if (($ponderation + $requestData["ponderation"]) > 100) {
      \Toastr::error("La ponderación supera el 100% de la Acción de Corto Plazo",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);

      return back()
        ->withInput($requestData);
    }

    $code = (int)Operation::Where('action_id', $requestData["action_id"])
        ->OrderBy('code', 'DESC')
        ->pluck('code')
        ->first() + 1;

    if ($requestData["code"] != $code) {
      \Toastr::error("El código para la Operación debe ser " . $code,
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);

      return back()
        ->withInput($requestData);
    }

    $requestOperation = $request->only(
      'code',
      'action_id',
      'year'
    );
    $requestDefinition = $request->only(
      'description',
      'measure',
      'ponderation',
      'base',
      'aim',
      'pointer',
      'validation',
      'start',
      'finish'
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

    $operation = Operation::create($requestOperation);
    $operation->definitions()->create($requestDefinition);
    $operation->poas()->create($requestPoa);

    \Toastr::success(
      "Una nuevo registro fue agregado",
      $title = 'CREACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);

    return redirect('institution/operation');

  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   *
   * @return \Illuminate\View\View
   */
  public function show(Request $request)
  {
    $perPage = 1;
    $type = $request->get('type');

    if(!empty($request->get('id'))){
      $id = $request->get('id');
      $id = Hashids::decode($id)[0];
    }
    else
      $id=0;

//    $goal = Goal::Join('configurations','goals.configuration_id','=','configurations.id')
//      ->Where('configurations.status',true)
//      ->paginate($perPage);

    $months = Month::OrderBy('id', 'ASC')->
    pluck('name', 'id');

    if($id != 0){
      $department = \App\Department::FindorFail($id);
      $header = ([
        [
          "text" => 'CÓD.',
          "align" => 'center'
        ], [
          "text" => 'ACCIÓN CORTO PLAZO / OPERACIÓN / TAREA',
          "align" => 'left'
        ], [
          "text" => 'POND.',
          "align" => 'center'
        ], [
          "text" => 'META',
          "align" => 'center'
        ], [
          "text" => 'INDICADOR',
          "align" => 'left'
        ], [
          "text" => 'A.P.',
          "align" => 'center'
        ], [
          "text" => 'A.E.',
          "align" => 'center'
        ], [
          "text" => '',
          "align" => 'center'
        ], [
          "text" => '%EF',
          "align" => 'center'
        ], [
          "text" => 'LOGRO',
          "align" => 'center'
        ], [
          "text" => 'PROG',
          "align" => 'center'
        ], [
          "text" => 'EJEC',
          "align" => 'center'
        ], [
          "text" => '',
          "align" => 'center'
        ], [
          "text" => 'EFIC',
          "align" => 'center'
        ], [
          "text" => 'RESPONSABLE',
          "align" => 'center'
        ]
      ]);
      $header2 = ([
        [
          "text" => 'CÓD.',
          "align" => 'center'
        ], [
          "text" => ' OPERACIÓN / TAREA',
          "align" => 'left'
        ], [
          "text" => 'POND. DEP.',
          "align" => 'center'
        ], [
          "text" => 'META',
          "align" => 'center'
        ], [
          "text" => 'INDICADOR',
          "align" => 'left'
        ], [
          "text" => 'A.P.',
          "align" => 'center'
        ], [
          "text" => 'A.E.',
          "align" => 'center'
        ], [
          "text" => '',
          "align" => 'center'
        ], [
          "text" => '%EF',
          "align" => 'center'
        ], [
          "text" => 'LOGRO',
          "align" => 'center'
        ], [
          "text" => 'PROG',
          "align" => 'center'
        ], [
          "text" => 'EJEC',
          "align" => 'center'
        ], [
          "text" => '',
          "align" => 'center'
        ], [
          "text" => 'EFIC',
          "align" => 'center'
        ], [
          "text" => 'RESPONSABLE',
          "align" => 'center'
        ]
      ]);

      $actions = Action::Where('year',activeyear())->
                  Where('department_id',$id)->
                  get();
      if (!empty($request->get('month')))
        $month = $request->get('month');
      else
        $month = activemonth();
      $ids = \App\Definition::where('created_at','>',(string)activeyear())->
                              where('department_id',$id)->
                              where('definition_type','App\Operation')->
                              pluck('definition_id')->
                              toarray();
      $operations2 = \App\Operation::WhereIn('id',$ids)->get();
      if (isset($type)) {
        switch ($type) {
          case '1':
            logrec('pdf', \Route::currentRouteName());
            $view = \View::make('layouts.report.poa.report.show',
              compact(['actions', 'months','header','department']))
              ->with('currentmonth', $month);
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('letter', 'landscape');
            return $pdf->stream();
            break;
          case '2':
            logrec('pdf', \Route::currentRouteName());
            $view = \View::make('layouts.report.poa.report.show2',
              compact(['operations2', 'months','header2','department']))
              ->with('currentmonth', $month);
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('letter', 'landscape');
            return $pdf->stream();
            break;
          default:
            return redirect()->back();
            break;
        }

      } else {
        logrec('html', \Route::currentRouteName());
        return view('layouts.report.poa.indexdep',
          compact(['actions', 'months','header','department','header2','operations2']))
          ->with('data', $this->data)
          ->with('currentmonth', $month);
      }
    }
    else {
      $goal = Goal::OrderBy('doing_id', 'ASC')->paginate($perPage);

      return view('layouts.report.poa.show', compact(['goal', 'months']))
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

  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param  int $id
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function update(OperationRequest $request, $id)
  {


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

  }
}
