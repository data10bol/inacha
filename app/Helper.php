<?php

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN ACCUM
| Devuelve el valor tipo float, del total acumulado de la programación o
| ejecución de una tarea, operación, acción o departamento hasta
| el mes de consulta.
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| (ID, TIPO, PROGRAMACIÓN / EJECUCIÓN, MES)
|
| $state = false; PROGRAMACIÓN
| $state = true; EJECUCIÓN
| $type = Task; TAREAS
| $type = Operation; OPERACIONES
| $type = Action; ACCIONES
| $type = Department; DEPARTAMENTOS
| $type = Total; TOTAL DE LAS ACCIONES
| $id = ID; Tarea, Operación, Acción o Departamento
| $month = Número de mes. Ej 1 - Enero
|--------------------------------------------------------------------------
*** */

use App\Definition;
use App\Operation;
use App\Poa;
///accum($id, $type, false, $i,true)

function accum($id, $type, $state, $month,$rep = false)
{
  if(!$rep){
    $total = 0;
    $type = strtoupper($type);

    if($state)
      $state = true;
    else
      $state = false;

    switch ($type){
      case ('TASK'):
        $type = 'App\Task';
        break;
      case ('OPERATION'):
        $type = 'App\Operation';
        break;
      case ('ACTION'):
        $type = 'App\Action';
        break;
    }

    if($type == 'DEPARTMENT'){
      if(activeyear()<=2019){
        $action = new \App\Action;
        $actions = $action->
        Where('department_id',$id)->
        Where('year',activeyear())->
        Where('status',true)->
        get();
        if(isset($actions)){

          foreach ($actions as $item)
            $total += accum($item->id,'Action',$state,$month, $rep);

          $total /= sizeof($actions);
        }
      }else{
        
        $yer = (string)activeyear();
        $ids_operations = \App\Operation::where('created_at','>',$yer)->pluck('id')->toArray();
        $definitions = \App\Definition::where('definition_type','App\operation')->
                                        wherein('definition_id',$ids_operations)->
                                        where('department_id',$id)->
                                        pluck('definition_id')->
                                        toArray();
                          
                                        ///generamos los IDs de las operaicones relacionadas al Departamento
        $operations = \App\Operation::wherein('id',$definitions)->get();
        if(isset($operations)){
          foreach ($operations as $item ) {
            $total += (accum($item->id,'Operation',$state,$month, $rep)*$item->definitions->last()->dep_ponderation)/100;
          }
        }
      }

    }
    elseif($type == 'TOTAL'){
      $action = new \App\Action;
      $actions = $action->
      Where('year',activeyear())->
      Where('status',true)->
      get();

      if(isset($actions)){
        $goals = $action->Select('goal_id')->
        Where('year',activeyear())->
        GroupBy('goal_id')->
        get();
        $goals = count($goals);
        ///contamos los goals

        foreach ($actions as $item)
          $total += accum($item->id,'Action',$state,$month)*0.01*$item->
                    definitions()->
                    first()->ponderation;

        $total /= $goals;
        
      }else{
        return 'Sin Acciones';
      }
    }
    else{
      $item = App\Poa::Where('poa_id', $id)->
      Where('poa_type', $type)->
      Where('month', false)->
      Where('state', $state)->
      Where('in','<=',$month)->
      Where('out','>=',$month)->
      first();
      if (isset($item)) {
        for ($i = 1; $i <= $month; $i++)
          $total += $item['m' . $i];
      }
    }
    return number_format((float)$total, 2, '.', '');

  }else{
    ///aqui hacemos el grafico de avance progresivo

    $total = 0;
    $type = strtoupper($type);

    if($state)
      $state = true;
    else
      $state = false;

    switch ($type){
      case ('TASK'):
        $type = 'App\Task';
        break;
      case ('OPERATION'):
        $type = 'App\Operation';
        break;
      case ('ACTION'):
        $type = 'App\Action';
        break;
    }

    if($type == 'DEPARTMENT'){
      if(activeyear()<=2019){
        $action = new \App\Action;
        $actions = $action->
        Where('department_id',$id)->
        Where('year',activeyear())->
        Where('status',true)->
        get();
        if(isset($actions)){

          foreach ($actions as $item)
            $total += accum($item->id,'Action',$state,$month, $rep);

          $total /= sizeof($actions);
        }
      }else{
        
        $yer = (string)activeyear();
        $ids_operations = \App\Operation::where('created_at','>',$yer)->pluck('id')->toArray();
        $definitions = \App\Definition::where('definition_type','App\operation')->
                                        wherein('definition_id',$ids_operations)->
                                        where('department_id',$id)->
                                        pluck('definition_id')->
                                        toArray();
                          
                                        ///generamos los IDs de las operaicones relacionadas al Departamento
        $operations = \App\Operation::wherein('id',$definitions)->get();
        if(isset($operations)){
          foreach ($operations as $item ) {
            $total += (accum($item->id,'Operation',$state,$month, $rep)*$item->definitions->last()->dep_ponderation)/100;
          }
        }
      }
    }
    elseif($type == 'TOTAL'){
      $action = new \App\Action;
      $actions =  $action->
                  Where('year',activeyear())->
                  Where('status',true)->
                  get();
      if(isset($actions)){
        $goals = $action->
        Select('goal_id')->
        Where('year',activeyear())->
        GroupBy('goal_id')->
        get();
        $goals = count($goals);
        foreach ($actions as $item)
          $total += accum($item->id,'Action',$state,$month, $rep)*0.01*$item->definitions()->first()->ponderation;
        $total /= $goals;
      }
      // la condicional para que el grafico realize el switcheo es aqui
    }
    else{
      $item = App\Poa::Where('poa_id', $id)->
      Where('poa_type', $type)->
      Where('month', false)->
      Where('state', $state)->
      first();
      if (isset($item)) {
        for ($i = 1; $i <= $month; $i++)
          $total += $item['m' . $i];
      }
    }

    return number_format((float)$total, 2, '.', '');
  }
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN EXECS
| Devuelve el valor tipo float, del total ejecutado de la programación o
| ejecución de una tarea, operación, acción o departamento hasta
| el mes de consulta.
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| execs(ID, TIPO, MES)
|
| $type = Task; TAREAS
| $type = Operation; OPERACIONES
| $type = Action; ACCIONES
| $type = Department; DEPARTAMENTOS
| $type = Total; TOTAL DE LAS ACCIONES
| $id = ID; Tarea, Operación, Acción o Departamento
| $month = Número de mes. Ej 1 - Enero
|--------------------------------------------------------------------------
*** */
///id = 4
function execs($id, $type, $month)
{

  $total = 0;
  $type = strtoupper($type);

  switch ($type){
    case ('TASK'):
      $type = 'App\Task';
      $item = App\Poa::Where('poa_id', $id)->
      Where('poa_type', $type)->
      Where('month', false)->
      Where('state', true)->
      first();
      if (isset($item))
          $total = $item['m' . $month];
      break;
      //
    case ('OPERATION'):

      ////como sabemos que es del tipo operación ejecutamos una operación
      /**
       * para que funcione necesitamos la sumatoria de la programacion
       * sumatoria de lo acumulado planificado en tareas
       */
      $m = $month;///alamcenamos el mes nuerico q operar en una variable m
      $month = 'm' . $month; ///creamos el apuntador del mes en el ejemplo sera 'm1'
      $accump = [];////inicialisamo sel acumulado planificado de la operacion
      $accume = [];///el acumulado ejecutado
      $accumt = [];///el acumulado total

      $item = App\Poa::Where('poa_id', $id)->///saca la ejecucion de la operacion
              Where('poa_type', 'App\Operation')->
              Where('month', false)->
              Where('state', true)->
              orderby('id','desc')->
              first();
              ///el ejecutado reprogramado *****

      ////aqui sacamos los items poas de las operaciones
      ///tenemos que preguntar si en la operacion estamos apuntado a lo replanificado o no

      if (isset($item)) {
        $operation = \App\Operation::find($id);
        $current = $operation->action()->pluck('current')->first();
        $repr = null;

        $prog = App\Poa::Where('poa_id', $id)->
                Where('poa_type', 'App\Operation')->
                Where('month', false)->
                ///aqui es la clave----------------------------------------------------------------------
                Where('state', false)->
                first();///elegimos el ultimo registro con las características
                ///aqui arrojo la matriz de reprogramados
        while(is_null($repr)){
          $repr = App\Poa::Where('poa_id', $id)-> ///aqui deberiamos sacar la ultima reprogramación
                  Where('poa_type', 'App\Operation')->
                  Where('month', false)->
                  Where('state', false)->
                  where('value',$current)->
                  first();
          $current--;
          if($current < 1)
            break 1;
        }
        ///ahora preguntamos si tenemos alguna reprogramacion que asignar
        /// generamos reprog current

        $progC = null;

        //$beeg = date('Y').'-01-01';
        //$lass = date('Y').'-12-31';
        $beeg = activeyear().'-01-01';
        $lass = activeyear().'-12-31';
        $progC = \App\Poa::Where('poa_id',$id)->
                          Where('poa_type','App\Operation')->
                          Where('month',false)->
                          Where('state',false)->
                          WhereBetween('created_at',[$beeg,$lass])->
                          Where('in','<=',$m)->
                          Where('out','>=',$m)->
                          first()->
                          toarray();

        /**
        * NOTA DE EJECUCION-... SI LA TAREA YA HA SIDO EJECUTADA CON DEFICICENCIA EN ALGUN MES SE TIENE Q COMPENSAR ESA FALTA DE CUMPLIMEINTO
        * EL ALGORITMO DE LA REMPROGAMCION CUENTA QUE EN TUS TREAS LLEGUES AL 100 POR CIEN SIN IMPORTAR EL ECENARIO PARA COMPROBAR LA GESTION PRO RESULTADOS
        */
        $ids = App\Task::Select('id')->
                Where('operation_id', $id)->
                Where('status',true)->
                pluck('id')->
                toarray();

        $ids_ordered = implode(',', $ids);

        $accump[0] = 0;
        $accume[0] = 0;
        $accumt[0] = 0;


        for ($i = 1; $i <= $m; $i++) {
          $j = $i - 1;
          $month = 'm' . $i;
          $accump[$i] = $progC['m' . $i];
          $accumt[$i] = App\Poa::Select($month)->
          Wherein('poa_id', $ids)->
          orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->
          Where('poa_type', 'App\Task')->
          Where('state', false)->
          Where('month', false)->
          pluck($month)->
          sum();
          $accume[$i] = App\Poa::Select($month)->
          Wherein('poa_id', $ids)->
          Where('poa_type', 'App\Task')->
          Where('state', true)->
          Where('month', $i)->
          pluck($month)->
          sum();
          $accump[$i] += $accump[$j];
          $accume[$i] += $accume[$j];
          $accumt[$i] += $accumt[$j];
        }

        //dd($accump);
        //return false;
        echo 'acum p = '.$accump[$m].' | acum e = '.$accume[$m].' | acum t = '.$accumt[$m]."\n";
        if($accumt[$m]==0)
          $total =0;
        else
          $total = $accume[$m] * $accump[$m] / $accumt[$m];
          $total = round($total,2);
         echo 'total: '.$total."\n";
        if ($m > 1) {
          $aux = 0;
          ///tenemos que actualizar el ejecutado antiguo antes de ejecutar lo nuevo
          ///como?
          for ($i = 1; $i < $m; $i++){
            $aux += $item['m' . $i];
            echo $item['m' . $i]."\n";
          }

          echo 'aux: '.$aux."\n";
          $total -= $aux;

        }
      }
      //----------------------------------------------------------------------------------------------------------------------------------------------------
      break;

    case ('ACTION'):
      $type = 'App\Action';
      $m = $month;
      $month = 'm' . $month;

      $ids = App\Operation::Where('action_id', $id)->
      pluck('id')->
      ToArray();

      ///obtenemos los ids de la operaciones
      //y una a una generamos el ejecutado del mes
      $poas = array();

      for($h = 0;$h<count ($ids); $h++){
        $poa =  App\Poa::select($month)->
                Where('poa_type','App\Operation')->
                Where('poa_id',$ids[$h])->
                Where('month', false)->
                Where('state', true)->
                orderby('id','desc')->
                pluck($month)->
                first();
        $poas[$h] = $poa;
      }
  ////aqui es donde tengo q modificar la ejecucion de la accion logrado
      $ponderations = array();
      for($h = 0;$h<count ($ids); $h++){
        $ponderation = App\Definition::Select('ponderation')->
                      Where('definition_type', 'App\Operation')->
                      Where('definition_id', $ids[$h])->
                      orderby('id','desc')->
                      pluck('ponderation')->
                      first();
        $ponderations[$h] = $ponderation;
      }

      for ($i = 0; $i < sizeof($poas); $i++)
        $total += $poas[$i] * $ponderations[$i] / 100;
      break;

      //--------------------------------------------------------------------
    case ('DEPARTMENT'):
      $action = new \App\Action;
      $m = $month;
      $month = 'm' . $month;

      $actions = $action->
      Where('department_id',$id)->
      Where('year',activeyear())->
      Where('status',true)->
      get();
      if(isset($actions)){
        foreach ($actions as $item)
          $total += (float) execs($item->id,'Action',$m);

        $total /= sizeof($actions);
      }
      break;
    case('TOTAL'):
      $action = new \App\Action;
      $m = $month;
      $month = 'm' . $month;

      $actions = $action->
      Where('year',activeyear())->
      Where('status',true)->
      get();

      if(isset($actions)){
        $goals = $action->Select('goal_id')->
        GroupBy('goal_id')->
        count();

        foreach ($actions as $item)
          $total += execs($item->id,'Action',$m)*0.01*
            $item->definitions()->
            first()->ponderation;
        $total /= $goals;
      }
      break;
  }

  return number_format((float)$total, 2, '.', '');
}

/**
 * Refrescar Poa
 * =========================================================================================
 * Para realizar el guardado de variables nuevas dentro de la ejecucion del poa
 *
 *
 */

function refresh_last_poa_op($id){
  ///primero obtenemos el ultimo poa creado con ejecucion
  ///y el ultimo poa creado con ejecucion
  $mon = activeReprogMonth();
  $accump = [];////inicialisamo sel acumulado planificado de la operacion
  $accume = [];///el acumulado ejecutado
  $accumt = [];///el acumulado total
  $accumx = [];///el acumulado no prosesado auxiliar
  $res = [];
  $ids= App\Task::Select('id')->
                  Where('operation_id', $id)->
                  Where('status',true)->
                  pluck('id')->
                  toarray();
  $idsc = count($ids);
                if($idsc == 0){
                  for($y = 1; $y < $mon; $y++){
                    $res['m'.$y] = 0;
                  }
                  $eje = App\Poa::Where('poa_type','App\Operation')->
                                  Where('poa_id',$id)->
                                  Where('month', false)->
                                  Where('state', true)->
                                  orderby('id','desc')->
                                  first();
                  $r = $eje->update($res);
                  if($r){
                    return $res;
                  }
                  return false;
                }
  $ids_ordered = implode(',', $ids);

  $item= App\Poa::Where('poa_type','App\Operation')->
                  Where('poa_id',$id)->
                  Where('month', false)->
                  Where('state', false)->
                  orderby('id','desc')->
                  first()->
                  toarray();
  ///sacamos el planificado del primer mes o del mes que toque
  $accump[0] = 0;
  $accume[0] = 0;
  $accumt[0] = 0;
  $accumx[0] = 0;
  for($i = 1 ; $i < $mon ; $i++){
      $j = $i - 1;
      $month = 'm' . $i;
      $accump[$i]= $item['m' . $i];
      $accumt[$i]= App\Poa::Select($month)->
                            Wherein('poa_id', $ids)->
                            orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->
                            Where('poa_type', 'App\Task')->
                            Where('state', false)->
                            Where('month', false)->
                            pluck($month)->
                            sum();
      $accume[$i]= App\Poa::Select($month)->
                            Wherein('poa_id', $ids)->
                            Where('poa_type', 'App\Task')->
                            Where('state', true)->
                            Where('month', $i)->
                            pluck($month)->
                            sum();
      $accump[$i] += $accump[$j];
      $accume[$i] += $accume[$j];
      $accumt[$i] += $accumt[$j];
      if($accumt[$i]!=0){
        $accumx[$i] = ($accume[$i]*$accump[$i])/$accumt[$i];

      }else{
        $accumx[$i] = 0;
      }

      echo '$i = '.$i.' accume = '.$accume[$i].' accump ='.$accump[$i].' accumt = '.$accumt[$i]."\n";
      if($i>1){
        ///agregamos la resta
        $res['m'.$i] = round($accumx[$i]-$accumx[$i-1],2);
      }else{
        ///aqui solo iniciamos con el m1
        $res['m'.$i] = $accumx[$i];
      }
  }

  $eje = App\Poa::Where('poa_type','App\Operation')->
                  Where('poa_id',$id)->
                  Where('month', false)->
                  Where('state', true)->
                  orderby('id','desc')->
                  first();
  $r = $eje->update($res);
  if($r){
    return $res;
  }
  return false;
}
/* ***
|--------------------------------------------------------------------------
| FUNCIÓN ACTIVEYEAR
| Devuelve el valor del año de operación
| Ej. 2019
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| activeyear()
|
|--------------------------------------------------------------------------
*** */

function activeyear()
{
  $year = new \App\Year;

  return $year->Select('name')->
  Where('current', true)->
  pluck('name')->
  first();
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN ACTIVEMONTH
| Devuelve el valor del mes del año en operación
| Ej. 1 [Corresponde al mes de enero]
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| activemonth()
|
|--------------------------------------------------------------------------
|Se arreglo el bug con respecto a los meses
*** */


function activemonth()
{
  $limit = App\Limit::Where('status', true)->first();
  if(!is_null($limit)){
    return (intval($limit->month));
  }else{
    return (intval(date('m')));
  }
/*
  $limit = App\Limit::Where('status', true)->first();

  if (strtotime(date('Y-m-d')) > strtotime($limit->date))
    $current = (intval(date('m'))) % 12;
  else
    $current = (intval($limit->month)) % 12;

  if ($current == 1)
    $previous = 12;
  else
    $previous = $current - 1;

  $date = App\Limit::Join('years', 'year_id', '=', 'years.id')->
  Select('limits.date')->
  Where('years.current', true)->
  Where('limits.status', true)->
  Where('limits.month', $previous)->
  first();

  if (!isset($date)) {
    return $current;
  }
  else {
    if (strtotime(date('Y-m-d')) < strtotime($date))
      return $previous;
    else
      return $current;
  }*/
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN DAYSTOLIMIT
| Devuelve la cantidad de días (int) faltantes para el cierre del mes
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| daystolimit()
|
|--------------------------------------------------------------------------
*** */

function daystolimit()
{
  $current = activemonth();

  $date = new \App\Limit;

  $date = $date->Join('years', 'year_id', '=', 'years.id')->
  Select('limits.date as limit', 'limits.month as month', 'years.name as year')->
  Where('years.current', true)->
  Where('limits.status', true)->
  first();

  if (!isset($date))
    $date = date('t');
  else {
    if (date('m') > $date->month) {
      $date = date('j', strtotime($date->limit));
    } else {
      $date = date('t',
          strtotime($date->year . '-' . $date->month)) + date('j', strtotime($date->limit));
    }
  }

  $diff = $date - date('j') + 1;

  if ($diff <= 60 && $diff > 10)
    $color = 'green';

  if ($diff <= 10 && $diff > 3)
    $color = 'orange';

  if ($diff <= 3)
    $color = 'red';

  if ($diff == 1)
    $dias = 'día';
  else
    $dias = 'días';

  return '<font color="' . $color . '">' . $diff . ' ' . $dias . '</font>';
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN TITLE
| Devuelve el nombre estandarizado para el título de una pagina.
| Se usa en BLADE
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| title()
|
|--------------------------------------------------------------------------
*** */

function title(){
  $parts = explode (".",Request::route()->getName());
  $title = strtoupper(end($parts));
  switch ($title){
    case "INDEX":
      $title = "Índice";
      break;
    case "SHOW":
      $title = "Item";
      break;
    case "EDIT":
      $title = "Editar";
      break;
    case "CREATE":
      $title = "Registrar";
      break;
    default:
      $title = "OFEP";
      break;
  }
  return $title;
}


/* ***
|--------------------------------------------------------------------------
| FUNCIÓN GRAPHADVANCE
| Devuelve la cadena gráfica comparativa entre lo programado y lo ejecutado
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| graphadvance($name, $prog,$exec, $width, $height)
|
| $name = chart; NOMBRE DE LA INSTANCIA
| $prog = float; PROGRAMACIÓN
| $exec = float; EJECUCIÓN
| $width = int; ANCHO DE LA IMAGEN
| $height = int; ALTO DE LA IMAGEN
|
| Dimensión sugerida 'width' => 450 y 'height' => 135
|
|--------------------------------------------------------------------------
*** */

function graphadvance($name, $prog, $exec, $width, $height)
{
  $chart = \Chart::title(['text' => 'Programado / Ejecutado',
    'style' => ['color' => '#000000',
            'fontWeight' => 'bold',
            'font-size' => '1.2em',],
    ])

    ->chart([
      'type' => 'bar',
      'renderTo' => $name,
      'width' => $width,
      'height' => $height,
      'marginLeft' => 35,
    ])
    ->subtitle([
      'text' => null,
    ])
    ->colors([
      '#0c2959', '#992959'
    ])
    ->xaxis([
      'categories' => [
        'P',
        'E',
      ],
      'labels' => [
        'align' => 'top',
        'formatter' => 'startJs:function(){return this.value}:endJs',
      ],
    ])
    ->yaxis([
      'title' => ['enabled' => false,],
    ])
    ->legend([
      'enabled' => false,
    ])
    ->plotOptions([
      'series' => [
        'borderRadius' => 5,
        'dataLabels' => [
          'enabled' => true,
          'format' => '{point.y:.1f}%',
        ],
        'shadow' => true,
        'showInLegend' => false,
      ],
      'column' => ['pointPadding' => 0.2, 'borderWidth' => 0],
      'enableMouseTracking' => false,
    ])
    ->exporting([
      'enabled' => false,
    ])
    ->tooltip([
      ['enabled' => false,],
    ])
    ->credits([
      ['enabled' => false,],
    ])
    ->series(
      [
        [
          'data' => [
            [
              'name' => 'Programado',
              'y' => (float) $prog,
              'color' => '#343a40'
            ],
            [
              'name' => 'Ejecutado',
              'y' => (float) $exec,
              'color' => '#DA2031',
            ],
          ],
        ],
      ]
    )
    ->display();
  return $chart;
}


/* ***
|--------------------------------------------------------------------------
| FUNCIÓN GRAPHLINE
| Devuelve la cadena gráfica tipo LINE de dos cadenas de datos
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| graphadvance($name, $value1,$value2, $width, $height)
|
| $name = chart; NOMBRE DE LA INSTANCIA
| $value1 = float array; PROGRAMACIÓN
| $value2 = float array; EJECUCIÓN
| $width = int; ANCHO DE LA IMAGEN
| $height = int; ALTO DE LA IMAGEN
|
| Dimensión sugerida 'width' => 450 y 'height' => 135
|
|--------------------------------------------------------------------------
*** */

function graphline($name, $value1, $value2, $width, $height){
  $chart = \Chart::title(['text' => 'Porcentual (%)',])
    ->chart([
      'type' => 'line',
      'renderTo' => $name,
      'width' => $width,
      'height' => $height,
      'marginLeft' => 35,
    ])
    ->subtitle([
      'text' => ' ',
    ])
    ->colors([
      '#0c2959', '#992959'
    ])
    ->xaxis([
      'categories' => [
        'Ene',
        'Feb',
        'Mar',
        'Abr',
        'May',
        'Jun',
        'Jul',
        'Ago',
        'Sep',
        'Oct',
        'Nov',
        'Dic',
      ],
      'labels' => [
        'formatter' => 'startJs:function(){return this.value}:endJs',
      ],
    ])
    ->yaxis([
      'lineColor' => '#FF0000',
      'lineWidth' => 1,
      'max' => 100.00,
      'title' => ['text' => null,],
    ])
    ->legend([
      'itemDistance' => 50,
    ])
    ->exporting([
      'buttons' => ([
        'contextButton' => ([
          'menuItems' =>  ['downloadJPEG', 'downloadPDF'],
        ])
      ])
    ])
    ->tooltip([])
    ->credits([
      ['enabled' => false,],
    ])
    ->plotOptions([
      'line' => [
        'dataLabels' => ['enabled' => true,],
      ],
    ])
    ->series(
      [
        [
          'name' => 'Programado',
          'data' => $value1,
          'color' => '#343a40',
          'label' => ['enabled' => false,],
          'enableMouseTracking' => true,
        ],
        [
          'name' => 'Ejecutado',
          'data' => $value2,
          'color' => '#DA2031',
          'label' => ['enabled' => false,],
          'enableMouseTracking' => true,
        ],
      ]
    )
    ->display();

  return $chart;
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN GRAPHCOLUMN
| Devuelve la cadena gráfica tipo COLUMN de dos datos
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| graphadvance($name, $value1,$value2, $width, $height)
|
| $name = chart; NOMBRE DE LA INSTANCIA
| $datap = float;  PROGRAMACIÓN
| $datae = float; EJECUCIÓN
| $width = int; ANCHO DE LA IMAGEN
| $height = int; ALTO DE LA IMAGEN
|
| Dimensión sugerida 'width' => 450 y 'height' => 135
|+0
{

  z
}
|--------------------------------------------------------------------------
*** */

function graphcolumn($name, $datap, $datae, $width, $height){

  $chart = \Chart::title(['text' => 'Porcentual (%)',])
    ->chart([
      'type' => 'column',
      'renderTo' => $name,
      'width' => $width,
      'height' => $height,
      'marginLeft' => 35,
    ])
    ->subtitle([
      'text' => ' ',
    ])
    ->colors([
      '#0c2959', '#992959'
    ])
    ->xaxis([
      'categories' => [
        'Programado',
        'Ejecutado',
      ],
      'labels' => [
        'align' => 'top',
        'formatter' => 'startJs:function(){return this.value}:endJs',
      ],
    ])
    ->yaxis([
      'title' => ['text' => null,],
    ])
    ->legend([
      'itemDistance' => 40,
      'enabled' => false,
    ])
    ->exporting([
      'buttons' => ([
        'contextButton' => ([
          'menuItems' =>  ['downloadJPEG', 'downloadPDF'],
        ])
      ])
    ])
    ->tooltip([
      ['enabled' => false,],
    ])
    ->credits([
      ['enabled' => false,],
    ])
    ->plotOptions([
      'series' => [
        'borderRadius' => 5,
        'dataLabels' => [
          'enabled' => true,
          'format' => '{point.y:.1f}%',
        ],
        'shadow' => true,
        'showInLegend' => false,
      ],
      'column' => ['pointPadding' => 0.2, 'borderWidth' => 0],
      'enableMouseTracking' => true,
    ])
    ->series(
      [
        [
          'type' => 'column',
          'data' => [
            [
              'name' => 'Programado',
              'y' => (float) $datap,
              'color' => '#343a40'
            ],
            [
              'name' => 'Ejecutado',
              'y' => (float) $datae,
              'color' => '#DA2031',
            ],
          ],
        ],

      ])
    ->display();
  return $chart;
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN CHARTACCUM
| Devuelve la cadena gráfica
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| chartaccum($id, $type, $graph, $m, $name)
|
| $id = int; IDENTIFICADOR DE LA ACCIÓN, OPERACIÓN O TAREA
| $type = char;  TIPO DE Instancia: Total - OFEP
|                                   Department - DEPARTAMENTO
|                                   Action - ACCIÓN
|                                   Operation - OPERACIÓN
|                                   Task - TAREA
| $graph = char;  TIPO DE GRÁFICO: Accum - Acumulado año
|                                 Month - Mes
|                                 AccumMonth - Acumulado al mes
| $m = int; MES DE CONSULTA
| $name = char; NOMBRE DEL ELEMENTO
|
| Dimensión de la imagen empleada 'width' => 450 y 'height' => 350
|
|--------------------------------------------------------------------------
*** */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//$chartaccuma_ofep = chartaccum(0,'Total','Accum', 6,'charta');

function chartaccum($id, $type, $graph, $m, $name)
{
  $chart = '';
  $type = strtoupper($type);
  $graph = strtoupper($graph);
  switch ($graph) {
    case 'ACCUM':
////aqui es el cambio del chart
      $ban = true;
      $refo = \App\Reformulation::where('year',activeyear())->pluck('month')->toarray();
      for ($i=0; $i < count($refo); $i++) {
        if($m >= $refo[$i]){
          $ban = false;
        }
      }
      for ($i = 1; $i <= 12; $i++) {
        $datap[$i] = number_format((float)accum($id, $type, false, $i,$ban), 2, '.', '');

        if($i > $m)
          $datae[$i] = 0;
        else
          $datae[$i] = number_format((float)accum($id, $type, true, $i,$ban), 2, '.', '');
      }

      $data = implode(',', $datap);

      $data = explode(',', $data);

      foreach ($data as $d)
        $value1[] = (float)$d;

      $data = implode(',', $datae);
      $data = explode(',', $data);

      foreach ($data as $d)
        $value2[] = (float)$d;

         if(!$ban){
           for ($i=1; $i < activeReprogMonth()-1; $i++) {
             $value1[$i] = $value2 [$i];
           }
         }

      $chart = graphline($name, $value1, $value2, 450, 350);

      break;
//fin del Chart
    case 'MONTH':
      $month = 'm' . $m;
      $refo = \App\Reformulation::where('year',activeyear())->pluck('month')->toarray();
      if(count($refo)){
        if($m > 1){
          if(activeReprogMonth()==$m){
            $datap =  number_format((float)accum($id, $type, false, $m), 2, '.', '') - number_format((float)accum($id, $type, true, $m -1), 2, '.', '');
  
            $datae =  number_format((float)accum($id, $type, true, $m), 2, '.', '') - number_format((float)accum($id, $type, true, $m-1), 2, '.', '') ;
          }else{
            $datap =  number_format((float)accum($id, $type, false, $m), 2, '.', '') - number_format((float)accum($id, $type, false, $m -1), 2, '.', '');
  
            $datae =  number_format((float)accum($id, $type, true, $m), 2, '.', '') - number_format((float)accum($id, $type, true, $m-1), 2, '.', '') ;
          }
        }
        else {
          $datap =  number_format((float)accum($id, $type, false, $m), 2, '.', '');
          $datae =  number_format((float)accum($id, $type, true, $m), 2, '.', '');
        }
      }else{
        if($m > 1){
          if(activeMonth()==$m){
            $datap =  number_format((float)accum($id, $type, false, $m), 2, '.', '') - number_format((float)accum($id, $type, true, $m -1), 2, '.', '');
  
            $datae =  number_format((float)accum($id, $type, true, $m), 2, '.', '') - number_format((float)accum($id, $type, true, $m-1), 2, '.', '') ;
          }else{
            $datap =  number_format((float)accum($id, $type, false, $m), 2, '.', '') - number_format((float)accum($id, $type, false, $m -1), 2, '.', '');
  
            $datae =  number_format((float)accum($id, $type, true, $m), 2, '.', '') - number_format((float)accum($id, $type, true, $m-1), 2, '.', '') ;
          }
        }
        else {
          $datap =  number_format((float)accum($id, $type, false, $m), 2, '.', '');
          $datae =  number_format((float)accum($id, $type, true, $m), 2, '.', '');
        }
      }
      $chart = graphcolumn($name, $datap, $datae, 450, 350);
      break;

    case 'ACCUMMONTH':
      $month = 'm' . $m;

      $datap = number_format((float)accum($id, $type, false, 12), 2, '.', '');
      $datae = number_format((float)accum($id, $type, true, $m), 2, '.', '');

      $chart = graphcolumn($name, $datap, $datae, 450, 350);

      break;
  }
  return $chart;
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN SEARCH_TASK
| Devuelve una cadena con los ID de las tareas buscadas
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| search_task($keyword)
|
| $keyword = char; PALABRA DE BÚSQUEDA
|
| Realiza la búsqueda en la definición de tarea y operación,
| como también en la lista de usuarios responsables
|--------------------------------------------------------------------------
*** */

function search_task($keyword){

  if(strpos($keyword, '.')){
    $codes = explode(".", $keyword);
      if(count($codes) == 4){
        $ids = \App\Task::Join('operations','operation_id','operations.id')->
          Join('actions','action_id','actions.id')->
          Join('goals','goal_id','goals.id')->
          Select('tasks.id')->
          Where('tasks.code',$codes[3])->
          Where('operations.code',$codes[2])->
          Where('actions.code',$codes[1])->
          Where('goals.code',$codes[0])->
          pluck('tasks.id')->
          all();
      }
      else
        $ids = [];
  }
  else{
    $ids1 = \App\Definition::Select('definition_id')
      ->Where('description', 'LIKE', "%$keyword%")
      ->Where('definition_type', "App\Task")
      ->pluck('definition_id')
      ->all();
    $ido = \App\Definition::Select('definition_id')
      ->Where('description', 'LIKE', "%$keyword%")
      ->Where('definition_type', "App\Operation")
      ->pluck('definition_id')
      ->all();
    if(isset($ido)){
      $ids2 = \App\Task::Select('id')
        ->Wherein('operation_id', $ido)
        ->pluck('id')
        ->all();
      $ids = array_unique(array_merge($ids1,$ids2));
    }
    else
      $ids = $ids1;

    $idus = \App\User::Where('name', 'LIKE', "%$keyword%")
      ->get();
    if(isset($idus)){
      $id_aux = [];
      foreach ($idus as $item)
        $id_aux = array_merge($item->tasks()->pluck('task_id')->toArray(),$id_aux);
      $id_aux = array_unique($id_aux);
      if(isset($id_aux))
        $ids = array_unique(array_merge($ids,$id_aux));
    }
  }

  return $ids;
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN SEARCH_OPERATION
| Devuelve una cadena con los ID de las operaciones buscadas
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| search_operation($keyword)
|
| $keyword = char; PALABRA DE BÚSQUEDA
|
| Realiza la búsqueda en la definición de operación y acción
|--------------------------------------------------------------------------
*** */

function search_operation($keyword){

  if(strpos($keyword, '.')){
    $codes = explode(".", $keyword);
    if(count($codes) == 3){
      $ids = \App\Operation::Join('actions','action_id','actions.id')->
      Join('goals','goal_id','goals.id')->
      Select('operations.id')->
      Where('operations.code',$codes[2])->
      Where('actions.code',$codes[1])->
      Where('goals.code',$codes[0])->
      pluck('operations.id')->
      all();
    }
    else
      $ids = [];
  }
  else{
    $ids = \App\Definition::Select('definition_id')
      ->Where('description', 'LIKE', "%$keyword%")
      ->Where('definition_type', 'App\Operation')
      ->pluck('definition_id')
      ->all();
    $ida = \App\Definition::Select('definition_id')
      ->Where('description', 'LIKE', "%$keyword%")
      ->Where('definition_type', "App\Action")
      ->pluck('definition_id')
      ->all();
    if(isset($ida)){
      $ids2 = \App\Operation::Select('id')
        ->Wherein('action_id', $ida)
        ->pluck('id')
        ->all();
      $ids = array_unique(array_merge($ids,$ids2));
    }
  }

  return $ids;
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN SEARCH_ACTION
| Devuelve una cadena con los ID de las acciones buscadas
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| search_action($keyword)
|
| $keyword = char; PALABRA DE BÚSQUEDA
|
| Realiza la búsqueda en la definición de operación y acción
|--------------------------------------------------------------------------
*** */

function search_action($keyword){

  $ids = [];

  if(strpos($keyword, '.')){
    $codes = explode(".", $keyword);
    if(count($codes) == 2) {
      $ids = \App\Action::Join('goals', 'goal_id', 'goals.id')->
      Select('actions.id')->
      Where('actions.code', $codes[1])->
      Where('goals.code', $codes[0])->
      pluck('actions.id')->
      all();
    }
  }
  else{
    $ids = \App\Definition::Select('definition_id')
      ->Where('description', 'LIKE', "%$keyword%")
      ->Where('definition_type', 'App\Action')
      ->pluck('definition_id')
      ->all();
    $idg = \App\Goal::Select('id')
      ->Where('description', 'LIKE', "%$keyword%")
      ->pluck('id')
      ->all();
    if(isset($idg)){
      $ids2 = \App\Action::Select('id')
        ->Wherein('goal_id', $idg)
        ->pluck('id')
        ->all();
      $ids = array_unique(array_merge($ids,$ids2));
    }
  }
  return $ids;
}

function operation_execution($id, $m)
{
  $month = 'm' . $m;
  $total = 0;
  $accump = [];
  $accume = [];
  $accumt = [];

  $item = App\Poa::Where('poa_id', $id)->
  Where('poa_type', 'App\Operation')->
  Where('month', false)->
  Where('state', true)->
  first();

  if (isset($item)) {
    $prog = App\Poa::Where('poa_id', $id)->
    Where('poa_type', 'App\Operation')->
    Where('month', false)->
    Where('state', false)->
    first();
    $ids = App\Task::Select('id')->
    Where('operation_id', $id)->
    pluck('id')->
    toarray();
    $accump[0] = 0;
    $accume[0] = 0;
    $accumt[0] = 0;
    for ($i = 1; $i <= $m; $i++) {
      $j = $i - 1;
      $month = 'm' . $i;
      $accump[$i] = $prog['m' . $i];
      $accumt[$i] = App\Poa::Select($month)->
      Wherein('poa_id', $ids)->
      Where('poa_type', 'App\Task')->
      Where('state', false)->
      Where('month', false)->
      pluck($month)->
      sum();
      $accume[$i] = App\Poa::Select($month)->
      Wherein('poa_id', $ids)->
      Where('poa_type', 'App\Task')->
      Where('state', true)->
      Where('month', $i)->
      pluck($month)->
      sum();
      $accump[$i] += $accump[$j];
      $accume[$i] += $accume[$j];
      $accumt[$i] += $accumt[$j];
    }

    $total = number_format((float)$accume[$m] * $accump[$m] / $accumt[$m], 2, '.', '');

    if ($m > 1) {
      $aux = 0;

      for ($i = 1; $i < $m; $i++)
        $aux += $item['m' . $i];

      $total -= $aux;
    }
  }

  return $total;
}



/* ***
|--------------------------------------------------------------------------
| FUNCIÓN estimate
| Devuelve un valor de cálculo de presupuesto
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| estimate($id, $type, class)
|
| $id = int; ID de la Tarea, Operacción o Acción
| $type = char; ACCOUNT - CUENTA
|               TASK - Tarea
|               OPERATION - Operation
|               ACTION - Acción
| $class = bool; 0 - Programado
|                1 - Ejecutado
|
| Realiza la búsqueda en la definición de operación y acción
|--------------------------------------------------------------------------
*** */

function estimate($id,$type,$class){
  $total = 0;
  $type = strtoupper($type);

  switch ($type) {
    case ('ACCOUNT'):
      $total = \App\Structure::Where('id',$id);
      if($class)
        $total = (float) $total->first()->inversion;
      else
        $total = (float) $total->first()->current;
      break;
    case ('TASK'):
      $type = 'App\Task';
      $ids = \App\Task::FindorFail($id)->
      structures()->
      pluck('id');
      if($ids->count() > 0){
        foreach ($ids->toArray() as $idt){
          $total += (float) estimate($idt,'ACCOUNT',$class);
        }
      }
      break;
    case ('OPERATION'):
      $type = 'App\Operation';
      $idt = \App\Task::Where('operation_id',$id)->
        pluck('id');
      if($idt->count() > 0){
        foreach ($idt->toArray() as $item){
         $total +=  (float) estimate($item,'TASK',$class);
        }
      }
      break;
    case ('ACTION'):
      $type = 'App\Action';
      $ido = \App\Operation::Where('action_id',$id)->
      pluck('id');
      if($ido->count() > 0){
        foreach ($ido->toArray() as $item){
          $total +=  (float) estimate($item,'OPERATION',$class);
        }
      }
      break;
  }

  $total = number_format((float)$total, 2, '.', '');

  return $total;
}
/* ***
|--------------------------------------------------------------------------
| FUNCIÓN certification
| Devuelve el valor de la certificación de una tarea
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| certification($id, $type)
|
| $id = int; ID de la Tarea, Operacción o Acción
| $type = char; ACCOUNT - CUENTA
|               TASK - Tarea
|               OPERATION - Operation
|               ACTION - Acción
|
|--------------------------------------------------------------------------
*** */

function certification($id, $type){
  $total = 0;
  $type = strtoupper($type);

  switch ($type) {
    case ('ACCOUNT'):
      $transaction = \App\Transaction::Where('structure_id',$id)->
      Where('year',activeyear());
      $total = $transaction->sum('middle');
      break;
    case ('TASK'):
      $type = 'App\Task';
      $ids = \App\Task::FindorFail($id)->
      structures()->
      pluck('id');
      if($ids->count() > 0){
        foreach ($ids->toArray() as $idt){
          $total += (float) certification($idt,'ACCOUNT');
        }
      }
      break;
    case ('OPERATION'):
      $idt = \App\Task::Where('operation_id',$id)->
      pluck('id');
      if($idt->count() > 0){
        foreach ($idt->toArray() as $item){
          $total +=  (float) certification($item,'TASK');
        }
      }
      break;
    case ('ACTION'):
      $ido = \App\Operation::Where('action_id',$id)->
      pluck('id');
      if($ido->count() > 0){
        foreach ($ido->toArray() as $item){
          $total +=  (float) certification($item,'OPERATION');
        }
      }
      break;
  }

  $total = number_format((float)$total, 2, '.', '');

  return $total;
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN coder
| Devuelve el código de una tarea, operación, acción CP, acción MP
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| coder($id, $type)
|
| $id = int; ID de la Tarea, Operacción o Acción
| $type = char; TASK - Tarea
|               OPERATION - Operation
|               ACTION - Acción CP
|               GOAL - Acción MP
|--------------------------------------------------------------------------
*** */

function coder($id, $type){
  $type = strtoupper($type);

  switch ($type) {
    case ('TASK'):
      $task = \App\Task::FindorFail($id);
      $code = $task->operation->action->goal->code . '.' .
        $task->operation->action->code . '.' .
        $task->operation->code . '.' .
        $task->code;
      break;
    case ('OPERATION'):
      $operation = \App\Operation::FindorFail($id);
      $code = $operation->action->goal->code . '.' .
        $operation->action->code . '.' .
        $operation->code;
      break;
    case ('ACTION'):
      $action = \App\Action::FindorFail($id);
      $code = $action->goal->code . '.' .
        $action->code;
    case ('GOAL'):
      $goal = \App\Goal::FindorFail($id);
      $code = $goal->code;
      break;
  }
  return $code;
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN statuscheck()
| Devuelve el código tipo badge del estado
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| statuscheck($var, $yes, $no)
|
| $var = bool; Estado 1 o 0
| $yes = char; Mensaje por Si
| $no = char; Mensaje por No
|--------------------------------------------------------------------------
*** */

function statuscheck($var, $ap = 'A.', $pe = 'P.', $ej = 'E.'){
  switch ($var) {
    case '0':
      $msg = '<small class="badge badge-danger">'.$pe.'</small>';
      break;
    case '1':
      $msg = '<small class="badge badge-success">'.$ap.'</small>';
      break;
    case '2':
      $msg = '<small class="badge badge-info">'.$ej.'</small>';
      break;
    default:
      $msg = '<small class="badge badge-danger">Error</small>';
      break;
  }
  return $msg;
}

/* ***
|--------------------------------------------------------------------------
| FUNCIÓN reprogcheck()
| Devuelve el estado de la bandera de reprogramación
|--------------------------------------------------------------------------
|
| SINTAXIS
|
| reprogcheck()
|
|--------------------------------------------------------------------------
*** */
function reprogcheck()
{
  $reconf = \App\Configuration::Select('reconfigure')->
            Where('status', true)->
            pluck('reconfigure')->
            first();
  return $reconf;
}


function action_programmed($id, $m)
{
  $month = 'm' . $m;
  $total = 0;
  $ids = App\Operation::Where('action_id', $id)->
  pluck('id')->
  ToArray();
  $ids_ordered = implode(',', $ids);

  $poas = App\Poa::Select($month)->
  Where('poa_type', 'App\Operation')->
  Wherein('poa_id', $ids)->
  Where('month', false)->
  Where('state', false)->
  Where('value', false)->
  orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->
  pluck($month)->
  toarray();

  $ponderations = App\Definition::Select('ponderation')->
  Where('definition_type', 'App\Operation')->
  Wherein('definition_id', $ids)->
  orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->
  pluck('ponderation')->
  toarray();

  for ($i = 0; $i < sizeof($poas); $i++)
    $total += $poas[$i] * $ponderations[$i] / 100;

  return number_format((float)$total, 2, '.', '');
}

/** FUNCION para reformular los reprogramados en la accion por cada mes
 * ----------------------------------------------------------------
 * SINTAXIS
 * action_reprogrammed($id, $m);
 *
 * @param Numero $id Recibe un numero el cual es el ID de la operación
 * @param Numero $m Recibe un numero el cual reprecenta el mes en el cual se esta calculando la reprogramcion
 * @return Numero Retorna el numero que se calculo en la funcion correspondiente a el mes de esa accion con la de la operacion
 * _______________________________________________________________________
 *
 */

//cant_op_curr($action);

function action_reprogrammed($id, $m)
{

  //primero obtenemos el mes que desamos operar y en la accion q tenemos q trabajar
  $month = 'm' . $m;
  $total = 0;//inicialisamos el sumador

  $ids = App\Operation::Where('action_id', $id)->
  orderBy('id')->
  pluck('id')->
  ToArray();
  /// volvemos los ids de las operaciones en una cadena delimitada por comas
  /// $ids_ordered = implode(',', $ids);
  $states = array();
  for($j = 0 ; $j < sizeof($ids); $j++){
    //echo $ids[$j]."* \n";
    $operation = \App\Operation::find($ids[$j]);
    //consulta de cantidad e definitions en el poa
    $states[$j] = $operation->poas()->where('state',false)->orderby('id','desc')->pluck('value')->first();
  }

  /// ya tenenmos los poas que estan reprogramamdos
  /// y los current que corresponden
  /// lo q son 0 se quedan con un estado de no reprogramacion los q son mayores a cero son
  /// reprogramados .
  /// al ser reprogramados el valor alamcenado en el array define que es el current
  /// planification ejempl.
  /// (0,0,2,0,1,0)
  //  un ejemplo de la salida de este array nos informa de que hay 2 operaciones
  /// reprogramadas la tercera y la 5ta.
  /// todo esto con respecto al array de las operaciones principales

  $poas = array();
  /// ahora armamos el poas del mes selecionado
  /// como
  /// realizamos el for para el poas y generamos el array correspondiente
  for($h = 0 ; $h< sizeof($ids); $h++){
    /// la consulta seria la siguiente
    /// select * from poas where poa_id=4 and poa_type like '%operation%' and state = false and value =1;
    $poam = App\Poa::Select($month)->
            Where([['month', false],['state', false],['value', $states[$h]]])->
            Where('poa_type', 'App\Operation')->
            Where('poa_id', $ids[$h])->
            first()->$month;
    $poas[$h] = $poam;
  }

/* ///formula anterior
  $poas = App\Poa::Select($month)->
  ///luego preguntamos q
  ///*sea el mes cero de planificacion
  ///*que el estado sea cero que es el de planificaicon
  ///* que value sea falso por q es la programacion original
  Where([['month', false],['state', false],['value', false]])->
  //selecionamos q sean del tipo operacion
  Where('poa_type', 'App\Operation')->
  //y qu busque en los IDs generados anterior mente
  Where('poa_id', $ids)->
  ///lo ordenamos por el campo id en ese orden (4,5,6,7)
  orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->
  //y ordenamos tambien poa_id
  orderBy('poa_id')->
  ///y selecionamos el dato regisrado e ese mes
  pluck($month);

*/

  $ponderations = array();
  for($h = 0 ; $h< sizeof($ids); $h++){
    $ponderation =  App\Definition::Select('ponderation')->
                    Where('out', 12)->
  ///aun hay q ver por q hacemos la coparacin con el out,, ya q tenemos q igualar con la conderacion correspondiente
                    Where('definition_type', 'App\Operation')->
                    Where('definition_id', $ids[$h])->
                    first()->ponderation;
    $ponderations[$h] = $ponderation;
  }

  /*
  //ahora selecionamos la poderaciones de la operaciones en definition
  $ponderations = App\Definition::Select('ponderation')->
  ///selecionamos donde out sea mayor o igual a mes de busqueda
  Where('out', '>=' , $m)->
  ///selecionamos los q sean tipo operacion
  Where('definition_type', 'App\Operation')->
  ///buscamos en los ids de los poas
  Wherein('definition_id', $ids)->
  ///se lo ordena como el anterior caso
  orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->
  ///se ordena como el anterior caso x2
  orderBy('definition_id')->
  ///sacamos solo ponderacion
  pluck('ponderation')->
  toarray();
  ///pregunrta por q tenemos tantas ponderaciones
  ///dd($poas);
  ///aqui generamos el calculo de cada uno
*/

  ///primero calculamos el mes activo menos uno para la formula

  ///en el for desde cero hasta la cantidad de poas generados
  ///calculamos el mensual de cada poa

  for ($i = 0; $i < sizeof($states); $i++){
    if($states[$i]==0){
      $total += $poas[$i] * $ponderations[$i];
    }else{
      ///aqui decimos q el acum es del tipo reprogramcion
      $total += $poas[$i] * $ponderations[$i];
    }
  }

  $total = $total/100;

  return number_format((float)$total, 2, '.', '');
}


/**
 * Verifica si un elemento poa esta reprogramado
 * --------------------------------------------------------------------------
 * FUNCTION cheking_poa_rep($poa_id, $type);
 *
 * @param Numero $poa_id Recibe el ID del tipo numero para el poa_id de la tabla 'poas'
 *
 * @param Cadena $type Es el tipo de elemento que se va a prosesar puede ser ('action', 'operation', 'task')
 *
 * @return Boleano Devuelve verdadero si esta con almenos una reprogramacion y devuelve false si no esta con reprogramacion
 * para esta uncion la modificaremos para q indique que existe una reformulacion
 * --------------------------------------------------------------------------
 *
 * verifica si la operacion accion o tarea esta con reproramacion en base al mes activo de reprogramacion en el caso de la accion y al mes activo en el caso de operacion
 *
 * --------------------------------------------------------------------------
 */

function cheking_poa_rep($poa_id, $type,$month = 1){
  ///select * from poas where  state = 0 and poa_type like '%operation%' and poa_id = 4 and 'in' = 7;

  $type = strtoupper($type);
  switch ($type){
    case ('TASK'):
      $type = 'App\Task';
      $mc = activemonth();
      break;
    case ('OPERATION'):
      $type = 'App\Operation';
      $mc = activemonth();
      break;
    case ('ACTION'):
      $type = 'App\Action';
      $mc = activeReprogMonth();
      break;
  }
  ///preguntar si tenemaos mas de un poa establecido
  $poa =  \App\Poa::select('*')->
          Where('state', false)->
          Where('poa_type', $type)->
          Where('poa_id',$poa_id)->
          count();
  if($poa > 1){
    ///si es mas de uno retornamos true
    return true;
  }else{
    return false;
  }
}


/** Pregunta si una operacion o tarea esta activa o no
 * ---------------------------------------------------------------------
 * SINTAXIS
 * @param Numero $id es el identificador de la operacion o tarea
 *
 * @
 */
function cheking_poa_active($id, $type){
  $type = strtoupper($type);
  switch ($type){
    case ('TASK'):
      $task = \App\Task::find($id);
      $task->status;
      if($task->status){
        return true;
      }else{
        return false;
      }
      break;
    case ('OPERATION'):
      $op = \App\Operation::find($id);
      $pon = $op->definitions->pluck('ponderation')->last();
      if($pon > 0){
        return true;
      }else{
        return false;
      }
      break;
  }
}


/** Deuleve la cantida de reprogamacione de un poa programado
 * ------------------------------------
 *
 */

function quantity_poa_rep($poa_id, $type){
  $type = strtoupper($type);
  switch ($type){
    case ('TASK'):
      $type = 'App\Task';
      $mc = activemonth();
      break;
    case ('OPERATION'):
      $type = 'App\Operation';
      $mc = activemonth();
      break;
    case ('ACTION'):
      $type = 'App\Action';
      $mc = activeReprogMonth();
      break;
  }
  $poa =  \App\Poa::select('*')->
          Where('state', false)->
          Where('poa_type', $type)->
          Where('poa_id',$poa_id)->
          count();
  return $poa-1;
}


/**
 * -----------------------------------------------------------------
 * FUNCION para Ejecutar una Accion
 * -----------------------------------------------------------------
 * SINTAXIS
 * @param numero $id Es el ID de la operacion en la cual se desa trabajar
 * @param numero $m Es le mes de ejeccion que se desa calcular
 * @return numero retorna un numero correspondiente al calculo de la ejecucion de la accion en ese mes
 * ______________________________________________________________________
 */

function action_execution($id, $m)
{
  $month = 'm' . $m;
  $total = 0;

  $ids = App\Operation::Where('action_id', $id)->
  pluck('id')->
  ToArray();
  $ids_ordered = implode(',', $ids);

  $poas = App\Poa::Select($month)->
  Where('poa_type', 'App\Operation')->
  Wherein('poa_id', $ids)->
  Where('month', false)->
  Where('state', true)->
  orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->
  pluck($month)->
  toarray();

  $ponderations = App\Definition::Select('ponderation')->
  Where('definition_type', 'App\Operation')->
  Wherein('definition_id', $ids)->
  orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->
  pluck('ponderation')->
  toarray();

  for ($i = 0; $i < sizeof($poas); $i++)
    $total += $poas[$i] * $ponderations[$i] / 100;

  return number_format((float)$total, 2, '.', '');
}


/**
 *
 */

function progexec($ids, $m)
{
//  $taskprog = 0;
//  $taskexec = 0;
  $month = 'm' . $m;

  $taskprog = App\Poa::Select($month)->
  Wherein('poa_id', $ids)->
  Where('poa_type', 'App\Task')->
  Where('state', false)->
  Where('month', false)->
  pluck($month)->
  sum();

  $taskexec = App\Poa::Select($month)->
  Wherein('poa_id', $ids)->
  Where('poa_type', 'App\Task')->
  Where('state', true)->
  Where('month', $m)->
  pluck($month)->
  sum();

  $operationprog = App\Poa::Select($month)->
  Where('poa_id', '1')->
  Where('poa_type', 'App\Operation')->
  Where('state', false)->
  Where('month', false)->
  pluck($month)->
  first();


  /*  $monthprog = App\Poa::Select($month)
      ->Wherein('poa_id', $ids)
      ->Where('poa_type', 'App\Task')
      ->Where('state', false)
      ->pluck($month)
      ->toarray();

    foreach ($monthprog as $prog) {
      if ($prog > 0)
        $taskprog++;
    }

    if ($taskprog > 0)
      //$prog = array_sum($monthprog) / $taskprog;
    $prog = array_sum($monthprog);

    else
      $prog = 100;

    $monthexec = App\Poa::Select($month)
      ->Wherein('poa_id', $ids)
      ->Where('poa_type', 'App\Task')
      ->Where('state', true)
      ->Where('month', $m)
      ->pluck($month)
      ->toarray();

    foreach ($monthexec as $exec) {
      if ($exec > 0)
        $taskexec++;
    }

    if ($taskexec > 0)
  //    $exec = array_sum($monthexec) / $taskexec;
        $exec = array_sum($monthexec);cant_op_curr($action)

    else
      $exec = 0; */

  //return number_format((float)($exec * 8.33) / $prog, 2, '.', '');
  return number_format((float)($operationprog * $taskexec) / $taskprog, 2, '.', '');
}

function progexecop($ids, $m)
{
  $totalmonth = 0;
  $month = 'm' . $m;
  $poa = App\Poa::Select($month)
    ->WhereIn('poa_id', $ids)
    ->Where('poa_type', 'App\Operation')
    ->Where('state', false)
    ->Pluck($month)
    ->all();

  $ponderation = App\Definition::Select('ponderation')
    ->WhereIn('definition_id', $ids)
    ->Where('definition_type', 'App\Operation')
    ->Pluck('ponderation')
    ->all();

  for ($i = 0; $i < count($ids); $i++) {
    if (isset($ponderation[$i]))
      $totalmonth += $poa[$i] * $ponderation[$i] / 100.00;

  }

  return number_format((float)$totalmonth, 2, '.', '');

}

function progexecops($ids, $m)
{
  $totalmonth = 0;
  $month = 'm' . $m;
  $poa = App\Poa::Select($month)
    ->WhereIn('poa_id', $ids)
    ->Where('poa_type', 'App\Operation')
    ->Where('state', true)
    ->Where('month', false)
    ->Pluck($month)
    ->all();
  $ponderation = App\Definition::Select('ponderation')
    ->WhereIn('definition_id', $ids)
    ->Where('definition_type', 'App\Operation')
    ->Pluck('ponderation')
    ->all();

  for ($i = 0; $i < count($ids); $i++) {
    if (isset($ponderation[$i]))
      $totalmonth += $poa[$i] * $ponderation[$i] / 100.00;

  }

  return number_format((float)$totalmonth, 2, '.', '');

}

function emptyrecord($cols)
{
  $row = '<tr>
                <td class="align-top text-center" colspan="' . $cols . '">
                    <p class="text-danger">SIN REGISTROS</p>
                </td>
            </tr>';

  return $row;
}

function lasttask($id)
{
  $month = App\Poa::Select('month')
    ->Where('poa_id', $id)
    ->Where('poa_type', 'Like', '%Task')
    ->Where('state', true)
    ->orderby('month', 'DESC')
    ->pluck('month')
    ->first();
  if (!isset($month))
    $month = 0;
  return $month;
}

function logrec($type, $description)
{
  activity($type)->causedBy(isset(\Auth::user()->id) ? \Auth::user()->id : null)
    ->withProperties(['IP' => \Request::ip(),
      'Name' => isset(\Auth::user()->id) ? \Auth::user()->name : null,
    ])
    ->log($description);
  return 0;
}

function permissions_check($attribute, $active)
{
  $editenable = \App\Configuration::Select('edit')
    ->Where('status', true)
    ->pluck('edit')
    ->first();

  $user = \App\User::find(\Auth::user()->id);
  if (!$user->hasPermissionTo($attribute . '.' . $active)) {
    logrec('error', \Route::currentRouteName());
    return false;
  } else
    return true;
}

function sidebar()
{
  $user = \App\User::find(\Auth::user()->id);
  if ($user->sidebar)
    return true;
  else
    return false;
}

function arrows($n)
{
  if ($n >= 95) {
    $arrow = '<span class="badge bg-success"><i class="fa fa-arrow-up"></i></span>';
  } elseif ($n >= 80 && $n < 95) {
    $arrow = '<span class="badge bg-warning"><i class="fa fa-expand"></i></span>';
  } elseif ($n >= 45 && $n < 80) {
    $arrow = '<span class="badge bg-warning"><i class="fa fa-compress"></i></span>';
  } else {
    $arrow = '<span class="badge bg-danger"><i class="fa fa-arrow-down"></i></span>';
  }

  return $arrow;
}

function check_reconf(){
  $chk = App\Configuration::where('reconfigure',1)->get();
  if(count($chk)>0){
    return true;
  }else{
    return false;
  }
}

/**
 * @return Numero devuelve el mes activo de la ultima reprogramación
 */

function activeReprogMonth(){
  $ref = \App\Reformulation::orderBy('id','desc')->first();
  if(is_null($ref)){
    return 0;
  }
  return $ref->month;
}

/**
 * @return Numero Devuelve el año activo de la ultima reprogramación
 */

function activeReprogYear(){
  $ref = \App\Reformulation::orderBy('id','desc')->first();
  if(is_null($ref)){
    return 0;
  }
  return $ref->year;
}

/** Devuelve la sumatoria de las ponderaciones de una accion que estan validas hasta fin de año
 * ----------------------------------------------------------
 * Sintaxis
 * @param Numero $id el ID de la accion la cual queremos saber la sumatoria de la ponderación
 * @return Decimal devolvemos la  sumatoria de la ponderacion de la accion
 */
function current_ponderation($id){
  $total = 0;
  $ids = App\Operation::Where('action_id', $id)->
  orderBy('id')->
  pluck('id')->
  ToArray();
  for($h = 0 ; $h< sizeof($ids); $h++){
    $ponderation =  App\Definition::Select('ponderation')->
                    Where('out', 12)->///aun hay q ver por q hacemos la coparacin con el out,, ya q tenemos q igualar con la conderacion correspondiente
                    Where('definition_type', 'App\Operation')->
                    Where('definition_id', $ids[$h])->
                    first()->ponderation;
    $total += $ponderation;
    //echo $ponderation."--- \n";
  }
  return $total;
}

function activeReprogId(){
  $ref = \App\Reformulation::all()->last();
  if(is_null($ref))
    return 0;
  if($ref->status==0){
    return ($ref->Id)-1;
  }else{
    return $ref->Id;
  }
}

/**
 * Cantidad de operaciones activas
 * =============================================================================================
 */

function cant_op_curr($action){
  $cont = 0;
  $operations = $action->operations()->get();
  foreach($operations as $operation){
    $count = $operation->poas()->where('value', $action->current)->Where('state',false)->count();
    if($count){
      $cont++;
    }
  }
  return $cont;
}

/**
 * Devuelve si un campo en un poa es distinto por texto
 */

function chek_definition($id, $type, $field){
  $type = strtoupper($type);
  switch ($type){
    case ('TASK'):
      $type = 'App\Task';
      $mc = activemonth();
      break;
    case ('OPERATION'):
      $type = 'App\Operation';
      $mc = activemonth();
      break;
    case ('ACTION'):
      $type = 'App\Action';
      $mc = activeReprogMonth();
      break;
  }
  $cadenas = \App\Definition::Where('definition_type','App\Operation')->
                              Where('definition_id',$id)->
                              select($field,'in')->
                              get()->toarray();
                              //obtenemos las cadenas del elemento selecionado
  for($i = 0; $i < count($cadenas);$i++){
    $str1 = '';
    $str2 = '';
    if($i+1 != count($cadenas)){
      ////aqui definios q ahy un siguiente
      $str1 = $cadenas[$i][$field];
      $str2 = $cadenas[$i+1][$field];
      //echo $str1.'++'.$str2.'*'.strcmp ($str1 , $str2 )." \n";
      if(strcmp ($str1 , $str2 )!=0){
        return true;
      }
    }
    ///se sale si es q ya no hay eleemntos al frente
  }
  return false;
}

function get_definition($id, $type, $field){
  $arre = array();
  $type = strtoupper($type);
  switch ($type){
    case ('TASK'):
      $type = 'App\Task';
      $mc = activemonth();
      break;
    case ('OPERATION'):
      $type = 'App\Operation';
      $mc = activemonth();
      break;
    case ('ACTION'):
      $type = 'App\Action';
      $mc = activeReprogMonth();
      break;
  }
  $cadenas = \App\Definition::Where('definition_type','App\Operation')->
                              Where('definition_id',$id)->
                              select($field,'in')->
                              get()->toarray();
                              //obtenemos las cadenas del elemento selecionado
  $pt = 0;
  $arre[$pt]['in']=1;
  $arre[$pt][$field]= $cadenas[$pt][$field];
  ///siempre llenamos el primer campo
  for($i = 0; $i < count($cadenas);$i++){
    $str1 = '';
    $str2 = '';
    if($i+1 != count($cadenas)){
      ////aqui definios q ahy un siguiente
      $str1 = $cadenas[$i][$field];
      $str2 = $cadenas[$i+1][$field];
      //echo $str1.'++'.$str2.'*'.strcmp ($str1 , $str2 )." \n";
      if(strcmp ($str1 , $str2 ) !=0){
        $pt++;
        ///agregamos el in y el out a la siguiente linea
        $arre[$pt]['in']=$cadenas[$i+1]['in'];
        $arre[$pt][$field]= $cadenas[$i+1][$field];
        //$i++;
      }
    }
    ///se sale si es q ya no hay eleemntos al frente
  }
  //armammos el HTML

  return $arre;
}

/**
 * Obtiene indicadores del BCB
 * =======================================================================================
 * @param Numero $cInd (1) Codigo del Indicador del Formato BCB
 * @param Numero $cMon (2) Codigo de la moneda, formato BCB
 * @param cadena $fech (10) Fecha de Consulta formato 'dd/mm/aaaa'
 *
 * @return numero Retorna el valor de la Peticion
 * ======================================================================================
 */

function obtenerIndicador($cind, $cmon, $date){
  $url = 'http://ws01.bcb.gob.bo:8080/ServiciosBCB/indicadores?wsdl';
  $params = array('encoding' => 'UTF-8','verifypeer'=>false);
  $client = new SoapClient($url,$params);
  $dato = $client->obtenerIndicador(['codIndicador'=>$cind,'codMoneda'=>$cmon,'fecha'=>$date]);
  if($dato->return[0]->dato == 0){
    switch ($cind) {
      case '1':
        return $dato->return[4]->dato;
        break;
      case '2':
        return $dato->return[5]->dato;
        break;
      case '3':
        return $dato->return[5]->dato;
        break;
      default:
        # code...
        break;
    }
  }else{
    return false;
  }
}

/**
 * Verifica cuantas operaciones ejecutadas tiene una accion al mes y devielve un color y el codigo de un icono
 * ______________________________________________________________________
 * @param numero $id que define cual es el id de la accion
 * @param numero $type tipo de elemento accion u operacion
 * @param numero $month indica el mes en el cual esta siendo consultado
 *
 */
/**
 *
 */

function quantity_exe($id,$type,$month){
  $arre = [];
  $arre[4]= "";
  $m = 'm'.$month;
  $type = strtoupper($type);
  switch ($type){
      case ('OPERATION'):
        $operation = \App\Operation::find($id);
        $tasks = $operation->tasks()->where('status',true)->get();
        $con = 0;
        $tot = 0;
        foreach ($tasks as $task ) {
          if((!empty($task->poas()->where('month', $month)->first()->$m))&&($task->poas()->where('state',false)->where('in','<=', activemonth())->where('out','>=',activemonth())->pluck('m'.activemonth())->first()>0)){
            $con++;
          }else{
            if((empty($task->poas()->where('month', $month)->first()->$m))&&($task->poas()->where('state',false)->where('in','<=', activemonth())->where('out','>=',activemonth())->pluck('m'.activemonth())->first()>0)){
              $arre[4] .= $task->operation->action->goal->code.'.'.$task->operation->action->code.'.'.$task->operation->code.'.'.$task->code."<br>";
            }
          }
          if(($task->poas()->where('state',false)->where('in','<=', activemonth())->where('out','>=',activemonth())->pluck('m'.activemonth())->first()>0)){
            $tot++;
          }
        }
        $arre[0]=$tot;
        $arre[1]=$con;
        $co = 'default';
        $bo = false;
        if($arre[1]==0){
          $co = "danger";
          $bo = false;
        }
        if($arre[1]>0){
          $co = "warning";
          $bo = false;
        }
        if($arre[1]==$arre[0]){
          $co = "success";
          $bo = true;
        }
        $arre[2]=$co;
        $arre[3]=$bo;
        return $arre;
        break;
      case ('ACTION'):
        $action = \App\Action::find($id);
        $operations = $action->operations()->get();
        $con = 0;
        $tot = 0;
        foreach ($operations as $operation ) {
          if( (!empty($operation->poas()->where('month', $month)->first()->$m))&&
              ($operation->poas()->where('state',false)->where('in','<=', activemonth())->where('out','>=',activemonth())->pluck('m'.activemonth())->first()>0)
            ){
            $con++;
          }else{
            if(empty($operation->poas()->where('month', $month)->first()->$m)&&
              ($operation->poas()->where('state',false)->where('in','<=', activemonth())->where('out','>=',activemonth())->pluck('m'.activemonth())->first()>0)
              ){
                $arre[4] .= $operation->action->goal->code.'.'.$operation->action->code.'.'.$operation->code."<br>";
              }

          }
          if(($operation->poas()->where('state',false)->where('in','<=', activemonth())->where('out','>=',activemonth())->pluck('m'.activemonth())->first()>0)){
            $tot++;
          }
        }
        $arre[0]=$tot;
        $arre[1]=$con;
        $co = 'default';
        if($arre[1]==0){
          $co = "danger";
          $bo = false;
        }
        if($arre[1]>0){
          $co = "warning";
          $bo = false;
        }
        if($arre[1]==$arre[0]){
          $co = "success";
          $bo = true;
        }

        $arre[2]=$co;
        $arre[3]=$bo;
        return $arre;
        break;

      default:
        $arre[0]=0;
        $arre[1]=0;
        $arre[2]='default';
        $arre[3]=false;
        return $arre;
        break;

  }
}

/**
 * Genera la Ejeucion de un presupuesto en sus distintos niveles
 */
function execsBudget($id, $type){
  $type = strtoupper($type);
  //dd($type);
  switch ($type){
    case 'STRUCTURE':
      $structure = \App\Structure::find($id);
      $res = $structure->transactions()->where('certification',false)->get()->sum('outflow')-$structure->transactions()->where('certification',false)->get()->sum('inflow');
      return $res;
      break;

    default:
      return 0;
      break;
  }
}

function check_parent_transaction($transaction){
  $transactionParent = \App\Transaction:: where('middle',$transaction->outflow)->
                                          where('structure_id',$transaction->structure_id)->
                                          where('user_id',$transaction->user_id)->
                                          where('description','Certificación Prespuestaria')->
                                          where('status',1)->
                                          first();
  if(!is_null($transactionParent)){
    return true;
  }else{
    return false;
  }
}


/**
 * ------------------
 * genera el porcentage de ejecucion de una operacion o accion o tarea en caso de que averiguemos como lo hace
 * @param id numero es el Id del elemento a resolver
 * @param type cadena es el tipo de elemento que deseamos procesar 'operation' , 'task', 'action' 'departament'
 *
 */

function percentage($id, $type, $m){
  $arre = array();
  $month = 'm'.$m;
  $type = strtoupper($type);
  switch ($type){
    case ('TASK'):
      ///ver su
      $type = 'App\Task';
      $mc = activemonth();
      break;
    case ('OPERATION'):
      ///funciona operation
      $type = 'App\Operation';
      $mc = activemonth();
      $ids =  App\Task::Select('id')->
                        Where('operation_id', $id)->
                        Where('status',true)->
                        pluck('id')->
                        toarray();
                        $poasp = array();
    for($h = 0;$h<count ($ids); $h++){
      $poa =  App\Poa::select($month)->
              Where('poa_type', 'App\Task')->
              Where('poa_id',$ids[$h])->
              Where('month', false)->
              Where('state', false)->
              orderby('id','desc')->
              pluck($month)->
              first();
      //echo '-'.$poa."-\n" ;
      $poasp[$h] = $poa;
    }
    $poase = array();
    for($h = 0;$h<count ($ids); $h++){
      $poa =  App\Poa::select($month)->
              Where('poa_type', 'App\Task')->
              Where('poa_id',$ids[$h])->
              Where('month', false)->
              Where('state', true)->
              orderby('id','desc')->
              pluck($month)->
              first();
      //echo '-'.$poa."-\n" ;
      $poase[$h] = $poa;
    }
    $sump = array_sum ( $poasp );
    $sume = array_sum ( $poase );
    //echo $sump.'-'.$sume;
    if($sump>0)
      $resp = $sume/$sump;
    else
      $resp = 0;
      break;

    case ('ACTION'):
    ///se buscara generar el algoritmo para la tarea
      $type = 'App\Action';
      $mc = activeReprogMonth();
      break;
  }



  return $resp;
}
/**
 * Devuelve el nombre de un usuario referente al ID
 */

function getNameUser($user_id = 0){
  if($user_id == 0)
    return '';
  $res = \App\User::find($user_id);
  if($res == null)
    return '';
  return $res->name;
}

function stringParce($cadena = ''){
  if($cadena == '')
    return null;
  $cad = substr($cadena,0,1);
  $cad = strtoupper ($cad);
  $has = "\App\ ";
  $cad = $has.$cad;
  $cad = str_replace(' ','',$cad);
  $dena = substr($cadena,1);
  return $cad.$dena;
}
