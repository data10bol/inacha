<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ExecutionOperationRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Action;
use App\Operation;
use App\Task;
use App\Definition;
use App\Position;
use App\User;
use App\Poa;
use App\Goal;
use App\Month;
use Auth;

use Hashids;

class ExecutionOperationController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor|Usuario|Responsable']);
    $this->data = array(
      'active' => 'execution.operation',
      'url1' => '/execution/operation',
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
    if(check_reconf())
        return view('layouts.partials.banner');
    //$keyword = $request->get('search');
    $type = $request->get('type');
    if (isset($type) && $type == "pdf")
      $perPage = 25000;
    else
      $perPage = 25000;

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
        "text" => 'ACCIÓN C.P.',
        "align" => 'left'
      ], [
        "text" => 'OPERACIÓN',
        "align" => 'left'
      ], [
        "text" => 'P(%)',
        "align" => 'center'
      ], [
        "text" => 'E(%)',
        "align" => 'center'
      ], [
        "text" => 'INICIO',
        "align" => 'center'
      ], [
        "text" => 'FIN',
        "align" => 'center'
      ], [
        "text" => '#TR',
        "align" => 'center'
      ], [
        "text" => 'EST',
        "align" => 'center'
      ]
    ]);

    $ido = \App\Definition::Where('definition_type','App\Operation')->
                            where('created_at','>',(string)activeyear())->
                            pluck('definition_id')->
                            ToArray();
    
    $operation = Operation::OrderBy('code', 'ASC')->get();
      
    if (Auth::user()->hasRole('Responsable')) {

      $ids = \App\Definition::Where('department_id',Auth::user()->position->department_id)->
                          Where('definition_type','App\Operation')->
                          where('created_at','>',(string)activeyear())->
                          pluck('definition_id')->
                          ToArray();

      $operation = Operation::Wherein('id', $ids)->get();
    }

    $months = Month::OrderBy('id', 'ASC')
      ->pluck('name', 'id');

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.execution.operation.report.index', compact(['operation', 'months', 'ido', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.execution.operation.index', compact(['operation', 'months', 'ido', 'header']))
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

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(ExecutionRequest $request)
  {

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

    if (strpos($id, '-') === false)
      $id = Hashids::decode($id)[0];
    else {
      list($temp, $type) = explode("-", $id);
      $id = Hashids::decode($temp)[0];
      unset($temp);
    }

    $operation = Operation::findOrFail($id);

    $months = Month::OrderBy('id','ASC')->get();

    $chart_accum = chartaccum($operation->id,'Operation','Accum',activemonth(),'charta');
    $chart_month = chartaccum($operation->id, 'Operation', 'Month', activemonth(), 'chartm');
    $chart_accum_month = chartaccum($operation->id, 'Operation', 'AccumMonth', activemonth(), 'chartam');


    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.execution.operation.report.show',
        compact(['operation','months']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.execution.operation.show',
        compact(['operation',
          'months',
          'chart_accum',
          'chart_month',
          'chart_accum_month']))
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
    
    $id = Hashids::decode($id)[0];
    $operation = Operation::findOrFail($id);
    $month = 'm' . activemonth();

    logrec('html', \Route::currentRouteName());
    /*$ntask = Task::Join('poas', function($join)
    {
        $join->on('tasks.id','=','poas.poa_id')
        ->where('poas.poa_type','Like','%Task');
    })
    ->where('tasks.operation_id',$id)
    ->where('poas.state',true)
    ->where('poas.month',activemonth())
    ->count();*/

    if (empty($operation->poas()->where('month', activemonth())->first()->$month)) {
      return view('layouts.execution.operation.edit', compact('operation'))
        ->with('data', $this->data);
    } else {

      \Toastr::error("Operación Ejecutada",
        $title = 'EJECUCIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
      return redirect('execution/operation');
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param  int $id
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function update(ExecutionOperationRequest $request, $id)
  {
    
    $id = Hashids::decode($id)[0];
    $operation = Operation::findOrFail($id);
    //dd($operation);
    $month = activemonth();
    $requestPoa = $request->only(
      'value',
      'success',
      'failure',
      'solution'
    );
    $operation = Operation::findOrFail($id);
    ///aqui creamos el registro de control
    $requestExecution["m" . $month] = $requestPoa["value"];
    $requestExecution["month"] = $month;
    $requestExecution["state"] = '1';
    $requestExecution["success"] = $requestPoa["success"];
    $requestExecution["failure"] = $requestPoa["failure"];
    $requestExecution["solution"] = $requestPoa["solution"];
    
    $operation->poas()->create($requestExecution);
   
    //$execmonth['m' . $month] = action_execution($operation->action_id, $month);
    
    ////asemos el ajuste de ejecución de la acción y se registra en la acción ejecución nueva 
    $execmonth['m' . $month] = execs($operation->action_id,'action', $month);
    $action = Action::Where('id', $operation->action_id)->
    where('status', true)->
    first();
    $action->
      poas()->
      Where('state', true)->      
      Where('month',false)->
      Orderby('id','desc')->
      first()->
      Update($execmonth);

    \Toastr::success("Operación Ejecutada",
      $title = 'EJECUCIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);
    logrec('html', \Route::currentRouteName());
    return redirect('execution/operation')
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

  }
}
