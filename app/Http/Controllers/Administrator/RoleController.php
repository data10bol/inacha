<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Hashids;

class RoleController extends Controller
{


    public function __construct()
    {
        $this->middleware(['role:Administrador']);
        $this->data = array(
            'active' => 'role',
            'url1' => '/administrator/role',
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
            ],[
                "text" => 'id',
                "align" => 'center'
            ],[
                "text" => 'ROL',
                "align" => 'left'
            ],[
                "text" => 'PERMISOS',
                "align" => 'left'
            ]
        ]);

        if (!empty($keyword)) {
            $role = Role::where('name', 'LIKE', "%$keyword%")
                ->OrderBy('name','ASC')
                ->paginate($perPage);
        } else {
            $role = Role::paginate($perPage);
        }

        return view('layouts.administrator.role.index', compact(['role','header']))
                    ->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $permissions = Permission::get()->pluck('name','name');
        return view('layouts.administrator.role.create',compact('permissions'))
                    ->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(RoleRequest $request)
    {

        $requestData = $request->except('permissions');
        $permissions = $request->permissions;


        $role = Role::create($requestData);

        $role->givePermissionTo($permissions);
        \Toastr::success(
            "Una nuevo registro fue agregado",
            $title = 'CREACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]);

        return redirect('administrator/role')
                        ->with('data',$this->data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $id = Hashids::decode($id)[0];
        $role = Role::findOrFail($id);

        return view('layouts.administrator.role.show', compact('role'))
                    ->with('data',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $id = Hashids::decode($id)[0];
        $role = Role::findOrFail($id);

        $permissions = Permission::get()->pluck('name','name');
        return view('layouts.administrator.role.edit', compact('role','permissions'))
                    ->with('data',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(RoleRequest $request, $id)
    {

        $requestData = $request->except('permissions');
        $permissions = $request->permissions;

        $id = Hashids::decode($id)[0];
        $role = Role::findOrFail($id);
        $role->update($requestData);

        $role->syncPermissions($permissions);
        \Toastr::warning(
                        "El registro fue actualizado",
                        $title = 'ACTUALIZACIÓN',
                        $options = [
                            'closeButton' => 'true',
                            'hideMethod' => 'slideUp',
                            'closeEasing' => 'easeInBack',
                            ]);

        return redirect('administrator/role')
                        ->with('data',$this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $id = Hashids::decode($id)[0];
        $role = Role::findOrFail($id);
        $role->delete();

        \Toastr::error(
            "El registro fue eliminado",
            $title = 'ELIMINACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]);
        return redirect('administrator/role')
                        ->with('data',$this->data);
    }
}
