<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\PlanRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Plan;
use App\Position;
use App\Department;

use Hashids;

class PlanController extends Controller
{


    public function __construct()
    {
        $this->middleware(['role:Administrador']);
        $this->data = array(
            'active' => 'plan',
            'url1' => '/administrator/plan',
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
                "text" => 'ESTADO',
                "align" => 'center'
            ],[
                "text" => 'DEPARTAMENTO',
                "align" => 'left'
            ],[
                "text" => 'CARGO',
                "align" => 'left'
            ],[
                "text" => 'POAI',
                "align" => 'left'
            ]
        ]);


        if (!empty($keyword)) {
            $plan = Plan::Join('positions','position_id','=','positions.id')
                        ->Join('departments','department_id','=','departments.id')
                        ->Where('plans.name','LIKE',"%$keyword%")
                        ->OrWhere('plans.name','LIKE',"%$keyword%")
                        ->OrWhere('plans.name','LIKE',"%$keyword%")
                        ->Select('plans.id AS id',
                                'departments.id AS department_id',
                                'departments.name AS department',
                                'positions.id AS position_id',
                                'positions.name AS position',
                                'plans.name AS name',
                                'plans.status AS status')
                        ->Orderby('departments.id','ASC')
                        ->Orderby('positions.id','ASC')
                        ->paginate($perPage);

        } else {
            $plan = Plan::Join('positions','position_id','=','positions.id')
                        ->Join('departments','department_id','=','departments.id')
                        ->Select('plans.id AS id',
                                'departments.id AS department_id',
                                'departments.name AS department',
                                'positions.id AS position_id',
                                'positions.name AS position',
                                'plans.name AS name',
                                'plans.status AS status')
                        ->Orderby('departments.id','ASC')
                        ->Orderby('positions.id','ASC')
                        ->paginate($perPage);

        }
        return view('layouts.administrator.plan.index', compact(['plan','header']))
                    ->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('layouts.administrator.plan.create')
                    ->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PlanRequest $request)
    {

        $requestData = $request->except(['department_id']);

        $plan = Plan::create($requestData);

        \Toastr::success(
                        "Una nuevo registro fue agregado",
                        $title = 'CREACIÓN',
                        $options = [
                            'closeButton' => 'true',
                            'hideMethod' => 'slideUp',
                            'closeEasing' => 'easeInBack',
                            ]);

        return redirect('administrator/plan')
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
        $plan = Plan::findOrFail($id);

        return view('layouts.administrator.plan.show', compact('plan'))
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
        $plan = Plan::findOrFail($id);

        return view('layouts.administrator.plan.edit', compact('plan'))
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
    public function update(PlanRequest $request,$id)
    {

        $requestData = $request->except(['department_id']);

        if (!isset($requestData['status']))
            $requestData['status']='0';

        $id = Hashids::decode($id)[0];
        $plan = Plan::findOrFail($id);
        $plan->update($requestData);

        \Toastr::warning(
            "El registro fue actualizado",
            $title = 'ACTUALIZACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]);


        return redirect('administrator/plan')
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
        $plan = Plan::findOrFail($id);
        $plan->delete();

        \Toastr::error(
            "El registro fue eliminado",
            $title = 'ELIMINACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]);
        return redirect('administrator/plan')
                        ->with('data',$this->data);
    }
}
