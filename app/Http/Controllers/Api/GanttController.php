<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Http\Request;

use App\Operation;
use App\Task;
use App\Definition;
use App\Position;
use App\User;
use App\Poa;
use App\Goal;
use App\Month;
use App\Reason;

use Hashids;


class GanttController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
      $id = Hashids::decode($id)[0];
      $user = User::FindorFail($id);
      $data = [];
      $link = [];
      $cnt=0;
      $parent = 0;

      if ($user->hasRole('Responsable|Usuario')) {
        $dep_id = $user->position->department->id;
        $id_operations = Operation::Join('actions', 'operations.action_id', '=', 'actions.id')
          ->Join('goals', 'actions.goal_id', '=', 'goals.id')
          ->Where('actions.year', activeyear())
          ->Where('actions.department_id', $dep_id)
          ->OrderBy('goals.code', 'ASC')
          ->Orderby('actions.code', 'ASC')
          ->OrderBy('operations.code', 'ASC')
          ->pluck('operations.id')
          ->all();
      }
      else{
        $id_operations = Operation::Join('actions', 'operations.action_id', '=', 'actions.id')
          ->Join('goals', 'actions.goal_id', '=', 'goals.id')
          ->Where('actions.year', activeyear())
          ->OrderBy('goals.code', 'ASC')
          ->Orderby('actions.code', 'ASC')
          ->OrderBy('operations.code', 'ASC')
          ->pluck('operations.id')
          ->all();
      }

      foreach ($id_operations as $item){
        $operation = Operation::findOrFail($item);
        $data[$cnt]["id"] = $cnt+1;
        $data[$cnt]["text"] = $operation->definitions->first()->description;
        $data[$cnt]["type"] = 'gantt.config.types.project';
        $data[$cnt]["color"] ="gray";
        $data[$cnt]["duration"] = duration_days($operation->id,'Operation');
        $data[$cnt]["progress"] = progress($operation->id,'Operation')/100;
        $data[$cnt]["start_date"] = date('Y-m-d H:i:s', strtotime(activeyear().'-'.$operation->definitions->first()->start));;
        $data[$cnt]["parent"] = 0;
        $data[$cnt]["created_at"] = null;
        $data[$cnt]["updated_at"] = null;
        $data[$cnt]["open"] = false;
        $cnt++;
        $parent = $cnt;

        $id_tasks = Task::Join('operations', 'operation_id', '=', 'operations.id')
          ->Join('actions', 'operations.action_id', '=', 'actions.id')
          ->Join('goals', 'actions.goal_id', '=', 'goals.id')
          ->Where('actions.year', activeyear())
          ->Where('tasks.operation_id',$item)
          ->OrderBy('goals.code', 'ASC')
          ->Orderby('actions.code', 'ASC')
          ->OrderBy('operations.code', 'ASC')
          ->OrderBy('tasks.code', 'ASC')
          ->pluck('tasks.id')
          ->all();
        foreach ($id_tasks as $id) {
          $task = Task::findOrFail($id);
          $data[$cnt]["id"] = $cnt+1;
          $data[$cnt]["text"] = $task->definitions->first()->description;
          $data[$cnt]["color"] ="red";
          $data[$cnt]["duration"] = duration_days($task->id,'Task');
          $data[$cnt]["progress"] = progress($task->id,'Task')/100;
          $data[$cnt]["start_date"] = date('Y-m-d H:i:s', strtotime(activeyear() . '-' . $task->definitions->first()->start));
          $data[$cnt]["parent"] = $parent;
          $data[$cnt]["created_at"] = null;
          $data[$cnt]["updated_at"] = null;
          $data[$cnt]["open"] = true;
          $cnt++;
        }
      }

      return response()->json([
        "data" => $data,
        "link" => $link,
        ]);
    }
}

function duration_days($id,$type){
  $diff = 0;
  if($type == 'Operation'){
    $item = Operation::findOrFail($id);
  }
  elseif($type == 'Task'){
    $item = Task::findOrFail($id);
  }
  else
    $item = 0;
  if(isset($item)){
    $start = strtotime(activeyear() . '-' . $item->definitions->first()->start.'-01');
    $finish = strtotime(activeyear() .
      '-' . $item->definitions->first()->finish.
      '-'.date('t',strtotime(activeyear().'-'.$item->definitions->first()->finish))    );
    $diff = round(($finish - $start) / (60 * 60 * 24));
  }
  return $diff;
}

function progress($id,$type){
  $progress = 0;
  if($type == 'Operation'){
    $item = Operation::findOrFail($id);
  }
  elseif($type == 'Task'){
    $item = Task::findOrFail($id);
  }
  else
    $item = 0;
  if(isset($item)){
    $poas = Poa::Where('poa_id',$item->id)
      ->Where('poa_type','App\\'.$type)
      ->Where('state',true)
      ->Where('value',false)
      ->OrderBy('month','asc')
      ->get();
    foreach ($poas as $poa){
      $aux = 0;
      for($i=1;$i<=12;$i++){
        $aux += $poa['m'.$i];
      }
      $progress += $aux;
    }
  }
  return $progress;
}
