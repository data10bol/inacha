<?php

namespace App\Http\Controllers\Report;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Operation;
use App\Action;
use App\Position;
use App\User;
use App\Task;
use App\Poa;
use App\Goal;
use App\Month;
use Auth;

use Hashids;

class ChartController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor|Responsable']);
    $this->data = array(
      'active' => 'chart.poa',
      'url1' => '',
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
  //dd('o');
    $actions = Action::Where('year',activeyear())->
                get();
    if (!empty($request->get('month')))
      $month = $request->get('month');
    else
      $month = activemonth();

    $months = Month::OrderBy('id', 'ASC')->
              pluck('name', 'id');
    $cnt = 0;
    $departments = \App\Action::Select('department_id')->
                    GroupBy('department_id')->
                    pluck('department_id')->
                    toarray();
    $naction=0;
    $name_ofep="OFEP";
    $chartaccuma_ofep = chartaccum(0,
      'Total',
      'Accum', $month,
      'charta');
    $chartaccumm_ofep = chartaccum(0,
      'Total',
      'Month', $month,
      'chartm');
    $chartaccumma_ofep = chartaccum(0,
      'Total',
      'AccumMonth', $month,
      'chartam');
    foreach ($departments as $department) {
      $naction++;
      $chart_accum[$naction] =       chartaccum($department,'Department', 'Accum', $month, 'charta' . $naction);
      $chart_month[$naction] =       chartaccum($department, 'Department','Month', $month, 'chartm' . $naction);
      $chart_accum_month[$naction] = chartaccum($department, 'Department','AccumMonth',$month, 'chartam' . $naction);
    }

      logrec('html', \Route::currentRouteName());

    return view('layouts.report.chart.index',
      compact(['months',
                'departments',
                'chart_accum',
                'chart_month',
                'chart_accum_month']))
      ->with('name_ofep', $name_ofep)
      ->with('chartaccuma_ofep', $chartaccuma_ofep)
      ->with('chartaccumm_ofep', $chartaccumm_ofep)
      ->with('chartaccumma_ofep', $chartaccumma_ofep)
      ->with('currentmonth', $month)
      ->with('data', $this->data);
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
