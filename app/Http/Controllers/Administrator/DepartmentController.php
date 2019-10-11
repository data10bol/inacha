<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Traits\HasRoles;

use App\User;
use App\Department;
use App\Level;

use Hashids;

class DepartmentController extends Controller
{


    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware(['role:Administrador']);

        $this->data = array(
            'active' => 'department',
            'url1' => '/administrator/department',
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
                    "text" => 'NOMBRE',
                    "align" => 'left'
                ],[
                    "text" => 'DEPENDENCIA',
                    "align" => 'left'
                ],[
                    "text" => 'RESPONSABLE',
                    "align" => 'left'
                ]
            ]);

        if (!empty($keyword)) {
            $department = Department::where('name', 'LIKE', "%$keyword%")
                ->orderBy('id','ASC')
                ->paginate($perPage);
        } else {
            $department = Department::orderBy('id','ASC')->paginate($perPage);
        }

        return view('layouts.administrator.department.index',
                        compact(['department','header']))
                    ->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('layouts.administrator.department.create')
                    ->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(DepartmentRequest $request)
    {

        $requestData = $request->all();

        $department = Department::create($requestData);

        \Toastr::success(
                        "Una nuevo registro fue agregado",
                        $title = 'CREACIÓN',
                        $options = [
                            'closeButton' => 'true',
                            'hideMethod' => 'slideUp',
                            'closeEasing' => 'easeInBack',
                            ]);

        return redirect('administrator/department')
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
        $department = Department::findOrFail($id);

        return view('layouts.administrator.department.show', compact('department'))
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
        $department = Department::findOrFail($id);

        return view('layouts.administrator.department.edit', compact('department'))
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
    public function update(DepartmentRequest $request,$id)
    {

        $requestData = $request->all();

        $id = Hashids::decode($id)[0];
        $department = Department::findOrFail($id);
        $department->update($requestData);

        \Toastr::warning(
            "El registro fue actualizado",
            $title = 'ACTUALIZACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]);


        return redirect('administrator/department')
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
        $department = Department::findOrFail($id);
        $department->delete();

        \Toastr::error(
            "El registro fue eliminado",
            $title = 'ELIMINACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]);
        return redirect('administrator/department')
                        ->with('data',$this->data);
    }
}
