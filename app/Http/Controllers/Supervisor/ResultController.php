<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ResultRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use App\Policy;
use App\Target;
use App\Result;
use App\Description;

use Hashids;

class ResultController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor']);
    $this->data = array(
      'active' => 'result',
      'url1' => '/government/result',
      'url2' => '/government/doing'
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
      ], [
        "text" => 'RESULTADO',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $ids = Description::Select('description_id')
        ->Where('description', 'LIKE', "%$keyword%")
        ->Where('description_type', 'App\Result')
        ->pluck('description_id')
        ->all();
      if (!empty($ids)) {
        $result = Result::WhereIn('id', $ids)
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      } else {
        $result = Result::orderBy('target_id', 'ASC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      }
    } else {
      $result = Result::orderBy('target_id', 'ASC')
        ->OrderBy('code', 'ASC')
        ->paginate($perPage);
    }

    if (isset($type) && $type == "pdf") {
      activity('pdf')->causedBy(\Auth::user()->id)
        ->withProperties(['IP' => \Request::ip(),
          'Name' => \Auth::user()->name,
        ])
        ->log(\Route::currentRouteName());

      $view = \View::make('layouts.government.result.report.index',
        compact(['result', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      activity('index')->causedBy(\Auth::user()->id)
        ->withProperties(['IP' => \Request::ip(),
          'Name' => \Auth::user()->name,
        ])
        ->log(\Route::currentRouteName() . '|' . \Route::currentRouteAction());

      return view('layouts.government.result.index', compact(['result', 'header']))
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

    if (!empty($request->get('id'))) {
      $target = Target::Select('id', 'code')->Where('id', $request->get('id'))->first();
      $code = (int)Result::Where('target_id', $target->id)->max('code') + 1;

      return view('layouts.government.result.create')
        ->with('target', $target)
        ->with('code', $code)
        ->with('data', $this->data);
    } else
      return view('layouts.government.result.create')
        ->with('data', $this->data);

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(ResultRequest $request)
  {

    $requestData = $request->except('description');
    $requestDescription = $request->only('description');

    $code = (int)Result::Where('target_id', $requestData["target_id"])
        ->OrderBy('code', 'DESC')
        ->pluck('code')
        ->first() + 1;
    if ($requestData["code"] == $code) {
      $result = Result::create($requestData);
      $result->descriptions()->create($requestDescription);

      \Toastr::success(
        "Una nuevo registro fue agregado",
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

    $result = Result::findOrFail($id);

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.government.result.report.show',
        compact('result'))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.government.result.show',
        compact('result'))
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
    $result = Result::findOrFail($id);

    $description = $result
      ->descriptions
      ->pluck('description')
      ->first();

    return view('layouts.government.result.edit',
      compact(['result', 'description']))
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
  public function update(ResultRequest $request, $id)
  {

    $requestData = $request->except('description');
    $requestDescription = $request->only('description');

    $id = Hashids::decode($id)[0];
    $result = Result::findOrFail($id);
    $result->update($requestData);
    $result->descriptions()->update($requestDescription);

    \Toastr::warning(
      "El registro fue actualizado",
      $title = 'ACTUALIZACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]
    );

    return redirect('government/result')
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
    $result = Result::findOrFail($id);

    Description::where([['description_id', $result->id],
      ['description_type', 'App\Result']])->delete();

    $result->delete();

    \Toastr::error(
      "El registro fue eliminado",
      $title = 'ELIMINACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]
    );

    return redirect('government/doing')
      ->with('data', $this->data);
  }
}
