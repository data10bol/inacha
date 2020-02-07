@extends('layouts.master')
@section('content')

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="card ">
          <div class="card-header d-flex p-0">
            <h3 class="card-title p-3"><strong>REPORTE MENSUAL: {{$department->initial}}</strong></h3>
            <ul class="nav nav-pills ml-auto p-2">
              <li class="nav-item"><a class="btn btn-outline-danger active " href="#tab_1" data-toggle="tab">Por Acciones</a></li>
              &nbsp;
              &nbsp;
              <li class="nav-item"><a class="btn btn-outline-danger " href="#tab_2" data-toggle="tab">Por Operaciones</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <!--inicio de los datos uno-->
                @php
                    $urlpdf = ''.Request::url().'?month='.Request::get('month').'&id='.Hashids::encode($department->id ).'&type=1';
                @endphp
                {!! Form::button('<i class="fa fa-file-pdf-o" aria-hidden="true"></i> Generar Reporte',
                  array('title' => 'PDF',
                      'type' => 'button',
                      'class' => 'btn btn-danger btn-sm',
                      'disabled' => null,
                      'onclick'=>'window.open("'. $urlpdf .'")', ))
              !!}
                <hr>
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-title align-top text-center">
                        @for ($i=1;$i<=sizeof($months);$i++)
                          {!! Form::button(ucfirst($months[$i]),
                                      array('title' => ucfirst($months[$i]),
                                              'type' => 'button',
                                              'class' => ($currentmonth==$i)?'btn btn-danger btn-sm':'btn btn-default btn-sm',
                                              'onclick'=>'window.location.href="showreportpoa?month='.$i.'&id='.Hashids::encode($department->id ).'"'))
                          !!}
                        @endfor
                      </div>
                      <div class="card-body">
                        <div class="row align-content-lg-center">
                          <div class="col-6">
                            <div class="info-box mb-3 bg-{{$department->color}}">
                                <span class="info-box-icon">
                                  <i class="{{ $department->icon }}"></i>
                                </span>
                              <div class="info-box-content">
                                <div class="info-box-text">
                                  <strong>{{ strtoupper($department->name) }}</strong>
                                  <span class="info-box-number">
                                    Acumulado a {{ ucfirst(\App\Month::Where('id', $currentmonth)->first()->name) }} ::
                                    Ejecutado: {{ accum($department->id, 'Department', true, $currentmonth) }}
                                 % / Programado: {{ accum($department->id, 'Department', false, $currentmonth) }} %
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="info-box mb-3 bg-{{$department->color}}">
                                <span class="info-box-icon">
                                  <i class="{{ $department->icon }}"></i>
                                </span>
                              <div class="info-box-content">
                                <div class="info-box-text">
                                  <strong>{{ strtoupper($department->name) }}</strong>
                                  </span>
                                  <span class="info-box-number">
          
                                    @php
                                      $m = $currentmonth;
                                      $month = 'm' . $m;
                                      if($m > 1){
                                        if(activeReprogMonth()==$m){
                                          $datap =  number_format((float)accum($department->id, 'Department', false, $m), 2, '.', '') - number_format((float)accum($department->id, 'Department', true, $m -1), 2, '.', '');                                
                                          $datae =  number_format((float)accum($department->id, 'Department', true, $m), 2, '.', '') - number_format((float)accum($department->id, 'Department', true, $m-1), 2, '.', '') ;
                                        }else{        
                                          $datap =  number_format((float)accum($department->id, 'Department', false, $m), 2, '.', '') - number_format((float)accum($department->id, 'Department', false, $m -1), 2, '.', '');                                
                                          $datae =  number_format((float)accum($department->id, 'Department', true, $m), 2, '.', '') - number_format((float)accum($department->id, 'Department', true, $m-1), 2, '.', '') ;
                                        }
                                      }
                                      else {
                                        $datap =  number_format((float)accum($department->id, 'Department', false, $m), 2, '.', '');
                                        $datae =  number_format((float)accum($department->id, 'Department', true, $m), 2, '.', '');
                                      }
          
                                    @endphp   
                                    En {{ ucfirst(\App\Month::Where('id', $currentmonth)->first()->name) }} ::
                                    Ejecutado: {{ $datae }}
                                    % /  Programado: {{ $datap }} %
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card card-{{$department->color}}">
                  <div class="card-header">
                    <h3 class="card-title">Reporte Por Accion de Corto Plazo</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fa fa-expand"></i></button>
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i></button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
              <table width="100%" class="table table-bordered table-hover dataTable"
                      role="grid"
                      aria-describedby="main table">
                <thead class=" text-danger">
                @foreach($header as $item)
                  <th class="text-{{ strtolower($item['align']) }}">
                    <strong>{{ strtoupper($item['text']) }}</strong>
                  </th>
                @endforeach
                </thead>
                <tbody>
                @forelse($actions as $item)
                  @php
                    $month = 'm'.$currentmonth;
                    $prog=$item->poas()->
                              Where('state',false)->
                              Where('month',false)->
                              Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->
                              orderby('id','desc')->
                              first()->$month;
                    $exec=$item->poas()->
                              Where('state',true)->
                              Where('month',false)->

                              Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->
                              orderby('id','desc')->
                              first()->$month;
                    $accump = accum($item->id,'Action',false,$currentmonth);
                    $accume = accum($item->id,'Action',true,$currentmonth);
                  @endphp
                  <tr style="font-weight:bold" bgcolor="#d3d3d3">
                    <td class="align-top text-left" width="75 px">
                      {{ $item->goal->code }}.{{
                          $item->code }}<br>
                      @if(!$item->status)
                        <span class="badge badge-danger">Borrado</span>
                      @endif
                    </td>
                    <td class="align-top text-justify" width="200 px">
                      {!! substr($item->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->description, 0, 200) !!}
                      {!! strlen($item->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->description) > 200 ? '...' : '' !!}
                    </td>
                    <td class="align-top text-center">
                      {{ $item->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->ponderation }}
                    </td>
                    <td class="align-top text-center">
                      {{ $item->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->aim }}
                    </td>
                    <td class="align-top text-center">
                      {{ $item->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->pointer }}
                    </td>
                    <td class="align-top text-center">
                      {{ number_format((float)$accump, 2, '.', '') }}
                    </td>
                    <td class="align-top text-center">
                      {{ number_format((float)$accume, 2, '.', '') }}
                    </td>
                    <td class="align-top text-center">
                      {!! arrows(number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '')) !!}
                    </td>
                    <td class="align-top text-center">
                      {{ number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '') }}%
                    </td>
                    <td class="align-top text-left">
                      {!! isset($item->poas->
                          Where('state',true)->
                          Where('month',$currentmonth)->
                          first()->success)?
                            $item->poas->
                          Where('state',true)->
                          Where('month',$currentmonth)->
                          first()->success:
                          '' !!}
                    </td>
                    <td class="align-top text-center">
                      {{ $prog }}
                    </td>
                    <td class="align-top text-center">
                      {{ $exec  }}
                    </td>
                    <td class="align-top text-center">
                      {!! arrows(number_format((float)($exec>$prog)?100:(
                      ($prog>0)?$exec*100/$prog:0), 2, '.', '')) !!}
                    </td>
                    <td class="align-top text-center">
                      {{ number_format((float)($exec>$prog)?100:(
                      ($prog>0)?$exec*100/$prog:0), 2, '.', '') }}%
                    </td>
                    <td class="align-top text-left">
                      {{ $item->department->name }}
                    </td>
                  </tr>
                  @php
                    $operations = App\Operation::Where('action_id',$item->id)->get();
                  @endphp
                  @forelse($operations as $operation)
                    @php
                      $prog=$operation->poas->
                                Where('state',false)->
                                Where('month',false)->
                                Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->
                                first()->$month;
                      $exec=$operation->poas->
                                Where('state',true)->
                                Where('month',false)->
                                Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->
                                first()->$month;
                      $accump =accum($operation->id,'Operation',false,$currentmonth);
                      $accume = accum($operation->id,'Operation',true,$currentmonth);
                    @endphp
                    <tr style="font-weight:bold" bgcolor="#f5f5f5">
                      <td class="align-top text-left" width="75">
                        {{ $operation->action->goal->code }}.{{
                          $operation->action->code }}.{{
                          $operation->code }}<br>
                        @if(!$operation->status)
                          <span class="badge badge-danger">REPROG</span>
                        @endif
                      </td>
                      <td class="align-top text-justify">
                        {!! substr($operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->description, 0, 200) !!}
                        {!! strlen($operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->description) > 200 ? '...' : '' !!}
                      </td>
                      <td class="align-top text-center">
                        {{ $operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->ponderation }}
                      </td>
                      <td class="align-top text-center">
                        {{ $operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->aim }}
                      </td>
                      <td class="align-top text-center">
                        {{ $operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->pointer }}
                      </td>
                      <td class="align-top text-center">
                        {{ number_format((float)$accump, 2, '.', '') }}
                      </td>
                      <td class="align-top text-center">
                        {{ number_format((float)$accume, 2, '.', '') }}
                      </td>
                      <td class="align-top text-center">
                        {!! arrows(number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '')) !!}
                      </td>
                      <td class="align-top text-center">
                        {{ number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '') }}%
                      </td>
                      <td class="align-top text-left">
                        {!! isset($operation->poas->
                                              Where('state',true)->
                                              Where('month',$currentmonth)->
                                              first()->success)?
                                  $operation->poas->
                                              Where('state',true)->
                                              Where('month',$currentmonth)->
                                              first()->success:
                                              '' !!}
                      </td>
                      <td class="align-top text-center">
                        {{ $prog }} 
                      </td>
                      <td class="align-top text-center">
                        @php
                            $perc = percentage($operation->id, 'operation', $currentmonth);
                            ///se realizo el calculo del porcentage en base a la ejecucion de las tareas del mes
                            $exec = $prog*$perc;
                        @endphp
                        {{ number_format($exec, 2, '.', '')  }}
                      </td>
                      <td class="align-top text-center">
                        {!! arrows(number_format((float)($exec>$prog)?100:(
                        ($prog>0)?$exec*100/$prog:0), 2, '.', '')) !!}
                      </td>
                      <td class="align-top text-center">
                        {{ number_format((float)($exec>$prog)?100:(
                        ($prog>0)?$exec*100/$prog:0), 2, '.', '') }}%
                      </td>
                      <td class="align-top text-left">
                        {{ $operation->definitions->last()->department->name }}
                      </td>
                    </tr>
                    @php
                      $tasks = App\Task::Where('operation_id',$operation->id)->get();
                    @endphp
                    @forelse($tasks as $task)
                      @php
                        $prog=$task->poas->
                                  Where('state',false)->
                                  Where('month',false)->
                                  first()->$month;
                        $exec=$task->poas->
                                  Where('state',true)->
                                  Where('month',false)->
                                  first()->$month;
                        $accump =accum($task->id,'Task',false,$currentmonth);
                        $accume = accum($task->id,'Task',true,$currentmonth);
                      @endphp
                      <tr>
                        <td class="align-top text-left" width="75">
                          {{ $task->operation->action->goal->code }}.{{
                            $task->operation->action->code }}.{{
                            $task->operation->code }}.{{
                            $task->code }}<br>
                          @if(!$task->status)
                            <span class="badge badge-danger">REPROG</span>
                          @endif
                        </td>
                        <td class="align-top text-justify">
                          {!! substr($task->definitions->first()->description, 0, 200) !!}
                          {!! strlen($task->definitions->first()->description) > 200 ? '...' : '' !!}
                        </td>
                        <td class="align-top text-center" colspan="3">

                        </td>
                        <td class="align-top text-center">
                          {{ number_format((float)$accump, 2, '.', '') }}
                        </td>
                        <td class="align-top text-center">
                          {{ number_format((float)$accume, 2, '.', '') }}
                        </td>
                        <td class="align-top text-center">
                          {!! arrows(number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '')) !!}
                        </td>
                        <td class="align-top text-center">
                          {{ number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '') }}%
                        </td>
                        <td class="align-top text-left">
                          {!! isset($task->poas->
                              Where('state',true)->
                              Where('month',$currentmonth)->
                              first()->success)?
                              $task->poas->
                              Where('state',true)->
                              Where('month',$currentmonth)->
                              first()->success:
                                ''!!}
                        </td>
                        <td class="align-top text-center">
                          {{ $prog }} 
                        </td>
                        <td class="align-top text-center">
                          {{ $exec  }}
                        </td>
                        <td class="align-top text-center">
                          {!! arrows(number_format((float)($exec>$prog)?100:(
                          ($prog>0)?$exec*100/$prog:0), 2, '.', '')) !!}
                        </td>
                        <td class="align-top text-center">
                          {{ number_format((float)($exec>$prog)?100:(
                          ($prog>0)?$exec*100/$prog:0), 2, '.', '') }}%
                        </td>
                        <td class="align-top text-left">
                          @forelse ($task->users as $taskuser)
                            <li>{{ $taskuser->name }}</li>
                          @empty
                            <li>SIN REGISTROS</li>
                          @endforelse
                        </td>
                      </tr>
                    @empty
                      {!! emptyrecord(count($header)) !!}
                    @endforelse
                  @empty
                    {!! emptyrecord(count($header)) !!}
                  @endforelse
                @empty
                  {!! emptyrecord(count($header)) !!}
                @endforelse
                </tbody>
              </table>
              
                  </div>
                </div>
              <!-- /.card -->
                <!--fina de los datos uno-->
              </div>
              <div class="tab-pane" id="tab_2">
                @php
                    $urlpdf = ''.Request::url().'?month='.Request::get('month').'&id='.Hashids::encode($department->id ).'&type=2';
                @endphp
                {!! Form::button('<i class="fa fa-file-pdf-o" aria-hidden="true"></i> Generar Reporte',
                  array('title' => 'PDF',
                      'type' => 'button',
                      'class' => 'btn btn-danger btn-sm',
                      'disabled' => null,
                      'onclick'=>'window.open("'. $urlpdf .'")', ))
              !!}
                <hr>
                <div class="row">
                  <div class="col-12">
          
                    <div class="card">
                      <div class="card-title align-top text-center">
                        @for ($i=1;$i<=sizeof($months);$i++)
                          {!! Form::button(ucfirst($months[$i]),
                                      array('title' => ucfirst($months[$i]),
                                              'type' => 'button',
                                              'class' => ($currentmonth==$i)?'btn btn-danger btn-sm':'btn btn-default btn-sm',
                                              'onclick'=>'window.location.href="showreportpoa?month='.$i.'&id='.Hashids::encode($department->id ).'"'))
                          !!}
                        @endfor
                      </div>
                      <div class="card-body">
                        <div class="row align-content-lg-center">
                          <div class="col-6">
                            <div class="info-box mb-3 bg-{{$department->color}}">
                                <span class="info-box-icon">
                                  <i class="{{ $department->icon }}"></i>
                                </span>
                              <div class="info-box-content">
                                <div class="info-box-text">
                                  <strong>{{ strtoupper($department->name) }}</strong>
                                  <span class="info-box-number">
                                    Acumulado a {{ ucfirst(\App\Month::Where('id', $currentmonth)->first()->name) }} ::
                                    Ejecutado: {{ accum($department->id, 'Department', true, $currentmonth) }}
                                 % / Programado: {{ accum($department->id, 'Department', false, $currentmonth) }} %
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="info-box mb-3 bg-{{$department->color}}">
                                <span class="info-box-icon">
                                  <i class="{{ $department->icon }}"></i>
                                </span>
                              <div class="info-box-content">
                                <div class="info-box-text">
                                  <strong>{{ strtoupper($department->name) }}</strong>
                                  </span>
                                  <span class="info-box-number">
          
                                    @php
                                      $m = $currentmonth;
                                      $month = 'm' . $m;
                                      if($m > 1){
                                        if(activeReprogMonth()==$m){
                                          $datap =  number_format((float)accum($department->id, 'Department', false, $m), 2, '.', '') - number_format((float)accum($department->id, 'Department', true, $m -1), 2, '.', '');                                
                                          $datae =  number_format((float)accum($department->id, 'Department', true, $m), 2, '.', '') - number_format((float)accum($department->id, 'Department', true, $m-1), 2, '.', '') ;
                                        }else{        
                                          $datap =  number_format((float)accum($department->id, 'Department', false, $m), 2, '.', '') - number_format((float)accum($department->id, 'Department', false, $m -1), 2, '.', '');                                
                                          $datae =  number_format((float)accum($department->id, 'Department', true, $m), 2, '.', '') - number_format((float)accum($department->id, 'Department', true, $m-1), 2, '.', '') ;
                                        }
                                      }
                                      else {
                                        $datap =  number_format((float)accum($department->id, 'Department', false, $m), 2, '.', '');
                                        $datae =  number_format((float)accum($department->id, 'Department', true, $m), 2, '.', '');
                                      }
          
                                    @endphp   
                                    En {{ ucfirst(\App\Month::Where('id', $currentmonth)->first()->name) }} ::
                                    Ejecutado: {{ $datae }}
                                    % /  Programado: {{ $datap }} %
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card card-{{$department->color}}">
                  <div class="card-header">
                    <h3 class="card-title">Reporte Por Accion de Operación</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fa fa-expand"></i></button>
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i></button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
                    <table width="100%" class="table table-bordered table-hover dataTable"
                    role="grid"
                    aria-describedby="main table">
                      <thead class=" text-danger">
                      @foreach($header2 as $item)
                        <th class="text-{{ strtolower($item['align']) }}">
                          <strong>{{ strtoupper($item['text']) }}</strong>
                        </th>
                      @endforeach
                      </thead>
                    
                    <tbody>
                      @forelse($operations2 as $operation)
                      @php
                        $prog=$operation->poas->
                                  Where('state',false)->
                                  Where('month',false)->
                                  Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->
                                  first()->$month;
                        $exec=$operation->poas->
                                  Where('state',true)->
                                  Where('month',false)->
                                  Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->
                                  first()->$month;
                        $accump =accum($operation->id,'Operation',false,$currentmonth);
                        $accume = accum($operation->id,'Operation',true,$currentmonth);
                      @endphp
                      <tr style="font-weight:bold" bgcolor="#f5f5f5">
                        <td class="align-top text-left" width="75">
                          {{ $operation->action->goal->code }}.{{
                            $operation->action->code }}.{{
                            $operation->code }}<br>
                          @if(!$operation->status)
                            <span class="badge badge-danger">REPROG</span>
                          @endif
                        </td>
                        <td class="align-top text-justify">
                          {!! substr($operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->description, 0, 200) !!}
                          {!! strlen($operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->description) > 200 ? '...' : '' !!}
                        </td>
                        <td class="align-top text-center">
                          {{ $operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->dep_ponderation }}
                        </td>
                        <td class="align-top text-center">
                          {{ $operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->aim }}
                        </td>
                        <td class="align-top text-center">
                          {{ $operation->definitions->Where('in','<=',$currentmonth)->Where('out','>=',$currentmonth)->first()->pointer }}
                        </td>
                        <td class="align-top text-center">
                          {{ number_format((float)$accump, 2, '.', '') }}
                        </td>
                        <td class="align-top text-center">
                          {{ number_format((float)$accume, 2, '.', '') }}
                        </td>
                        <td class="align-top text-center">
                          {!! arrows(number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '')) !!}
                        </td>
                        <td class="align-top text-center">
                          {{ number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '') }}%
                        </td>
                        <td class="align-top text-left">
                          {!! isset($operation->poas->
                                                Where('state',true)->
                                                Where('month',$currentmonth)->
                                                first()->success)?
                                    $operation->poas->
                                                Where('state',true)->
                                                Where('month',$currentmonth)->
                                                first()->success:
                                                '' !!}
                        </td>
                        <td class="align-top text-center">
                          {{ $prog }} 
                        </td>
                        <td class="align-top text-center">
                          @php
                              $perc = percentage($operation->id, 'operation', $currentmonth);
                              ///se realizo el calculo del porcentage en base a la ejecucion de las tareas del mes
                              $exec = $prog*$perc;
                          @endphp
                          {{ number_format($exec, 2, '.', '')  }}
                        </td>
                        <td class="align-top text-center">
                          {!! arrows(number_format((float)($exec>$prog)?100:(
                          ($prog>0)?$exec*100/$prog:0), 2, '.', '')) !!}
                        </td>
                        <td class="align-top text-center">
                          {{ number_format((float)($exec>$prog)?100:(
                          ($prog>0)?$exec*100/$prog:0), 2, '.', '') }}%
                        </td>
                        <td class="align-top text-left">
                          {{ $operation->definitions->last()->department->name }}
                        </td>
                      </tr>
                      @php
                        $tasks = App\Task::Where('operation_id',$operation->id)->get();
                      @endphp
                      @forelse($tasks as $task)
                        @php
                          $prog=$task->poas->
                                    Where('state',false)->
                                    Where('month',false)->
                                    first()->$month;
                          $exec=$task->poas->
                                    Where('state',true)->
                                    Where('month',false)->
                                    first()->$month;
                          $accump =accum($task->id,'Task',false,$currentmonth);
                          $accume = accum($task->id,'Task',true,$currentmonth);
                        @endphp
                        <tr>
                          <td class="align-top text-left" width="75">
                            {{ $task->operation->action->goal->code }}.{{
                              $task->operation->action->code }}.{{
                              $task->operation->code }}.{{
                              $task->code }}<br>
                            @if(!$task->status)
                              <span class="badge badge-danger">REPROG</span>
                            @endif
                          </td>
                          <td class="align-top text-justify">
                            {!! substr($task->definitions->first()->description, 0, 200) !!}
                            {!! strlen($task->definitions->first()->description) > 200 ? '...' : '' !!}
                          </td>
                          <td class="align-top text-center" colspan="3">
  
                          </td>
                          <td class="align-top text-center">
                            {{ number_format((float)$accump, 2, '.', '') }}
                          </td>
                          <td class="align-top text-center">
                            {{ number_format((float)$accume, 2, '.', '') }}
                          </td>
                          <td class="align-top text-center">
                            {!! arrows(number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '')) !!}
                          </td>
                          <td class="align-top text-center">
                            {{ number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '') }}%
                          </td>
                          <td class="align-top text-left">
                            {!! isset($task->poas->
                                Where('state',true)->
                                Where('month',$currentmonth)->
                                first()->success)?
                                $task->poas->
                                Where('state',true)->
                                Where('month',$currentmonth)->
                                first()->success:
                                 ''!!}
                          </td>
                          <td class="align-top text-center">
                            {{ $prog }} 
                          </td>
                          <td class="align-top text-center">
                            {{ $exec  }}
                          </td>
                          <td class="align-top text-center">
                            {!! arrows(number_format((float)($exec>$prog)?100:(
                            ($prog>0)?$exec*100/$prog:0), 2, '.', '')) !!}
                          </td>
                          <td class="align-top text-center">
                            {{ number_format((float)($exec>$prog)?100:(
                            ($prog>0)?$exec*100/$prog:0), 2, '.', '') }}%
                          </td>
                          <td class="align-top text-left">
                            @forelse ($task->users as $taskuser)
                              <li>{{ $taskuser->name }}</li>
                            @empty
                              <li>SIN REGISTROS</li>
                            @endforelse
                          </td>
                        </tr>
                      @empty
                        {!! emptyrecord(count($header)) !!}
                      @endforelse
                    @empty
                      {!! emptyrecord(count($header)) !!}
                    @endforelse
                    </tbody>
                  </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
      </div>



      



      
    </div>
    <!-- /.container-fluid -->
    </div>
  </section>
@endsection
