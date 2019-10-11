<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\LimitRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


use App\Year;
use App\Limit;

use Hashids;

class LimitController extends Controller
{

  public function __construct()
  {

    $this->middleware('auth');
    $this->middleware(['role:Administrador|Supervisor']);

    $this->data = array(
      'active' => 'limit',
      'url1' => '/administrator/limit',
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

    $limit = new Limit;

    $keyword = $request->get('search');
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
        "text" => 'GESTIÓN',
        "align" => 'left'
      ], [
        "text" => 'MES',
        "align" => 'left'
      ], [
        "text" => 'FECHA LÍMITE',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $limit = $limit->where('date', 'LIKE', "%$keyword%")->
      orderBy('year_id', 'ASC')->
      orderBy('month', 'ASC')->
      paginate($perPage);
    } else {
      $limit = $limit->orderBy('year_id', 'ASC')->
      orderBy('month', 'ASC')->
      paginate($perPage);
    }

    return view('layouts.administrator.limit.index',
      compact(['limit',
        'header']))
      ->with('data', $this->data)
      ->with('title', 'Índice');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    return view('layouts.administrator.limit.create')
      ->with('data', $this->data)
      ->with('title', 'Crear');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(LimitRequest $request)
  {

    $limit = new Limit;

    $requestData = $request->all();

    $new = $limit->create($requestData);

    if (!isset($requestData['status']))
      $requestData['status'] = '0';
    else
      $limit->where('id', '<>', $new->id)->
      update(['status' => '0']);

    \Toastr::success(
      "Una nuevo registro fue agregado",
      $title = 'CREACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);

    return redirect('administrator/limit')
      ->with('data', $this->data)
      ->with('title', 'Índice');
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

    $limit = new Limit;

    $id = Hashids::decode($id)[0];

    $limit = $limit->FindOrFail($id);

    return view('layouts.administrator.limit.show', compact('limit'))
      ->with('data', $this->data)
      ->with('title', 'Item');
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
    $limit = new Limit;

    $id = Hashids::decode($id)[0];

    $limit = $limit->FindOrFail($id);

    return view('layouts.administrator.limit.edit', compact('limit'))
      ->with('data', $this->data)
      ->with('title', 'Editar');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param  int $id
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function update(LimitRequest $request, $id)
  {

    $limit = new Limit;

    $requestData = $request->all();

    $id = Hashids::decode($id)[0];

    if (!isset($requestData['status']))
      $requestData['status'] = '0';
    else
      $limit->where('id', '<>', $id)->
      update(['status' => '0']);


    $limit = $limit->findOrFail($id);

    $limit->update($requestData);

    \Toastr::warning(
      "El registro fue actualizado",
      $title = 'ACTUALIZACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);


    return redirect('administrator/limit')
      ->with('data', $this->data)
      ->with('title', 'Índice');
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

    $limit = new Limit;

    $id = Hashids::decode($id)[0];

    $limit = $limit->findOrFail($id);

    $limit->delete();

    \Toastr::error(
      "El registro fue eliminado",
      $title = 'ELIMINACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);

    return redirect('administrator/limit')
      ->with('data', $this->data)
      ->with('title', 'Índice');
  }
}
