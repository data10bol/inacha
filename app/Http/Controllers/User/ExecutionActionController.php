<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ExecutionActionRequest;
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

class ExecutionActionController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor|Responsable']);
    $this->data = array(
      'active' => 'execution.action',
      'url1' => '/execution/action',
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
        "text" => 'ACCIÓN LARGO PLAZO',
        "align" => 'left'
      ], [
        "text" => 'ACCIÓN CORTO PLAZO',
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
        "text" => '#OP',
        "align" => 'center'
      ]
    ]);

    $ida = Action::Join('goals', 'actions.goal_id', '=', 'goals.id')->
      Where('actions.year', activeyear())->
      OrderBy('goals.code', 'ASC')->
      Orderby('actions.code', 'ASC')->
      pluck('actions.id')->
      toarray();
        
    if (!empty($keyword)) {
      $ids = search_action($keyword);

      if (!empty($ids)) {
        $action = Action::WhereIn('id', $ids)
          ->Where('year',activeyear())
          ->OrderBy('goal_id', 'ASC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      } else {
        $action = Action::orderBy('goal_id', 'ASC')
          ->Where('year',activeyear())
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      }
    } else {
      $action = Action::orderBy('goal_id', 'ASC')
      ->Where('year',activeyear())
        ->OrderBy('code', 'ASC')
        ->paginate($perPage);
    }

    if (Auth::user()->hasRole('Responsable')) {

      $ids = Action::Select('id')
        ->Where('department_id', Auth::user()->position->department->id)
        ->pluck('id')
        ->toarray();

      $action = Action::Wherein('id', $ids)
        ->paginate($perPage);
    }

    $months = Month::OrderBy('id', 'ASC')
      ->pluck('name', 'id');

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.execution.action.report.index',
        compact(['action', 'months', 'ida', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.execution.action.index',
        compact(['action', 'months', 'ida', 'header']))
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

    $action = Action::findOrFail($id);

    $months = Month::OrderBy('id','ASC')->get();

    $chart_accum = chartaccum($action->id,'Action','Accum',activemonth(),'charta');
    $chart_month = chartaccum($action->id, 'Action', 'Month', activemonth(), 'chartm');
    $chart_accum_month = chartaccum($action->id, 'Action', 'AccumMonth', activemonth(), 'chartam');


    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.execution.action.report.show',
        compact(['action','months']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.execution.action.show',
        compact(['action',
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
