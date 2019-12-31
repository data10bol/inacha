<?php

namespace App\Http\Controllers\Chief;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\OperationRequest;

use App\Operation;
use App\Task;
use App\Definition;
use App\Action;
use App\Poa;
use App\Reformulation;
use App\Month;
use Auth;

use Hashids;

class OperationController extends Controller
{


  public function __construct()
  {
    $this->middleware(['role:Administrador|Supervisor|Responsable|Usuario']);
    $this->data = array(
      'active' => 'operation',
      'url1' => '/institution/operation',
      'url2' => '/institution/task'
    );
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\View\View
   */
  public function index(Request $request)
  {
    // if(check_reconf())
    //     return view('layouts.partials.banner');
    $keyword = $request->get('search');
    $type = $request->get('type');
    if (isset($type) && $type == "pdf")
      $perPage = 25000;
    else
      $perPage = 25000;

    $header = ([
      [
        "text" => 'CÓDIGO',
        "align" => 'center'
      ], [
        "text" => 'GESTIÓN',
        "align" => 'center'
      ], [
        "text" => 'DEPARTAMENTO',
        "align" => 'left'
      ], [
        "text" => 'ACCIÓN C.P.',
        "align" => 'left'
      ], [
        "text" => 'OPERACIÓN',
        "align" => 'left'
      ], [
        "text" => 'P(%)',
        "align" => 'center'
      ], [
        "text" => 'INICIO',
        "align" => 'center'
      ], [
        "text" => 'FIN',
        "align" => 'center'
      ], [
        "text" => '#TR',
        "align" => 'center'
      ], [
        "text" => 'EST.',
        "align" => 'center'
      ]
    ]);

    $ido = Operation::Join('actions', 'operations.action_id', '=', 'actions.id')
      ->Join('goals', 'actions.goal_id', '=', 'goals.id')
      ->Where('actions.year', activeyear())
      ->OrderBy('goals.code', 'ASC')
      ->Orderby('actions.code', 'ASC')
      ->OrderBy('operations.code', 'ASC')
      ->pluck('operations.id')
      ->toarray();

    if (!empty($keyword)) {
      $ids = search_operation($keyword);

      if (!empty($ids)) {
        $operation = Operation::WhereIn('id', $ids)
          ->OrderBy('action_id', 'ASC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      } else {
        $operation = Operation::orderBy('action_id', 'ASC')
          ->OrderBy('action_id', 'ASC')
          ->OrderBy('code', 'ASC')
          ->paginate($perPage);
      }
    }
    else {
      $operation = Operation::orderBy('action_id', 'ASC')
        ->OrderBy('action_id', 'ASC')
        ->OrderBy('code', 'ASC')
        ->paginate($perPage);
    }

    if (Auth::user()->hasRole('Responsable|Usuario')) {
      $ids = Action::Select('id')
        ->Where('department_id', Auth::user()->position->department->id)
        ->pluck('id')
        ->toarray();

      $operation = Operation::WhereIn('action_id', $ids)
        ->paginate($perPage);
    }

    $months = Month::OrderBy('id', 'ASC')
      ->pluck('name', 'id');

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.institution.operation.report.index',
        compact(['operation', 'months', 'ido', 'header']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());

      return view('layouts.institution.operation.index',
        compact(['operation', 'months', 'ido', 'header']))
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

    logrec('html', \Route::currentRouteName());

    if (!empty($request->get('id'))) {
      $action = Action::Select('id', 'code', 'department_id')
        ->Where('id', $request->get('id'))
        ->first();
//            $code = (int)Operation::Where('action_id',$action->id)->max('code')+1;

      return view('layouts.institution.operation.create')
        ->with('action', $action)
//                ->with('code',$code)
        ->with('data', $this->data);
    } else
      return view('layouts.institution.operation.create')
        ->with('data', $this->data);
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
    $requestData = $request->all();

    $ponderation = 0;

    $operations = Operation::Where('action_id', $requestData["action_id"])
      ->get();
    foreach ($operations as $operation)
      $ponderation += $operation->definitions->pluck('ponderation')->last();

    if (($ponderation + $requestData["ponderation"]) > 100) {
      \Toastr::error("La ponderación supera el 100% de la Acción de Corto Plazo",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);

      return back()
        ->withInput($requestData)
        ->with('data', $this->data);
    }

    $code = (int)Operation::Where('action_id', $requestData["action_id"])
        ->OrderBy('code', 'DESC')
        ->pluck('code')
        ->first() + 1;
    //dd($code);
    $requestOperation = $request->only(
    //'code',
      'action_id'
    );
    $requestOperation["code"] = $code;

    $requestDefinition = $request->only(
      'description',
      'measure',
      'ponderation',
      'base',
      'aim',
      'describe',
      'pointer',
      'validation'
  //                        'start',
  //                        'finish'
    );
    $requestPoa = $request->only(
      'm1',
      'm2',
      'm3',
      'm4',
      'm5',
      'm6',
      'm7',
      'm8',
      'm9',
      'm10',
      'm11',
      'm12'
    );

    $pond = 0;
    $requestDefinition["start"] = 1;
    $requestDefinition["finish"] = 12;

    for ($i = 1; $i <= 12; $i++) {
      if (isset($requestPoa['m' . $i])) {
        if ($pond == 0)
          $requestDefinition["start"] = $i;
        elseif ($requestPoa['m' . $i] > 0)
          $requestDefinition["finish"] = $i;
        $pond += $requestPoa['m' . $i];
      }
    }

    $operation = Operation::create($requestOperation);
    if(reprogcheck()){
      ///creamos el doble con uno solo en ceros y con el corte reprogramado vacio
      $requestPoa1= array(); 
      for($k=1; $k <= 12; $k++) {///asignamos ceros a todos los meses
        $requestPoa1['m'.$k] = 0;
      }
      $requestPoa1['state'] = false;
      $requestPoa1['value'] = false;
      $requestPoa1['month'] = false;

     

      $requestPoa1['out'] = activeReprogMonth()-1;
      $operation->poas()->create($requestPoa1);///creamos planificacion en ceros
      //$requestPoa1;

      $requestPoa['value'] = activeReprogMonth();///creamos value uno a la nueva planificacion
      $requestPoa['in'] = activeReprogMonth();
      $requestDefinition1 = $requestDefinition;
      $requestDefinition1['out'] = activeReprogMonth()-1;
      $requestDefinition['in'] = activeReprogMonth();
      $operation->definitions()->create($requestDefinition1);      
      $operation->definitions()->create($requestDefinition);
      $operation->poas()->create($requestPoa);
      $operation->poas()->create(['state' => '1']);

    }else{///aqui creamos desde cero
      $operation->definitions()->create($requestDefinition);
      $operation->poas()->create($requestPoa);
      $operation->poas()->create(['state' => '1'],['out' => activeReprogMonth()-1 ]);///y creamos una ejecucion con ceros
      $operation->poas()->create(['state' => '1'],['in' => activeReprogMonth()],['value' => activeReprogMonth()]);
    }

    \Toastr::success(
      "Una nuevo registro fue agregado",
      $title = 'CREACIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);
      /// Actualizamos la accion dependiente
    $action = Action::FindorFail($operation->action_id);
    for ($i = 1; $i <= 12; $i++){
      if(reprogcheck()){
        $prog['m'.$i] = action_reprogrammed($action->id, $i);
      } else{
        $prog['m'.$i] = action_programmed($action->id, $i);
      }
    }
      
    if(!reprogcheck()){
      $action->poas()->Where('state',false)->Where('month',false)->update($prog);
    }else{
      $action->poas()->Where('state',false)->Where('month',false)->Where('value', activeReprogMonth())->update($prog);
    }
    logrec('html', \Route::currentRouteName());

    return redirect('institution/operation')
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
    
    if(!permissions_check('read',$this->data["active"]))
      return redirect($this->data["url1"]);

    if (strpos($id, '-') === false)
      $id = Hashids::decode($id)[0];
    else {
      list($temp, $type) = explode("-", $id);
      $id = Hashids::decode($temp)[0];
      unset($temp);
    }
    //dd($id);
    $operation = Operation::findOrFail($id);

    $months = Month::OrderBy('id','ASC')->get();

    $tks = Task::Where('operation_id',$operation->id)
      ->OrderBy('code')
      ->get();

    if (isset($type) && $type == "pdf") {
      logrec('pdf', \Route::currentRouteName());

      $view = \View::make('layouts.institution.operation.report.show',
        compact(['operation','months','tks']))
        ->with('data', $this->data);

      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream();
    } else {
      logrec('html', \Route::currentRouteName());
      return view('layouts.institution.operation.show',
        compact(['operation','months','tks']))
        ->with('data', $this->data);
    }
  }
  /**
   * Show the form for editing the specified resource.

   * @param  int $id
   *
   * @return \Illuminate\View\View
   */
  public function edit($id)
  {
    if(!permissions_check('edit',$this->data["active"]))
      return redirect($this->data["url1"]);
    
    $id = Hashids::decode($id)[0];
    if(accum($id,'operation',false,activeReprogMonth()-1)>99){ ////preguntamos si la operacion fue completada 
      \Toastr::error("La Operación ya fue concluida",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
        return back();
    }
    if(cheking_poa_rep($id, 'operation')){
      if(cheking_poa_active($id, 'operation')){
        \Toastr::error("La Operación ya fue reprogramada",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
      }else{
        \Toastr::error("La Operación fue anulada",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
      }
      return back();
    }
    $operation = Operation::findOrFail($id);
    logrec('html', \Route::currentRouteName());
    return view('layouts.institution.operation.edit',
      compact('operation'))
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
  public function update(OperationRequest $request, $id)
  {
    
    //deshasheamos el ID 
    $requestData = $request->all();
    $id = Hashids::decode($id)[0];
    $ponderation = 0;///inicializamos
    $operations = Operation::Where('action_id', $requestData["action_id"])->
                  Where('id', '!=', $id)->
                  get();///tomamos las operariones que no sean la operacion a prosesar y sumamos sus ponderaciones

    /**
     * Se toma el ultimo registro de las ponderaciones
     */

    foreach ($operations as $operation)
      $ponderation += $operation->definitions->pluck('ponderation')->last();

    if (($ponderation + $requestData["ponderation"]) > 100) {
      \Toastr::error("La ponderación supera el 100% de la Acción a Mediano Plazo",
        $title = 'ERROR',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
      return back()
        ->withInput($requestData)
        ->with('data', $this->data);
    }

    //dd($request);
    $requestOperation = $request->only(
    //'code',
      'action_id',
      'year'
    );
    $requestDefinition = $request->only(
      'description',
      'measure',
      'ponderation',
      'base',
      'aim',
      'describe',
      'pointer',
      'validation'
  //                        'start',
  //                        'finish'
    );
    $requestPoa = $request->only(
      'm1',
      'm2',
      'm3',
      'm4',
      'm5',
      'm6',
      'm7',
      'm8',
      'm9',
      'm10',
      'm11',
      'm12'
    );
    
    $pond = 0;
    $requestDefinition["start"] = 1;
    $requestDefinition["finish"] = 12;
    for ($i = 1; $i <= 12; $i++) {
      if (isset($requestPoa['m' . $i])) {
        if ($pond == 0)
          $requestDefinition["start"] = $i;
        elseif ($requestPoa['m' . $i] > 0)
          $requestDefinition["finish"] = $i;
        $pond += $requestPoa['m' . $i];
      }
    }
    $operation = Operation::findOrFail($id);
    // REPROGRAMACIÓN
    // dd($requestDefinition);
    // Duplicar operación y acción
    $quanty = $operation->poas()->where('state',0)->count();//sacamos la cantida de reformulaciones q se colocara en value;
    
    if (reprogcheck()){
      // aquí consultamos si hay alguna reprogramación anterior
      // $quanty = $operation->poas()->where('state',0)->count();//sacamos la cantida de reformulaciones q se colocara en value;
      // dd($quanty);

      // si estoy reprogramando tengo que preguntar a mi accion en que reprogramacion estoy
      // de esa forma asignare el value de la accion en la cual esto y reprogramando
      $ref = Reformulation::where('month',activemonth())->where('year',activeyear())->first();
      if($ref->status == 0){
        $ref->status = 1;
        $ref->save();
      }
      $current_action_ref = $operation->action()->pluck('current')->first();
      if($current_action_ref<$ref->month){
        //$operation->action()->update('current',$ref->month);
        $action = \App\Action::find ($operation->action()->pluck('id')->first());
        $action->current = $ref->month;
        $action->save();
        $current_action_ref = $ref->month;
        ///ajustamos por q deberiamos crear la programacion y ejecucion nuevas de una accion 
      }
      
      ///aqui mismo deberiamos crear los replanificado y ejecutado nuevos
      //estas acciones solo se realizan con la primera reprogramacion 

      $prev_def = $operation->definitions()->orderBy('id','desc')->first();///obtenemos la ultima definicion 'del año'
      $prev_poa = $operation->poas()->where('state',false)->orderBy('id','desc')->first(); // aqui obtenemos el ultimo poa programado
      $prev_poae = $operation->poas()->where('state',true)->first(); // aqui obtenemos el ultimo poa ejecutado en 'el año'
      $prev_def->update(['out' => $current_action_ref-1]);
      $prev_poa->update(['out' => $current_action_ref-1]);
      $prev_poae->update(['out' => $current_action_ref-1]);

      //$operation->definitions()->update(['out' => activemonth()-1]);
      ///movemos el ultimo out a el mes actual menos 1
      $requestDefinition["in"] = $ref->month;///aqui ajustamos a la nueva definicion con in del mes actual
      $operation->definitions()->create($requestDefinition);
      //$requestPoa->value = $quanty ;
      $nwpoa = $operation->poas()->create($requestPoa);////colocamos la nueva reprogramacion 
      //dd($nwpoa);      
      $nwpoa->value = $current_action_ref;
      $nwpoa->in = $ref->month;
      $nwpoae = $operation->poas()->create($operation->poas()->where('state',true)->where('month',false)->first()->toarray());////colocamos la nueva reprogramacion ejecutada 
      $nwpoae->value = $current_action_ref;
      $nwpoae->in = $ref->month;
      $nwpoae->out = 12;
      ///asignamos ceros apartir de la ejecucion de la nueva reprogramacion 
      $nwpoa->save();
      $nwpoae->save();
      
      \Toastr::success("Operación Reprogramada",
        $title = 'REPROGRAMACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
    }

    // ACTUALIZACIÓN

    else {
      $operation->update($requestOperation);
      $operation->definitions()->update($requestDefinition);
      $operation->poas()->Where('state', '0')->Where('value', false)->update($requestPoa);

      \Toastr::warning(
        "El registro fue actualizado",
        $title = 'ACTUALIZACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
    }
    

    //dd('hecho');

    /////actualizamos reformulacion en acciones
    $action = Action::FindorFail($operation->action_id);
    if(!reprogcheck())
    {
      for ($i = 1; $i <= 12; $i++)
        $prog['m'.$i] = action_programmed($action->id, $i);///ejectutamos la programacion 
    }
    else 
    {
      for ($i = 1; $i <= 12; $i++)
        $prog['m'.$i] = action_reprogrammed($action->id, $i); ///ejecutamso la reprogramacion 
    }
    ///generamos las reprogramaciones de cada mes en la accion 

    if(!reprogcheck()){
      $action->poas()->Where('state',false)->Where('month',false)->Where('value',false)->update($prog);///aqui estamos programando
    }else{   
      ///ver si la accion esta correctamente realizada en la pregunta y formularla biwen
      //podemos preguntar si en mis operaciones ya tengo un value igual a micurrent  si esos son mas de uno signufivca que ya lo cree
        ///preguntamos cuantas operacion es tenemso con el current si son mas de uno 
        $cant = cant_op_curr($action);
        
        if($cant <=1){///otra
            // y creamos el nuevo prog
            // dd('creando');
            $prog['state'] = 0;
            $prog['value'] = $action->current;
            $prog['month'] = 0;
            $prog['in']=activeReprogMonth();
            $progPrev['out']=activeReprogMonth()-1;
            ///definimos parametros de los nuevos elementos

            $action->poas()->Where('state',false)->Where('month',false)->orderby('id','desc')->first()->update($progPrev);
            // aqui estamos actualizando el prog anterior que ayamos registrado
            $action->poas()->create($prog);///y creamos el nuevo programado reprog

            $ant_eje = $action->poas()->where('state', true)->Where('month',false)->orderBy('id','desc')->first();
            //$action->poas()->Where('state',true)->Where('month',false)->orderby('id','desc')->first();///aqui estamos haciendo algo con el ultimo ejecutado
            $actR_eje = $action->poas()->create($action->poas()->where('state', true)->Where('month',false)->orderBy('id','desc')->first()->toarray()); ////
            ///copiamos y creamos un nuevo poa de ejecucion
            $ant_eje->update($progPrev);

            // y el ejecutado anterior lo actualizamos tambien una ves duplicado
            $action->poas()->where('state', true)->orderBy('id','desc')->first()->update(['out' => activemonth()-1]);
            $actR_eje = \App\Poa::find($actR_eje->id);
            $actR_eje->value = $action->current;
            $actR_eje->in = activeReprogMonth();
            $actR_eje->out = 12;
            $actR_eje->save();
            
            // aqui diplcamos la accion 
          
        }else{
          // dd('actualizando');
          $action->poas()->Where('value',$action->current)->Where('state',false)->Where('month',false)->update($prog);
        }
        // listamos las tareas que dependan de la operacion
        if($request->ponderation==0){
          $tasks = $operation->tasks->all();
          // dd($tasks);
          foreach($tasks as $task){
            $tasky = \App\Task::find($task->id);            
            $tasky->status = 0;
            $tasky->save();
          }
        }
        refresh_last_poa_op($operation->id);
    }
    
    logrec('html', \Route::currentRouteName());
    return redirect('institution/operation')
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

    if (!Task::Where('operation_id', $id)->get()->count()) {
      $operation = Operation::findOrFail($id);

      foreach ($operation->poas->pluck('id') as $poa) {
        Poa::where('id', $poa)->delete();
      }

      foreach ($operation->definitions->pluck('id') as $definition) {
        Definition::where('id', $definition)->delete();
      }

      foreach ($operation->structures->pluck('id') as $structure) {
        Structure::where('id', $structure)->delete();
      }

      $operation->delete();

      \Toastr::error(
        "El registro fue eliminado",
        $title = 'ELIMINACIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    } else {
      \Toastr::warning(
        "El registro NO se encuentra vacío",
        $title = 'ERROR EN BORRADO',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]
      );
    }

    logrec('html', \Route::currentRouteName());
    return redirect('institution/operation')
      ->with('data', $this->data);
  }

}
