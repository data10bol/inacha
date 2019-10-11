<?php

namespace App\Http\Controllers\Chief;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;

use App\Operation;
use App\Task;
use App\Subtask;
use App\Position;
use App\User;
use App\Poa;
use App\Month;
use App\Reason;
use Auth;
use DB;

use Hashids;

class TaskController extends Controller
{
  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor|Responsable|Usuario']);
    $this->data = array(
      'active' => 'task',
      'url1' => '/institution/task',
       //'url2' => '/institution/subtask'
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
    // if(check_reconf())
    //     return view('layouts.partials.banner');
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
        "align" => 'center'
      ], [
        "text" => 'OPERACIÓN',
        "align" => 'left'
      ], [
        "text" => 'TAREA',
        "align" => 'left'
      ], [
        "text" => 'RESPONSABLE(S)',
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

      $view = \View::make('layouts.institution.task.report.index',
        compact(['task', 'months', 'idt', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());
      return view('layouts.institution.task.index',
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
    if(!permissions_check('create',$this->data["active"]))
      return redirect($this->data["url1"]);

    logrec('html', \Route::currentRouteName());
    if (!empty($request->get('id'))) {
      $operation = Operation::Where('id', $request->get('id'))->first();
//            $code = (int)Task::Where('operation_id',$operation->id)->max('code')+1;

      return view('layouts.institution.task.create')
        ->with('operation', $operation)
//                ->with('code',$code)
        ->with('data', $this->data);
    } else {
      if (Auth::user()->hasrole('Administrador|Responsable|Usuario')) {
        return view('layouts.institution.task.create')
          ->with('data', $this->data);
      } else {
        \Toastr::error(
          "Sin atributos",
          $title = 'ERROR',
          $options = [
            'closeButton' => 'true',
            'hideMethod' => 'slideUp',
            'closeEasing' => 'easeInBack',
          ]
        );

        return redirect('institution/operation');
      }
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(TaskRequest $request)
  {
    $requestData = $request->all();

    /*        
      $responsable = User::Where('id',$requestData["user_id"])->first();
      $department = Operation::Where('id',$requestData["operation_id"])->first();
      if($responsable->position->department->id != $department->action->department_id)
      {
          \Toastr::error("El responsable no pertenece al departamento",
          $title = 'ERROR',
          $options = [
              'closeButton' => 'true',
              'hideMethod' => 'slideUp',
              'closeEasing' => 'easeInBack',
              ]);

          return back()
              ->withInput($requestData)
              ->with('data',$this->data);
      } 
    */

    $code= (int)Task::Where('operation_id', $request->operation_id)->
                      OrderBy('code', 'DESC')->
                      pluck('code')->
                      first() + 1;

    /*        if($requestData["code"] != $code)
            {
                \Toastr::error("El código para la Tarea debe ser ". $code,
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

    $requestTask = $request->only(
//                        'code',
      'operation_id',
//                        'user_id',
      'plan_id',
      'year'
    );
    $requestTask['code'] = $code;

    $requestDefinition = $request->only(
      'description',
      'measure',
      'base',
      'aim',
      'describe',
      'pointer',
      'validation'
//                        'start',
//                        'finish'
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

    $pond = 0;
    $requestDefinition["start"] = 1;
    $requestDefinition["finish"] = 12;

    for ($i = 1; $i <= 12; $i++) {
      if (isset($requestPoa['m' . $i])) {
        if ($pond == 0)
          $requestDefinition["start"] = $i;
        elseif ($requestPoa['m' . $i] > 0)
          $requestDefinition["finish"] = $i;
        $pond += $requestPoa['m' . $i];
      }
    }
    ///delimitamos el finish y el start en base a lo llenado e la request
    //dd($request->users);
    $task = Task::create($requestTask);///creamos una nueva tarea
    $task->users()->attach($request->users); ///asignamos usuarios
    $task->definitions()->create($requestDefinition);///creamos su definición 
    $task->poas()->create($requestPoa);
    $task->poas()->create(['state' => '1']);

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
    return redirect('institution/task')
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

    $task = Task::findOrFail($id);

    $months = Month::OrderBy('id','ASC')->get();

    $reasons = Reason::Where('reason_id',$task->id)
              ->Where('reason_type','App\Task')
              ->OrderBy('id')
              ->get();

    if (isset($type) && $type == "pdf"){
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.institution.task.report.show',
        compact(['task','months']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());
      return view('layouts.institution.task.show',
        compact(['task','months','reasons']))
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
    $task = Task::findOrFail($id);
    logrec('html', \Route::currentRouteName());
    if (!$task->status) {
      return view('layouts.institution.task.show', compact('task'))
        ->with('data', $this->data);
    } else {
      return view('layouts.institution.task.edit', compact('task'))
        ->with('data', $this->data);
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




  public function update(TaskRequest $request, $id)
  {
    $requestData = $request->all();

    $id = Hashids::decode($id)[0];

    /*        $responsable = User::Where('id',$requestData["user_id"])->pluck('department_id')->first();
            $department = Operation::Where('id',$requestData["operation_id"])->first();
            if($responsable != $department->action->department_id)
            {
                \Toastr::error("El responsable no pertenece al departamento",
                $title = 'ERROR',
                $options = [
                    'closeButton' => 'true',
                    'hideMethod' => 'slideUp',
                    'closeEasing' => 'easeInBack',
                    ]);

                return back()
                    ->withInput($requestData)
                    ->with('data',$this->data);
            } */

    $requestTask = $request->only(
    //'code',
      'operation_id',
//                        'user_id',
      'plan_id',
      'year'
    );
    $requestDefinition = $request->only(
      'description',
      'measure',
      'base',
      'aim',
      'describe',
      'pointer',
      'validation'
//                        'start',
//                        'finish'
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

    $requestReason["description"] = $request->input('reason');
    $requestReason["user_id"] = Auth::user()->id;

    $pond = 0;
    $requestDefinition["start"] = 1;
    $requestDefinition["finish"] = 12;

    for ($i = 1; $i <= 12; $i++) {
      if (isset($requestPoa['m' . $i])) {
        if ($pond == 0)
          $requestDefinition["start"] = $i;
        elseif ($requestPoa['m' . $i] > 0)
          $requestDefinition["finish"] = $i;
        $pond += $requestPoa['m' . $i];
      }
    }

    $task = Task::findOrFail($id);

    // if ($task->operation->action->goal->configuration->reconfigure) {
    //   $task = Task::Where('id', $task->id)->Update(['status' => '0',]);
    //   $task = Task::create($requestTask);

    //   $task->definitions()->create($requestDefinition);
    //   $task->poas()->create($requestPoa);

    //   \Toastr::success(
    //     "Tarea Reprogramada",
    //     $title = 'REPROGRAMACIÓN',
    //     $options = [
    //       'closeButton' => 'true',
    //       'hideMethod' => 'slideUp',
    //       'closeEasing' => 'easeInBack',
    //     ]
    //   );
    // } else {
      $task->update($requestTask);
      $task->users()->sync($request->users);
      $task->definitions()->update($requestDefinition);
      $task->poas()->Where('state', '0')->update($requestPoa);
      $task->reasons()->create($requestReason);

      $operation = Operation::Where('id', $task->operation_id)->
          where('status', true)->
          first();
      $month = activemonth();
      $m = 'm'.$month;
      if($operation->
          poas()->
          Where('state',true)->
          Where('month',false)->
          first()->$m > 0){
        $execmonth['m' . $month] = execs($task->operation_id,'Operation' ,$month);
        $operation->
          poas()->
          Where('state', true)->
          Where('month',false)->
          Update($execmonth);
     // }
      \Toastr::warning(
        "El registro fue actualizado",
        $title = 'ACTUALIZACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    }
    logrec('html', \Route::currentRouteName());
    return redirect('institution/task')
      ->with('data', $this->data);
  }


  /**
   * Actualizar o reformular tarea Mod1
   */


  public function update_j(TaskRequest $request, $id)
  {
    
    $requestData = $request->all();

    $id = Hashids::decode($id)[0];

    /*        $responsable = User::Where('id',$requestData["user_id"])->pluck('department_id')->first();
            $department = Operation::Where('id',$requestData["operation_id"])->first();
            if($responsable != $department->action->department_id)
            {
                \Toastr::error("El responsable no pertenece al departamento",
                $title = 'ERROR',
                $options = [
                    'closeButton' => 'true',
                    'hideMethod' => 'slideUp',
                    'closeEasing' => 'easeInBack',
                    ]);

                return back()
                    ->withInput($requestData)
                    ->with('data',$this->data);
            } */

    $requestTask = $request->only(
    //'code',
      'operation_id',
    //                        'user_id',
      'plan_id',
      'year'
    );
    $requestDefinition = $request->only(
      'description',
      'measure',
      'base',
      'aim',
      'describe',
      'pointer',
      'validation'
    //                        'start',
    //                        'finish'
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

    $requestReason["description"] = $request->input('reason');
    $requestReason["user_id"] = Auth::user()->id;

    $pond = 0;
    $requestDefinition["start"] = 1;
    $requestDefinition["finish"] = 12;

    for ($i = 1; $i <= 12; $i++) {
      if (isset($requestPoa['m' . $i])) {
        if ($pond == 0)
          $requestDefinition["start"] = $i;
        elseif ($requestPoa['m' . $i] > 0)
          $requestDefinition["finish"] = $i;
        $pond += $requestPoa['m' . $i];
      }
    }
  
    $requestPoa['value'] = activeReprogMonth();
    
    dd($request);
    $task = Task::findOrFail($id);
    /// Se arman los datos de la tarea
    if ($task->operation->action->goal->configuration->reconfigure) {
      /**
       * si estamos reformulando 
       * debemos registrar lA FORMULACION NUEVA EN LA TAREA
       * 
      */
      ///AQUI BIRRA LA TAREA ANTERIOR
      //$task = Task::Where('id', $task->id)->Update(['status' => '0',]);
      ///CREA UNA NUEVA TAREA CON LOS MISMO DATOS
      ///$task = Task::create($requestTask);
      $task->definitions()->create($requestDefinition);
      $task->poas()->create($requestPoa);
      \Toastr::success(
        "Tarea Reprogramada",
        $title = 'REPROGRAMACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    } else {
      $task->update($requestTask);
      $task->users()->sync($request->users);
      $task->definitions()->update($requestDefinition);
      $task->poas()->Where('state', '0')->update($requestPoa);
      $task->reasons()->create($requestReason);

      $operation = Operation::Where('id', $task->operation_id)->
          where('status', true)->
          first();
      $month = activemonth();
      $m = 'm'.$month;
      if($operation->
          poas()->
          Where('state',true)->
          Where('month',false)->
          first()->$m > 0){
        $execmonth['m' . $month] = execs($task->operation_id,'Operation' ,$month);
        $operation->
          poas()->
          Where('state', true)->
          Where('month',false)->
          Update($execmonth);
      }


      \Toastr::warning(
        "El registro fue actualizado",
        $title = 'ACTUALIZACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    }
    logrec('html', \Route::currentRouteName());
    return redirect('institution/task')
      ->with('data', $this->data);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function destroy(Request $request, $id)
  {
    if(!permissions_check('delete',$this->data["active"]))
      return redirect($this->data["url1"]);

    $id = Hashids::decode($id)[0];

    $reason = $request->only('reason');
    $exec = Poa::Where([['poa_id', $id], ['poa_type', 'App\Task'], ['state', true]])
      ->Orderby('month', 'DESC')
      ->get();
    $prog = Poa::Where([['poa_id', $id], ['poa_type', 'App\Task'], ['state', false]])
      ->first();
    $last = $exec->first()->month;
    if ($last > 11) {
      \Toastr::error(
        "No se puede eliminar la tarea",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    } else {
      $totalprog = 0;
      for ($i = 1; $i <= $last; $i++) {
        $totalprog += $prog['m' . $i];
      }
      $delete['m' . ($last + 1)] = 100 - $totalprog;
      if ($last <= 10) {
        for ($i = $last + 2; $i <= 12; $i++) {
          $delete['m' . $i] = 0;
        }
      } else {
        $delete["m12"] = 0;
      }
      // Task::destroy($id);
      $task = Task::findOrFail($id);
      $task->poas()->Where('state', '0')->Update($delete);
      $task = Task::Where('id', $task->id)->Update(['status' => '0', 'reason' => $reason["reason"]]);


      \Toastr::error(
        "El registro fue eliminado",
        $title = 'ELIMINACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    }

    logrec('html', \Route::currentRouteName());
    return redirect('institution/task')
      ->with('data', $this->data);
  }

  public function delete($id)
  {
    if(!permissions_check('delete',$this->data["active"]))
      return redirect($this->data["url1"]);

    $id = Hashids::decode($id)[0];
    $task = Task::findOrFail($id);
    logrec('html', \Route::currentRouteName());
    if (!$task->status) {
      return response('TAREA ELIMINADA', 200)
        ->header('Content-Type', 'text/plain');
    } else {
      if (!Subtask::Where('task_id', $task->id)->get()->count()) {
        return view('layouts.institution.task.delete', compact('task'))
          ->with('data', $this->data);
      } else {
        return response('ERROR: EL REGISTRO NO SE ENCUENTRA VACIO', 200)
          ->header('Content-Type', 'text/plain');
      }
    }
  }
}
