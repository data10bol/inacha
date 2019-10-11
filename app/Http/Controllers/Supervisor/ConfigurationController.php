<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ConfigurationRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use App\Configuration;
use App\Goal;
use App\Reformulation;

use Hashids;

class ConfigurationController extends Controller
{

  public function __construct()
  {
    $this->middleware(['role:Usuario|Responsable|Administrador|Supervisor']);
    $this->data = array(
      'active' => 'configuration',
      'url1' => '/institution/configuration',
      'url2' => '/institution/goal'
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
        "text" => 'PERIODO',
        "align" => 'center'
      ], [
        "text" => 'ESTADO',
        "align" => 'center'
      ], [
        "text" => 'MISIÓN',
        "align" => 'left'
      ], [
        "text" => 'VISIÓN',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $configuration = Configuration::OrderBy('period_id', 'ASC')
        ->Where('mision', 'LIKE', "%$keyword%")
        ->OrWhere('vision', 'LIKE', "%$keyword%")
        ->paginate($perPage);
    } else {
      $configuration = Configuration::OrderBy('period_id', 'ASC')
        ->paginate($perPage);
    }

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.institution.configuration.report.index',
        compact(['configuration', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.institution.configuration.index',
        compact(['configuration', 'header']))
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
    if(!permissions_check('create',$this->data["active"]))
      return redirect($this->data["url1"]);

    logrec('html', \Route::currentRouteName());
    return view('layouts.institution.configuration.create')
      ->with('data', $this->data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(ConfigurationRequest $request)
  {

    $requestData = $request->all();

    Configuration::create($requestData);
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
    return redirect('institution/configuration')
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

    $configuration = Configuration::findOrFail($id);

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.institution.configuration.report.show',
        compact('configuration'))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());
      return view('layouts.institution.configuration.show',
        compact('configuration'))
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
    $configuration = Configuration::findOrFail($id);
    logrec('html', \Route::currentRouteName());
    return view('layouts.institution.configuration.edit',
      compact('configuration'))
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
  public function update(ConfigurationRequest $request, $id)
  {

    $requestData = $request->all();

    $id = Hashids::decode($id)[0];
    $configuration = Configuration::findOrFail($id);
    if (!isset($requestData['edit']))
      $requestData['edit'] = '0';
    if (!isset($requestData['reconfigure']))
      $requestData['reconfigure'] = '0';
    if (!isset($requestData['status']))
      $requestData['status'] = '0';
    else
      $status = Configuration::where('id', '<>', $id)->update(['status' => '0']);

    $configuration->update($requestData);
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
    return redirect('institution/configuration')
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
    if(!permissions_check('delete',$this->data["active"]))
      return redirect($this->data["url1"]);

    $id = Hashids::decode($id)[0];
    logrec('html', \Route::currentRouteName());
    if (!Goal::Where('configuration_id', $id)->get()->count()) {
      $configuration = Configuration::findOrFail($id);

      $configuration->delete();

      \Toastr::error(
        "El registro fue eliminado",
        $title = 'ELIMINACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    } else {
      \Toastr::warning(
        "El registro NO se encuentra vacío",
        $title = 'ERROR EN BORRADO',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    }
    return redirect('institution/configuration')
      ->with('data', $this->data);
  }

  public function addReformulation(Request $request){
    //dd($request);
    $ref = Reformulation::where('month',activemonth())->where('year',activeyear())->count();
    if($ref == 0){
      $reformulation = new Reformulation;
      $reformulation->reasons = $request->reasons;
      //dd($request);
      $reformulation->month = activemonth();
      $reformulation->year = activeyear();
      $reformulation->status = false;
      if($reformulation->save()){
        return response()->json('1');
      }else{
        return response()->json('0');
      }
    }else{
      $ref = Reformulation::where('month',activemonth())->where('year',activeyear())->first();
      $ref->reasons = $request->reasons;
      if($ref->save()){
        return response()->json('1');
      }else{
        return response()->json('0');
      }
    }
  }

  public function statusReprog(){
    $ref = Reformulation::where('month',activemonth())->where('year',activeyear())->first();
    if(!is_null($ref)){
      return response()->json($ref);
    }else{
      return response()->json(0);
    }
  }



}
