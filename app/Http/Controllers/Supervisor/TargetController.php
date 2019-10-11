<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\TargetRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use App\Policy;
use App\Target;
use App\Description;

use Hashids;

class TargetController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor']);
    $this->data = array(
      'active' => 'target',
      'url1' => '/government/target',
      'url2' => '/government/result'
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
        "text" => 'CÓDIGO',
        "align" => 'center'
      ], [
        "text" => 'PILAR',
        "align" => 'left'
      ], [
        "text" => 'META',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $ids = Description::Select('description_id')
        ->Where('description', 'LIKE', "%$keyword%")
        ->Where('description_type', 'App\Target')
        ->pluck('description_id')
        ->all();
      if (!empty($ids)) {
        $target = Target::WhereIn('id', $ids)
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      } else {
        $target = Target::orderBy('policy_id', 'ASC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      }
    } else {
      $target = Target::orderBy('policy_id', 'ASC')
        ->OrderBy('code', 'ASC')
        ->paginate($perPage);
    }

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.target.report.index',
        compact(['target', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.target.index',
        compact(['target', 'header']))
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
      $policy = Policy::Select('id', 'code')
        ->Where('id', $request
          ->get('id'))
        ->first();
      $code = (int)Target::Where('policy_id', $policy->id)
          ->max('code') + 1;
      return view('layouts.government.target.create')
        ->with('policy', $policy)
        ->with('code', $code)
        ->with('data', $this->data);
    } else
      return view('layouts.government.target.create')
        ->with('data', $this->data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(TargetRequest $request)
  {

    $requestData = $request->except('description');
    $requestDescription = $request->only('description');

    logrec('html', \Route::currentRouteName());

    $code = (int)Target::Where('policy_id', $requestData["policy_id"])
        ->OrderBy('code', 'DESC')
        ->pluck('code')->first() + 1;
    if ($requestData["code"] == $code) {
      $target = Target::create($requestData);
      $target->descriptions()->create($requestDescription);

      \Toastr::success(
        "Una nuevo registro fue agregado",
        $title = 'CREACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );

      return redirect('government/target')
        ->with('data', $this->data);
    } else {

      \Toastr::error(
        "El código para la meta debe ser " . $code,
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );

      return back()
        //->withInput(request(['username']));
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

    $target = Target::findOrFail($id);

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.target.report.show',
        compact('target'))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.target.show',
        compact('target'))
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
    $target = Target::findOrFail($id);
    $description = $target
      ->descriptions
      ->pluck('description')
      ->first();

    logrec('html', \Route::currentRouteName());

    return view('layouts.government.target.edit',
      compact(['target', 'description']))
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
  public function update(TargetRequest $request, $id)
  {

    $requestData = $request->except('description');
    $requestDescription = $request->only('description');

    $id = Hashids::decode($id)[0];
    $target = Target::findOrFail($id);
    $target->update($requestData);
    $target->descriptions()->update($requestDescription);

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

    return redirect('government/target')
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
    $target = Target::findOrFail($id);

    foreach ($target->doings->pluck('id') as $doing) {
      Description::where([['description_id', $doing],
        ['description_type', 'App\Doing']])->delete();
    }

    Description::where([['description_id', $target->id],
      ['description_type', 'App\Target']])->delete();

    $target->delete();

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

    return redirect('government/target')
      ->with('data', $this->data);
  }
}
