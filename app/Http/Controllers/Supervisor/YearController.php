<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\YearRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use DB;
use App\Period;
use App\Year;

use Hashids;

class YearController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor']);
    $this->data = array(
      'active' => 'year',
      'url1' => '/government/year',
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
    $keyword = $request->get('search');
    $type = $request->get('type');
    $perPage = 25;

    $header = ([
      [
        "text" => '#',
        "align" => 'center'
      ], [
        "text" => 'id',
        "align" => 'center'
      ], [
        "text" => 'ACTUAL',
        "align" => 'center'
      ], [
        "text" => 'ESTADO',
        "align" => 'center'
      ], [
        "text" => 'PERIODO',
        "align" => 'left'
      ], [
        "text" => 'GESTIÓN',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $year = Year::Where('name', 'LIKE', "%$keyword%")
        ->OrderBy('name', 'ASC')
        ->paginate($perPage);
    } else {
      $year = Year::OrderBy('name', 'ASC')
        ->paginate($perPage);
    }


    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.year.report.index', compact(['year', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.year.index', compact(['year', 'header']))
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

    return view('layouts.government.year.create')
      ->with('data', $this->data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(YearRequest $request)
  {

    $requestData = $request->all();

    logrec('html', \Route::currentRouteName());

    if (Period::Where('start', '<=', $requestData["name"])
      ->Where('finish', '>=', $requestData["name"])
      ->Where('id', $requestData["period_id"])
      ->exists()) {
      if (!isset($requestData['status']))
        $requestData['status'] = '0';

      if (!isset($requestData['current']))
        $requestData['current'] = '0';
      else
        $status = Year::where('id', '<>', $id)->update(['current' => '0']);

      $year = Year::create($requestData);

      \Toastr::success(
        "Una nuevo registro fue agregado",
        $title = 'CREACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );

      return redirect('government/year')
        ->with('data', $this->data);

    } else {
      \Toastr::error(
        "La gestión es incorrecta",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );

      return back()
        ->withInput($requestData)
        ->with('data', $this->data);

    }

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

    $year = Year::findOrFail($id);

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.year.report.show', compact('year'))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.year.show', compact('year'))
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
    $year = Year::findOrFail($id);

    logrec('html', \Route::currentRouteName());

    return view('layouts.government.year.edit',
      compact(['year']))
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
  public function update(YearRequest $request, $id)
  {

    $requestData = $request->all();

    $id = Hashids::decode($id)[0];
    logrec('html', \Route::currentRouteName());

    if (Period::Where('start', '<=', $requestData["name"])
      ->Where('finish', '>=', $requestData["name"])
      ->Where('id', $requestData["period_id"])
      ->exists()) {

      if (!isset($requestData['status']))
        $requestData['status'] = '0';

      if (!isset($requestData['current']))
        $requestData['current'] = '0';
      else
        $current = Year::where('id', '<>', $id)
          ->update(['current' => '0']);

      $year = Year::findOrFail($id);

      $year->update($requestData);

      \Toastr::warning(
        "El registro fue actualizado",
        $title = 'ACTUALIZACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );

      return redirect('government/year')
        ->with('data', $this->data);

    } else {
      \Toastr::error(
        "La gestión es incorrecta",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );

      return back()
        ->withInput($requestData)
        ->with('data', $this->data);

    }

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
    $year = Year::findOrFail($id);

    $year->delete();

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

    return redirect('government/year')
      ->with('data', $this->data);
  }
}
