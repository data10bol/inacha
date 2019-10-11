<?php

namespace App\Http\Controllers\Chief;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\SubtaskRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Subtask;
use App\Task;
use App\Definition;
use App\Position;
use App\User;
use App\Poa;
use App\Goal;
use App\Month;
use Auth;

use Hashids;

class SubtaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Administrador|Supervisor|Responsable|Usuario']);
        $this->data = array(
            'active' => 'subtask',
            'url1' => '/institution/subtask',
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

        if (!empty($keyword)) {
            $ids = Subtask::Select('id')
            ->Where('description', 'LIKE', "%$keyword%")
            ->pluck('definition_id')
            ->all();
        } else {
            $subtask = Subtask::orderBy('task_id', 'ASC')
                            ->OrderBy('code', 'ASC')
                            ->paginate($perPage);
        }

        if (Auth::user()->hasRole('Responsable')) {
            $idp = Position::Select('id')
                    ->Where('department_id', Auth::user()->position->department->id)
                    ->pluck('id')
                    ->toarray();
            $idu = User::Select('id')
                    ->Wherein('position_id', $idp)
                    ->pluck('id')
                    ->toarray();
            $ids = Task::Select('id')
                    ->Wherein('user_id', $idu)
                    ->pluck('id')
                    ->toarray();

            $subtask = $subtask->WhereIn('task_id', $ids)->all();
        }

        if (Auth::user()->hasRole('Usuario')) {
            $ids = Task::Select('id')
                ->Where('user_id', Auth::user()->id)
                ->pluck('id')
                ->toarray();

            $subtask = $subtask->WhereIn('task_id', $ids)->all();
        }

        return view('layouts.institution.subtask.index', compact('subtask'))
                    ->with('data', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        if (!empty($request->get('id'))) {
            $task = Task::Where('id', $request->get('id'))->first();
//            $code = (int)Subtask::Where('task_id',$subtask->id)->max('code')+1;

            if (!$task->status) {
                return redirect()->route('subtask.index');
            } else {
                return view('layouts.institution.subtask.create')
                                ->with('task', $task)
                //                ->with('code',$code)
                                ->with('data', $this->data);
            }
        } else {
            return view('layouts.institution.subtask.create')
                        ->with('data', $this->data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SubtaskRequest $request)
    {
        $requestData = $request->all();

        $code = (int)Subtask::Where('task_id', $requestData["task_id"])
                ->OrderBy('code', 'DESC')
                ->pluck('code')
                ->first() +1;

        $requestSubtask = $request->only(
                        'task_id',
                        'user_id',
                        'description',
                        'validation',
                        'start',
                        'finish'
                    );
        $requestSubtask["code"] = $code;

        $subtask = Subtask::create($requestSubtask);

        \Toastr::success(
            "Una nuevo registro fue agregado",
            $title = 'CREACIÓN',
            $options = [
                'closeButton' => 'true',
                'hideMethod' => 'slideUp',
                'closeEasing' => 'easeInBack',
                ]
        );

        return redirect('institution/subtask')
                        ->with('data', $this->data);
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
        $subtask = Subtask::findOrFail($id);

        return view('layouts.institution.subtask.show', compact('subtask'))
                    ->with('data', $this->data);
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
        $subtask = Subtask::findOrFail($id);

        if ($subtask->status) {
            return view('layouts.institution.subtask.show', compact('subtask'))
                                ->with('data', $this->data);
        } else {
            return view('layouts.institution.subtask.edit', compact('subtask'))
                        ->with('data', $this->data);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(SubtaskRequest $request, $id)
    {
        $requestData = $request->all();

        $id = Hashids::decode($id)[0];

        $requestSubtask = $request->only(
                        'description',
                        'validation',
                        'start',
                        'finish',
                        'status'
                    );

        $subtask = Subtask::findOrFail($id);

        $subtask->update($requestSubtask);

        \Toastr::warning(
                "El registro fue actualizado",
                $title = 'ACTUALIZACIÓN',
                $options = [
                    'closeButton' => 'true',
                    'hideMethod' => 'slideUp',
                    'closeEasing' => 'easeInBack',
                    ]
            );

        return redirect('institution/subtask')
                        ->with('data', $this->data);
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

        $subtask = Subtask::findOrFail($id);

        $subtask->delete();

        \Toastr::error(
                "El registro fue eliminado",
                $title = 'ELIMINACIÓN',
                $options = [
                    'closeButton' => 'true',
                    'hideMethod' => 'slideUp',
                    'closeEasing' => 'easeInBack',
                ]
            );

        return redirect('institution/subtask')
                        ->with('data', $this->data);
    }
}
