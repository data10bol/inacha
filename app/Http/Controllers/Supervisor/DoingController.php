<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\DoingRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use App\Policy;
use App\Target;
use App\Result;
use App\Doing;
use App\Description;

use Hashids;

class DoingController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor']);
    $this->data = array(
      'active' => 'doing',
      'url1' => '/government/doing',
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
      $perPage = 25;
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
      ], [
        "text" => 'RESULTADO',
        "align" => 'left'
      ], [
        "text" => 'ACCIÓN',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $ids = Description::Select('description_id')
        ->Where('description', 'LIKE', "%$keyword%")
        ->Where('description_type', 'App\Doing')
        ->pluck('description_id')
        ->all();
      if (!empty($ids)) {
        $doing = Doing::WhereIn('id', $ids)
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      } else {
        $doing = Doing::orderBy('result_id', 'ASC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      }
    } else {
      $doing = Doing::orderBy('result_id', 'ASC')
        ->OrderBy('code', 'ASC')
        ->paginate($perPage);
    }

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.doing.report.index',
        compact(['doing', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.doing.index',
        compact(['doing', 'header']))
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
      $result = Result::Select('id', 'code')
        ->Where('id', $request
        ->get('id'))
        ->first();
      $code = (int)Doing::Where('target_id', $result->id)->max('code') + 1;

      return view('layouts.government.doing.create')
        ->with('target', $result)
        ->with('code', $code)
        ->with('data', $this->data);
    } else
      return view('layouts.government.doing.create')
        ->with('data', $this->data);

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(DoingRequest $request)
  {

    $requestData = $request->except('description');
    $requestDescription = $request->only('description');

    $code = (int)Doing::Where('target_id', $requestData["target_id"])
        ->OrderBy('code', 'DESC')
        ->pluck('code')
        ->first() + 1;

    logrec('html', \Route::currentRouteName());

    if ($requestData["code"] == $code) {
      $doing = Doing::create($requestData);
      $doing->descriptions()->create($requestDescription);

      \Toastr::success(
        "Un nuevo resultado fue agregado",
        $title = 'CREACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );

      return redirect('government/doing')
        ->with('data', $this->data);
    } else {
      \Toastr::error(
        "El código para el resultado debe ser " . $code,
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

    $doing = Doing::findOrFail($id);

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.doing.report.show',
        compact('doing'))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.doing.show',
        compact('doing'))
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
    $doing = Doing::findOrFail($id);

    $description = $doing
      ->descriptions
      ->pluck('description')
      ->first();
    logrec('html', \Route::currentRouteName());
    return view('layouts.government.doing.edit',
      compact(['doing', 'description']))
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
  public function update(DoingRequest $request, $id)
  {

    $requestData = $request->except('description');
    $requestDescription = $request->only('description');

    $id = Hashids::decode($id)[0];
    $doing = Doing::findOrFail($id);
    $doing->update($requestData);
    $doing->descriptions()->update($requestDescription);

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
    return redirect('government/doing')
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
    $doing = Doing::findOrFail($id);

    Description::where([['description_id', $doing->id],
      ['description_type', 'App\Doing']])->delete();

    $doing->delete();

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
    return redirect('government/doing')
      ->with('data', $this->data);
  }
}
