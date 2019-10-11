<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\PeriodRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use Dompdf\Dompdf;
use App\Period;
use App\Year;

use Hashids;

class PeriodController extends Controller
{

  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor']);
    $this->data = array(
      'active' => 'period',
      'url1' => '/government/period',
      'url2' => '/government/policy'
    );
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\View\View
   */
  public function index(Request $request)
  {
    $keyword = $request->get('search');
    $type = $request->get('type');

    if (isset($type) && $type == "pdf")
      $perPage = 25000;
    else
      $perPage = 25;

    $header = ([
      [
        "text" => '#',
        "align" => 'center'
      ], [
        "text" => 'id',
        "align" => 'center'
      ], [
        "text" => 'ESTADO',
        "align" => 'center'
      ], [
        "text" => 'ACTUAL',
        "align" => 'center'
      ], [
        "text" => 'INICIO',
        "align" => 'left'
      ], [
        "text" => 'FINAL',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $period = Period::Where('start', 'LIKE', "%$keyword%")
        ->OrWhere('finish', 'LIKE', "%$keyword%")
        ->OrderBy('id', 'ASC')
        ->paginate($perPage);
    }

    else {
      $period = Period::OrderBy('id', 'ASC')
        ->paginate($perPage);
    }

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.period.report.index',
        compact(['period', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    }

    else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.period.index',
        compact(['period', 'header']))
        ->with('data', $this->data);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    if (!permissions_check('create', $this->data["active"]))
      return redirect($this->data["url1"]);

    logrec('html', \Route::currentRouteName());

    return view('layouts.government.period.create')
      ->with('data', $this->data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(PeriodRequest $request)
  {

    $requestData = $request->all();

    $lastyear = Period::max('finish');

    if ($lastyear < $requestData['start']) {
      $period_id = Period::create($requestData)->id;
      for ($i = $requestData['start']; $i <= $requestData['finish']; $i++) {
        $requestYear['name'] = $i;
        $requestYear['period_id'] = $period_id;
        $year = Year::create($requestYear);
      }
      \Toastr::success(
        "Una nuevo registro fue agregado",
        $title = 'CREACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    } else {
      \Toastr::error(
        "ERROR en el rango del periodo",
        $title = 'CREACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    }

    logrec('html', \Route::currentRouteName());

    return redirect('government/period')
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
    if (!permissions_check('read', $this->data["active"]))
      return redirect($this->data["url1"]);

    if (strpos($id, '-') === false)
      $id = Hashids::decode($id)[0];
    else {
      list($temp, $type) = explode("-", $id);
      $id = Hashids::decode($temp)[0];
      unset($temp);
    }

    $period = Period::findOrFail($id);

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.period.report.show',
        compact('period'))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.period.show',
        compact('period'))
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
    if (!permissions_check('edit', $this->data["active"]))
      return redirect($this->data["url1"]);

    $id = Hashids::decode($id)[0];
    $period = Period::findOrFail($id);

    logrec('html', \Route::currentRouteName());

    return view('layouts.government.period.edit',
      compact('period'))
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
  public function update(PeriodRequest $request, $id)
  {

    $requestData = $request->all();

    $id = Hashids::decode($id)[0];
    $period = Period::findOrFail($id);
    if (!isset($requestData['status']))
      $requestData['status'] = '0';

    if (!isset($requestData['current']))
      $requestData['current'] = '0';
    else
      $current = Period::where('id', '<>', $id)->update(['current' => '0']);

    $period->update($requestData);
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

    return redirect('government/period')
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
    if (!permissions_check('delete', $this->data["active"]))
      return redirect($this->data["url1"]);

    $id = Hashids::decode($id)[0];
    Period::destroy($id);
    \Toastr::error(
      "El registro fue eliminado",
      $title = 'ELIMINACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]
    );

    logrec('html', \Route::currentRouteName());

    return redirect('government/period')
      ->with('data', $this->data);
  }
}
