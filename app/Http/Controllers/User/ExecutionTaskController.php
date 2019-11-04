<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ExecutionTaskRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Operation;
use App\Task;
use App\Definition;
use App\Position;
use App\User;
use App\Poa;
use App\Goal;
use App\Month;
use Auth;
use DB;

use Hashids;

class ExecutionTaskController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor|Usuario|Responsable']);
    $this->data = array(
      'active' => 'execution.task',
      'url1' => '/execution/task',
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
    $keyword = $request->get('search');
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
        "text" => 'DEPARTAMENTO',
        "align" => 'left'
      ], [
        "text" => 'OPERACIÓN',
        "align" => 'center'
      ], [
        "text" => 'TAREA',
        "align" => 'center'
      ], [
        "text" => 'RESPONSABLE(S)',
        "align" => 'center'
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
      ]
    ]);

    $idt = Task::Join('operations', 'operation_id', '=', 'operations.id')
      ->Join('actions', 'operations.action_id', '=', 'actions.id')
      ->Join('goals', 'actions.goal_id', '=', 'goals.id')
      ->Select('goals.code as goal',
        'actions.code as action',
        'operations.code as operation',
        'tasks.code as task',
        'tasks.id as id')
      ->Where('actions.year', activeyear())
      ->OrderBy('goals.code', 'ASC')
      ->Orderby('actions.code', 'ASC')
      ->OrderBy('operations.code', 'ASC')
      ->OrderBy('tasks.code', 'ASC')
      ->pluck('id')
      ->toarray();
    $idt_ordered = implode(',', $idt);

    if (!empty($keyword)) {
      $ids = search_task($keyword);
    }

    if (Auth::user()->hasRole('Responsable')) {
      $idp = Position::Select('id')
        ->Where('department_id', Auth::user()->position->department->id)
        ->pluck('id')
        ->toarray();
      $idu = User::Select('id')
        ->Wherein('position_id', $idp)
        ->pluck('id')
        ->toarray();
      if (!empty($ids))
        $task = Task::WhereHas('users',
          function ($q) use ($idu) {
            $q->wherein('user_id', $idu);
          })
          ->WhereIn('id', $ids)
          ->get();
      else
        $task = Task::WhereHas('users',
          function ($q) use ($idu) {
            $q->wherein('user_id', $idu);
          })
          ->get();
    }
    elseif (Auth::user()->hasRole('Usuario')) {
      $idp = Auth::user()->position->id;
      $idu = User::Select('id')
        ->Where('position_id', $idp)
        ->pluck('id')
        ->toarray();
      if (!empty($ids))
        $task = Task::WhereHas('users',
          function ($q) use ($idu) {
            $q->wherein('user_id', $idu);
          })
          ->WhereIn('id', $ids)
          ->get();
      else
        $task = Task::WhereHas('users',
          function ($q) use ($idu) {
            $q->wherein('user_id', $idu);
          })
          ->get();
    }
    else {
      if (!empty($ids))
        $task = Task::WhereIn('id', $ids)
          ->OrderBy('operation_id', 'ASC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      else
        $task = Task::WhereIn('id', $idt)->
        orderByRaw(DB::raw("FIELD(id, $idt_ordered)"))->
        paginate($perPage);
    }

    $months = Month::OrderBy('id', 'ASC')
      ->pluck('name', 'id');

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.execution.task.report.index',
        compact(['task', 'months', 'idt', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.execution.task.index',
        compact(['task', 'months', 'idt', 'header']))
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

    $task = Task::findOrFail($id);
    $months = Month::OrderBy('id', 'ASC')->get();

    $chart_accum = chartaccum($task->id,'Task','Accum',activemonth(),'charta');
    $chart_month = chartaccum($task->id, 'Task', 'Month', activemonth(), 'chartm');
    $chart_accum_month = chartaccum($task->id, 'Task', 'AccumMonth', activemonth(), 'chartam');


    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.execution.task.report.show',
        compact(['task', 'months']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());
      return view('layouts.execution.task.show',
        compact(['task',
          'months','months',
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
    $task = Task::findOrFail($id);
    if(!$task->status){
      \Toastr::error("Tarea Desestimada",
        $title = 'EJECUCIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
      logrec('html', \Route::currentRouteName());
      return redirect('execution/task');
    }
    $month = 'm' . activemonth();
    if(empty($task->operation->poas()->where('month', activemonth())->first()->$month)){
      if (Auth::user()->hasRole('Responsable|Supervisor|Administrador')) {
        return view('layouts.execution.task.edit', compact('task'))
          ->with('data', $this->data);
      }
      else{
        if (empty($task->poas()->where('month', activemonth())->first()->$month)) {
          return view('layouts.execution.task.edit', compact('task'))
            ->with('data', $this->data);
        } else {
          if (empty($task->operation->poas()->where('month', activemonth())->first()->$month)) {
            \Toastr::error("Tarea Ejecutada",
              $title = 'EJECUCIÓN',
              $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
              ]);
            logrec('html', \Route::currentRouteName());
            return redirect('execution/task');
          }
        }
      }
    }
    else {
      \Toastr::error("Operación Ejecutada",
        $title = 'EJECUCIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
      logrec('html', \Route::currentRouteName());
      return redirect('execution/task');
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
  public function update(ExecutionTaskRequest $request, $id)
  {
    
    $id = Hashids::decode($id)[0];

    $task = Task::findOrFail($id);
    
    $month = activemonth();
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
      'm12',
      'success',
      'failure',
      'solution'
    );
    if (!isset($requestPoa["m" . $month]))
      $requestPoa["m" . $month] = 0;
      
    $execmonth['m' . $month] = $requestPoa["m" . $month];
    $task->poas()->Where('state', true)->
    Where('month', false)->
    Update($execmonth);
    
    $requestExecution["m" . $month] = $requestPoa["m" . $month];
    $requestExecution["month"] = $month;
    $requestExecution["state"] = '1';
    $requestExecution["success"] = $requestPoa["success"];
    $requestExecution["failure"] = $requestPoa["failure"];
    $requestExecution["solution"] = $requestPoa["solution"];
    
    if(isset($task->poas->
      Where('state',true)->
      Where('month',activemonth())->
      first()->
      month)){
        
        $task->poas()->
        Where('state',true)->
        Where('month',activemonth())->
        update($requestExecution);
      }
    else{
      
        $task->poas()->create($requestExecution);
    }

    //    $ids = Task::Select('id')
    //      ->Where('operation_id', $task->operation_id)
    //      ->pluck('id')
    //      ->toarray();
    //    $total = 0;
    //    for ($m = 1; $m <= 12; $m++) {
    //      $taskmonth['m' . $m] = progexec($ids, $m);
    //    }
    //    $taskmonth['m' . $month] = progexec($ids, $month);
    //    $execmonth['m' . $month] = operation_execution($task->operation_id, $month);
    if($requestPoa["m" . $month]>0){
      $execmonth['m' . $month] = execs($task->operation_id,'Operation' ,$month);
    }
    $operation = Operation::Where('id', $task->operation_id)->
                              Where('status', true)->
                              First();
    
      ////verificamos que la operacion no este eliminada o suspendida
    if(is_null($operation)){
      \Toastr::error("La tarea no puede ser ejecutada por la opearacion no esta habilitada",
      $title = 'EJECUCIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);
      logrec('html', \Route::currentRouteName());
      return redirect('execution/task')
        ->with('data', $this->data);
    }
    if($requestPoa["m" . $month]>0){
      $operation->
      poas()->
      where('state',true)->
      Where('month',false)->
      Orderby('id','desc')->
      first()->
      Update($execmonth);
    }
    
      ////aqui ejecutamos en el nuevo 
      ///y e sel nuevo que tenemos que mostrara en la ejecucion del la operacion 
      
    \Toastr::success("Tarea Ejecutada",
      $title = 'EJECUCIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);
    logrec('html', \Route::currentRouteName());
    return redirect('execution/task')
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
