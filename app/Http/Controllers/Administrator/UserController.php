<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\User;
use App\Department;
use App\Level;

use Hashids;

class UserController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador']);
    $this->data = array(
      'active' => 'user',
      'url1' => '/administrator/user',
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
        "text" => 'C.I.',
        "align" => 'left'
      ], [
        "text" => 'NOMBRE',
        "align" => 'left'
      ], [
        "text" => 'DEPARTAMENTO',
        "align" => 'left'
      ], [
        "text" => 'CARGO',
        "align" => 'left'
      ], [
        "text" => 'ROLES',
        "align" => 'left'
      ]
    ]);

    if (!empty($keyword)) {
      $user = User::where('employee', 'LIKE', "%$keyword%")
        ->orWhere('username', 'LIKE', "%$keyword%")
        ->orWhere('name', 'LIKE', "%$keyword%")
        ->orderBy('name', 'ASC')
        ->paginate($perPage);
    } else {
      $user = User::orderBy('name', 'ASC')->paginate($perPage);
    }
    return view('layouts.administrator.user.index', compact(['user', 'header']))
      ->with('data', $this->data);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    return view('layouts.administrator.user.create')
      ->with('data', $this->data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(UserRequest $request)
  {

    $requestData = $request->except(['roles', 'department_id']);
    $roles = $request->roles;

    $user = User::create($requestData);

    $user->assignRole($roles);

    \Toastr::success(
      "Una nuevo registro fue agregado",
      $title = 'CREACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);

    return redirect('administrator/user')
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

    $id = Hashids::decode($id)[0];
    $user = User::findOrFail($id);

    return view('layouts.administrator.user.show', compact('user'))
      ->with('data', $this->data);
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
    $id = Hashids::decode($id)[0];
    $user = User::findOrFail($id);

    return view('layouts.administrator.user.edit', compact('user'))
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
  public function update(UserRequest $request, $id)
  {

    $requestData = $request->except([
      'roles',
      'pos_id',
      'dep_id',
      'usr_id',
      'department_id'
    ]);
    $roles = $request->roles;
    if (!isset($requestData['status']))
      $requestData['status'] = '0';

    $id = Hashids::decode($id)[0];
    $user = User::findOrFail($id);
    $user->update($requestData);

    $user->syncRoles($roles);

    \Toastr::warning(
      "El registro fue actualizado",
      $title = 'ACTUALIZACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);


    return redirect('administrator/user')
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

    $id = Hashids::decode($id)[0];
    $user = User::findOrFail($id);
    $user->delete();

    \Toastr::error(
      "El registro fue eliminado",
      $title = 'ELIMINACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);
    return redirect('administrator/user')
      ->with('data', $this->data);
  }
}
