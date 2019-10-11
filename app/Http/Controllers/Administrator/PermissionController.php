<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Hashids;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:Administrador']);

        $this->data = array(
            'active' => 'permission',
            'url1' => '/administrator/permission',
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
                "text" => 'PERMISO',
                "align" => 'left'
            ]
        ]);

        if (!empty($keyword)) {
            $permission = Permission::where('name', 'LIKE', "%$keyword%")
                ->OrderBy('name','ASC')
                ->paginate($perPage);
        } else {
            $permission = Permission::paginate($perPage);
        }

    return view('layouts.administrator.permission.index', compact(['permission','header']))
                    ->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('layouts.administrator.permission.create')
                    ->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PermissionRequest $request)
    {

        $requestData = $request->all();

        Permission::create($requestData);

        \Toastr::success(
                        "Una nuevo registro fue agregado",
                        $title = 'CREACIÓN',
                        $options = [
                            'closeButton' => 'true',
                            'hideMethod' => 'slideUp',
                            'closeEasing' => 'easeInBack',
                            ]);

        return redirect('administrator/permission')
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
        $permission = Permission::findOrFail($id);

        return view('layouts.administrator.permission.show', compact('permission'))
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
        $permission = Permission::findOrFail($id);

        return view('layouts.administrator.permission.edit', compact('permission'))
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
    public function update(PermissionRequest $request, $id)
    {

        $requestData = $request->all();

        $id = Hashids::decode($id)[0];
        $permission = Permission::findOrFail($id);
        $permission->update($requestData);

        \Toastr::warning(
                        "El registro fue actualizado",
                        $title = 'ACTUALIZACIÓN',
                        $options = [
                            'closeButton' => 'true',
                            'hideMethod' => 'slideUp',
                            'closeEasing' => 'easeInBack',
                            ]);

        return redirect('administrator/permission')
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
        $permission = Permission::findOrFail($id);
        $permission->delete();

        \Toastr::error(
            "El registro fue eliminado",
            $title = 'ELIMINACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]);
        return redirect('administrator/permission')
                        ->with('data',$this->data);
    }
}
