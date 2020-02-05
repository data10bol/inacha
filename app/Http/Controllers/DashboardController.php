<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Administrator\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Task;
use App\Operation;
use App\Action;

use App\User;

use App\Month;

class DashboardController extends Controller
{
  public function __construct()
  {
    $this->middleware(['role:Usuario|Responsable|Supervisor|Administrador']);
  }

  public function index()
  {
    if (!empty(Auth::user()->status)) {
      
      $action = new Action;
      $operation = new Operation;
      $task = new Task;

      if (Auth::user()->hasRole('Administrador|Supervisor')) {
        $actions = $action->Where('year', activeyear())->
        OrderBy('code', 'ASC')->
        get();
      }
      else {
        $actions = $action->Where('department_id', Auth::user()->position->department->id)->
        Where('year', activeyear())->
        OrderBy('code', 'ASC')->
        get();
      }
      
      if(count ($actions) == 0){
        $operations = null;
        $tasks = null;
        $chartaccuma = null;
        $chartaccumm = null;
        $chartaccumma = null;
        $name="OFEP";
        $month = ucfirst(\App\Month::Where('id', activemonth())->first()->name);
        return view('layouts.dashboard')->
        with('actions', $actions)->
        with('operations', $operations)->
        with('tasks', $tasks)->
        with('chartaccuma', $chartaccuma)->
        with('chartaccumm', $chartaccumm)->
        with('chartaccumma', $chartaccumma)->
        with('name', $name)->
        with('month', $month);
      }

      $actions_ordered = implode(',', $actions->pluck('id')->toarray());

      $operations = $operation->Wherein('action_id', $actions->pluck('id')->toArray())->
      orderByRaw(DB::raw("FIELD(action_id, $actions_ordered)"))->
      OrderBy('code', 'ASC')->
      get();

      if(count ($operations) == 0){
        $tasks = null;
        $chartaccuma = null;
        $chartaccumm = null;
        $chartaccumma = null;
        $name="OFEP";
        $month = ucfirst(\App\Month::Where('id', activemonth())->first()->name);
        return view('layouts.dashboard')->
        with('actions', $actions)->
        with('operations', $operations)->
        with('tasks', $tasks)->
        with('chartaccuma', $chartaccuma)->
        with('chartaccumm', $chartaccumm)->
        with('chartaccumma', $chartaccumma)->
        with('name', $name)->
        with('month', $month);
      }
      $operations_ordered = implode(',', $operations->pluck('id')->toarray());

      // TASKS FAST FILLING

      if (Auth::user()->hasRole('Usuario')) {
        
        $user_id = Auth::user()->id;

        $tasks = $task->//Wherein('operation_id', $operations->pluck('id')->toArray())->
                        orderByRaw(DB::raw("FIELD(operation_id, $operations_ordered)"))->
                        Where('created_at','>',(string)activeyear())->
                        WhereHas('users', 
                        function ($q)use ($user_id) {
                          $q->where('user_id', $user_id);
                        })->
                        orderBy('code', 'ASC')->
                        get();
      } else {
        
        $tasks = $task->//Wherein('operation_id', $operations->pluck('id')->toArray())->
        where('created_at' ,'>',(string)activeyear())->
        orderByRaw(DB::raw("FIELD(operation_id, $operations_ordered)"))->

        orderBy('code', 'ASC')->
        get();
      }
      // END TASKS FAST FILLING

      // RESUME GENERATOR

      $month = ucfirst(\App\Month::Where('id', activemonth())->first()->name);

      if (Auth::user()->hasRole('Administrador|Supervisor')) {
        $name="OFEP";
        $chartaccuma = chartaccum(0,
          'Total',
          'Accum', activemonth(),
          'charta');
        $chartaccumm = chartaccum(0,
          'Total',
          'Month', activemonth(),
          'chartm');
        $chartaccumma = chartaccum(0,
          'Total',
          'AccumMonth',
          activemonth(),
          'chartam');
      }

      elseif(Auth::user()->hasRole('Responsable|Usuario')) {
        $name=strtoupper(Auth::user()->position->department->name);
        $chartaccuma = chartaccum(Auth::user()->position->department->id,
          'Department',
          'Accum', activemonth(),
          'charta');
        $chartaccumm = chartaccum(Auth::user()->position->department->id,
          'Department',
          'Month', activemonth(),
          'chartm');
        $chartaccumma = chartaccum(Auth::user()->position->department->id,
          'Department',
          'AccumMonth',
          activemonth(),
          'chartam');
      }

      else{
        $name ="";
        $chartaccuma = " ";
        $chartaccumm = " ";
        $chartaccumma = " ";
      }

      // END RESUME GENERATOR
      logrec('home', \Route::currentRouteName());

      return view('layouts.dashboard')->
      with('actions', $actions)->
      with('operations', $operations)->
      with('tasks', $tasks)->
      with('chartaccuma', $chartaccuma)->
      with('chartaccumm', $chartaccumm)->
      with('chartaccumma', $chartaccumma)->
      with('name', $name)->
      with('month', $month);
    } else {
      Auth::logout();
      \Flash::error("Usuario Suspendido")->important();
      return redirect('/');
    }
  }

  public function sidebar()
  {

    $user = new User;

    $user = $user->find(\Auth::user()->id);

    if ($user->sidebar)
      $user->sidebar = false;
    else
      $user->sidebar = true;

    $user->update();
  }

}
