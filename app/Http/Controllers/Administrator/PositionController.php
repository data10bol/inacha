<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\PositionRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Position;
use App\Department;
use App\Level;

use Hashids;

class PositionController extends Controller
{


    public function __construct()
    {
        $this->middleware(['role:Administrador']);
        $this->data = array(
            'active' => 'position',
            'url1' => '/administrator/position',
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
            ]
        ]);

        if (!empty($keyword)) {
            $position = Position::where('name', 'LIKE', "%$keyword%")
                ->orderBy('department_id','ASC')
                ->paginate($perPage);
        } else {
            $position = Position::orderBy('department_id','ASC')->paginate($perPage);
        }
        return view('layouts.administrator.position.index', compact(['position','header']))
                    ->with('data',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('layouts.administrator.position.create')
                    ->with('data',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PositionRequest $request)
    {

        $requestData = $request->all();

        $position = Position::create($requestData);

        \Toastr::success(
                        "Una nuevo registro fue agregado",
                        $title = 'CREACIÓN',
                        $options = [
                            'closeButton' => 'true',
                            'hideMethod' => 'slideUp',
                            'closeEasing' => 'easeInBack',
                            ]);

        return redirect('administrator/position')
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
        $position = Position::findOrFail($id);

        return view('layouts.administrator.position.show', compact('position'))
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
        $position = Position::findOrFail($id);

        return view('layouts.administrator.position.edit', compact('position'))
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
    public function update(PositionRequest $request,$id)
    {

        $requestData = $request->all();

        if (!isset($requestData['status']))
            $requestData['status']='0';

        $id = Hashids::decode($id)[0];
        $position = Position::findOrFail($id);
        $position->update($requestData);

        \Toastr::warning(
            "El registro fue actualizado",
            $title = 'ACTUALIZACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]);


        return redirect('administrator/position')
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
        $position = Position::findOrFail($id);
        $position->delete();

        \Toastr::error(
            "El registro fue eliminado",
            $title = 'ELIMINACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]);
        return redirect('administrator/position')
                        ->with('data',$this->data);
    }
}
