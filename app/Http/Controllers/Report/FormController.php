<?php

namespace App\Http\Controllers\Report;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Operation;
use App\Configuration;
use App\Action;
use App\Poa;
use App\Goal;
use App\Month;

use Hashids;

class FormController extends Controller
{


    public function __construct()
    {
        $this->middleware(['role:Administrador|Supervisor|Responsable']);
        $this->data = array(
            'active' => 'form.poa',
            'url1' => '/formpoa',
            'url2' => '/pdf',
            'url3' => '/excel',
            );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $forms = [
            [
                'name' => 'Formulario N° 1',
                'description' => 'ARTICULACIÓN POA Y PRESUPUESTO ANUAL',
                'link' => 'form1',
            ],[
                'name' => 'Formulario N° 2',
                'description' => 'RELACION ACCIONES DE CORTO PLAZO - OPERACIÓN',
                'link' => 'form2',
            ],[
                'name' => 'Formulario N° 3',
                'description' => 'RELACION OPERACIÓN - TAREA',
                'link' => 'form3',
            ],[
                'name' => 'Presupuesto',
                'description' => 'MEMORIA DE CALCULO',
                'link' => 'presupuesto',
            ],[
                'name' => 'Consultorias',
                'description' => 'MATRIZ DE CONSULTORIAS ',
                'link' => 'consultorias',
            ],[
                'name' => 'Eventuales',
                'description' => 'MATRIZ DE PERSONAL EVENTUAL',
                'link' => 'eventuales',
            ]
        ];

        $months = Month::OrderBy('id','ASC')
                ->pluck('name','id');
        return view('layouts.report.form.index', compact(['action','forms']))
                ->with('data',$this->data);
    }

    public function form1(Request $request)
    {
        $goals = Goal::orderBy('doing_id', 'ASC')
                ->OrderBy('code', 'ASC')
                ->get();
        $config = Configuration::Where('status', true)
                ->first();

        return view('layouts.report.form.form1')
                ->with('config',$config)
                ->with('goals',$goals)
                ->with('data',$this->data);
    }

    public function form2(Request $request)
    {
        return view('layouts.report.form.form1')
                ->with('data',$this->data);
    }

    public function form3(Request $request)
    {
        return view('layouts.report.form.form1')
                ->with('data',$this->data);
    }

    public function presupuesto(Request $request)
    {
        return view('layouts.report.form.form1')
                ->with('data',$this->data);
    }

    public function consultorias(Request $request)
    {
        return view('layouts.report.form.form1')
                ->with('data',$this->data);
    }

    public function eventuales(Request $request)
    {
        return view('layouts.report.form.form1')
                ->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(OperationRequest $request)
    {
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(OperationRequest $request,$id)
    {
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
    }
}
