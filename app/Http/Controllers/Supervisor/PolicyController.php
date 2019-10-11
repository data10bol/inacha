<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\PolicyRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use DB;
use App\Period;
use App\Policy;
use App\Description;

use Hashids;

class PolicyController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor']);
    $this->data = array(
      'active' => 'policy',
      'url1' => '/government/policy',
      'url2' => '/government/target'
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
        "text" => 'PERIODO',
        "align" => 'center'
      ], [
        "text" => 'CÓDIGO',
        "align" => 'center'
      ], [
        "text" => 'PILAR',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $ids = Description::Select('description_id')
        ->Where('description', 'LIKE', "%$keyword%")
        ->Where('description_type', 'App\Policy')
        ->pluck('description_id')
        ->all();
      if (!empty($ids)) {
        $policy = Policy::WhereIn('id', $ids)
          ->orderBy('period_id', 'DESC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      } else {
        $policy = Policy::orderBy('period_id', 'DESC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      }
    } else {
      $policy = Policy::orderBy('period_id', 'DESC')
        ->OrderBy('code', 'ASC')
        ->paginate($perPage);
    }

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());
      $view = \View::make('layouts.government.policy.report.index',
        compact(['policy', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.policy.index',
        compact(['policy', 'header']))
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
      $period = Period::Select('id')
        ->Where('id', $request->get('id'))
        ->first();
      $code = (int)Policy::Where('id', $period->id)
          ->max('code') + 1;
      return view('layouts.government.policy.create')
        ->with('period', $period)
        ->with('code', $code)
        ->with('data', $this->data);
    } else
      return view('layouts.government.policy.create')
        ->with('data', $this->data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(PolicyRequest $request)
  {

    logrec('html', \Route::currentRouteName());

    $requestData = $request->except('description');
    $requestDescription = $request->only('description');

    $code = (int)Policy::Where('period_id', $requestData["period_id"])
        ->OrderBy('code', 'DESC')
        ->pluck('code')
        ->first() + 1;
    if ($requestData["code"] == $code) {
      $policy = Policy::create($requestData);
      $policy->descriptions()->create($requestDescription);

      \Toastr::success(
        "Una nuevo registro fue agregado",
        $title = 'CREACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );

      return redirect('government/policy')
        ->with('data', $this->data);
    } else {

      \Toastr::error(
        "El código para el pilar debe ser " . $code,
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

    $policy = Policy::findOrFail($id);

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.policy.report.show',
        compact('policy'))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.policy.show',
        compact('policy'))
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
    $policy = Policy::findOrFail($id);
    $description = $policy
      ->descriptions
      ->pluck('description')
      ->first();

    logrec('html', \Route::currentRouteName());

    return view('layouts.government.policy.edit',
      compact(['policy', 'description']))
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
  public function update(PolicyRequest $request, $id)
  {

    $requestData = $request->except('description');
    $requestDescription = $request->only('description');

    $id = Hashids::decode($id)[0];
    $policy = Policy::findOrFail($id);
    $policy->update($requestData);
    $policy->descriptions()->update($requestDescription);

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

    return redirect('government/policy')
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
    $policy = Policy::findOrFail($id);

    foreach ($policy->doings->pluck('id') as $doing) {
      Description::where([['description_id', $doing],
        ['description_type', 'App\Doing']])->delete();
    }

    foreach ($policy->targets->pluck('id') as $target) {
      Description::where([['description_id', $target],
        ['description_type', 'App\Target']])->delete();
    }

    Description::where([['description_id', $policy->id],
      ['description_type', 'App\Policy']])->delete();

    $policy->delete();

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

    return redirect('government/policy')
      ->with('data', $this->data);
  }
}
