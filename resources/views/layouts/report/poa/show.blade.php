@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- /.card-header -->
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><b>REPORTE</b></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                          'report' => true,
                          'excel' => true,
                          'urlexcel' => "/excelpoa",
                          'pdf' => false,
                          'urlpdf' => "/pdfpoa"
                          ])
                  @endcomponent
                  <div class="col-sm-12 col-md-6">
                    <div class="pagination-wrapper">
                      {!! $goal->appends(['search' => Request::get('search')])->render() !!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Acción a Mediano Plazo</h5>
              <div class="card-tools">
                <button class="btn btn-sm btn-gray" type="button" data-widget="collapse">
                  <i class="fa fa-minus" aria-hidden="true"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                @foreach($goal as $item)
                  <table class="table">
                    <tr class="table-success">
                      <th class="align-top text-left">PILAR</th>
                      <th class="align-top text-left">{!! $item->doing->result->target->policy->code !!}</th>
                      <td class="align-top text-left" colspan="17">
                        {!! strtoupper($item->doing->result->target->policy->descriptions->first()->description) !!}
                      </td>
                    </tr>
                    <tr class="table-success">
                      <th class="align-top text-left">META</th>
                      <th class="align-top text-left">{!! $item->doing->result->target->code !!}</th>
                      <td class="align-top text-left" colspan="17">
                        {!! strtoupper($item->doing->result->target->descriptions->first()->description) !!}
                      </td>
                    </tr>
                    <tr class="table-success">
                      <th class="align-top text-left">RESULTADO</th>
                      <th class="align-top text-left">{!! $item->doing->result->code !!}</th>
                      <td class="align-top text-left" colspan="17">
                        {!! strtoupper($item->doing->result->descriptions->first()->description) !!}
                      </td>
                    </tr>
                    <tr class="table-success">
                      <th class="align-top text-left">A. M. PLAZO</th>
                      <th class="align-top text-left">{!! $item->code !!}</th>
                      <td class="align-top text-left" colspan="17">
                        {!! strtoupper($item->description) !!}
                      </td>
                    </tr>
                  </table>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      @foreach($goal as $item)
        @php
          $actions = App\Action::Where('goal_id',$item->id)
                                ->Where('year',activeyear())
                                ->get();
          $totalaction =0;
        @endphp
        @foreach($actions as $action)
          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header">
                  <table class="table table-bordered">
                    <tr>
                      <th colspan="14" class="align-top text-left">
                        ACCIÓN {{ $action->code }} :
                        ({{ $action->definitions->first()->ponderation }} %)
                        {!! substr($action->definitions->first()->description,0,80) !!}<br>
                        RESPONSABLE: {{ $action->department->name }}
                      </th>
                    </tr>
                    <tr>
                      <th width="7%"></th>
                      <th width="7%" class="align-top text-center">ENE</th>
                      <th width="7%" class="align-top text-center">FEB</th>
                      <th width="7%" class="align-top text-center">MAR</th>
                      <th width="7%" class="align-top text-center">ABR</th>
                      <th width="7%" class="align-top text-center">MAY</th>
                      <th width="7%" class="align-top text-center">JUN</th>
                      <th width="7%" class="align-top text-center">JUL</th>
                      <th width="7%" class="align-top text-center">AGO</th>
                      <th width="7%" class="align-top text-center">SEP</th>
                      <th width="7%" class="align-top text-center">OCT</th>
                      <th width="7%" class="align-top text-center">NOV</th>
                      <th width="7%" class="align-top text-center">DIC</th>
                      <th width="7%" class="align-top text-center">TTS</th>
                    </tr>
                    <tr class="bg-success">
                      <th class="align-top text-left">PROG</th>
                      @php
                        $total = 0;
                        $totalaction += $action->definitions->first()->ponderation;
                        $ids = App\Operation::Select('id')
                                  ->Where('action_id',$action->id)
                                  ->pluck('id')
                                  ->all();
                        $ponderation = App\Definition::Select('ponderation')
                                      ->WhereIn('definition_id',$ids)
                                      ->Where('definition_type','App\Operation')
                                      ->Pluck('ponderation')
                                      ->all();
                      @endphp
                      @for ($m = 1; $m <= 12; $m++)
                        @php
                          $month = 'm'.$m;
                          $totalmonth = progexecop($ids, $m);
                          $totalmonth = $action->poas()->Where('state',false)->Where('month',false)->first()->$month;
                          $total += $totalmonth;
                        @endphp
                        <td class="align-top text-right">{{ $totalmonth }}</td>
                      @endfor
                      <td class="align-top text-right">{{ number_format((float)$total, 2, '.', '') }}</td>
                    </tr>
                    <tr class="table-success">
                      <th class="align-top text-left">EJEC</th>
                      @php
                        $ids = App\Operation::Select('id')
                        ->Where('action_id',$action->id)
                        ->pluck('id')
                        ->toarray();
                        $total = 0;
                      @endphp
                      @for ($m = 1; $m <= 12; $m++)
                        @php
                          $month = 'm'.$m;
                          $opmonth = progexecops($ids,$m);
                          $opmonth = $action->poas()->Where('state',true)->Where('month',false)->first()->$month;
                          $total += $opmonth;
                        @endphp
                        <td class="align-top text-right">{{ number_format((float)$opmonth, 2, '.', '') }}</td>
                      @endfor
                      <td class="align-top text-right">{{ number_format((float)$total, 2, '.', '') }}</td>
                    </tr>
                  </table>
                  <div class="card-tools">
                    <button class="btn btn-sm btn-gray" type="button" data-widget="collapse">
                      <i class="fa fa-minus" aria-hidden="true"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    @php
                      $operations = App\Operation::Where('action_id',$action->id)->get();
                      $totaloperation =0;
                    @endphp
                    @foreach($operations as $operation)
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="card">
                            <div class="card-header">
                              <table class="table table-bordered">
                                <tr>
                                  <th colspan="14" class="align-top text-left">
                                    OPERACIÓN {{ $operation->code }} :
                                    ({{ $operation->definitions->first()->ponderation }} %)
                                    {!! substr($operation->definitions->first()->description,0,80) !!}
                                  </th>
                                </tr>
                                <tr>
                                  <th width="7%"></th>
                                  <th width="7%" class="align-top text-center">ENE</th>
                                  <th width="7%" class="align-top text-center">FEB</th>
                                  <th width="7%" class="align-top text-center">MAR</th>
                                  <th width="7%" class="align-top text-center">ABR</th>
                                  <th width="7%" class="align-top text-center">MAY</th>
                                  <th width="7%" class="align-top text-center">JUN</th>
                                  <th width="7%" class="align-top text-center">JUL</th>
                                  <th width="7%" class="align-top text-center">AGO</th>
                                  <th width="7%" class="align-top text-center">SEP</th>
                                  <th width="7%" class="align-top text-center">OCT</th>
                                  <th width="7%" class="align-top text-center">NOV</th>
                                  <th width="7%" class="align-top text-center">DIC</th>
                                  <th width="7%" class="align-top text-center">TTS</th>
                                </tr>
                                <tr class="table-warning">
                                  <th class="align-top text-left">PROG</th>
                                  @php
                                    $total = 0;
                                    $totaloperation += $operation->definitions->first()->ponderation;
                                  @endphp
                                  @for ($m = 1; $m <= 12; $m++)
                                    @php
                                      $month = 'm'.$m;
                                      $total +=  $operation->poas->first()->$month;
                                    @endphp
                                    <td class="align-top text-right">
                                      {{ number_format((float)$operation->poas->first()->$month, 2, '.', '') }}
                                    </td>
                                  @endfor
                                  <td class="align-top text-right">
                                    {{ number_format((float)$total, 2, '.', '') }}
                                  </td>
                                </tr>
                                <tr class="table">
                                  <th class="align-top text-left">EJEC</th>
                                  @php
                                    $exops = App\Poa::Where('poa_id',$operation->id)
                                                    ->Where('poa_type','App\Operation')
                                                    ->Where('state',true)
                                                    ->first();
                                    $total = 0;
                                  @endphp
                                  @for ($m = 1; $m <= 12; $m++)
                                    @php
                                      $month = 'm'.$m;
                                    $total += $exops->$month;
                                    @endphp
                                    <td class="align-top text-right">
                                      {{ number_format((float)$exops->$month, 2, '.', '') }}
                                    </td>
                                  @endfor
                                  <td class="align-top text-right">
                                    {{ number_format((float)$total, 2, '.', '') }}
                                  </td>
                                </tr>
                              </table>
                              <div class="card-tools">
                                <button class="btn btn-sm btn-gray" type="button" data-widget="collapse">
                                  <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="table-responsive">
                                @php
                                  $tasks = App\Task::Where('operation_id',$operation->id)->get();
                                  $totaltask = [0,0,0,0,0,0,0,0,0,0,0,0];
                                @endphp
                                @foreach($tasks as $task)
                                  <table class="table table-bordered">
                                    <!-- TAREAS -->
                                    <tr class="table-danger">
                                      <th width="7%" class="align-top text-left">TAREA {{ $task->code }}
                                        @if(!$task->status)
                                          <br><span class="badge badge-danger">ELIMINADA</span>
                                        @endif
                                      </th>
                                      @php
                                        $total = 0;
                                      @endphp
                                      @for ($m = 1; $m <= 12; $m++)
                                        @php
                                          $month = 'm'.$m;
                                          $total +=  $task->poas->first()->$month;
                                          if($task->poas->first()->$month > 0)
                                            $totaltask[$m-1]++;
                                        @endphp
                                        <td width="7%" class="align-top text-right">
                                          {{ number_format((float)$task->poas->first()->$month, 2, '.', '') }}
                                        </td>
                                      @endfor
                                      <td width="7%" class="align-top text-right">
                                        {{ number_format((float)$total, 2, '.', '') }}
                                      </td>
                                    </tr>
                                    <tr class="table">
                                      <th class="align-top text-left">EJECT</th>
                                      @php
                                        $total = 0;
                                        $totalexec = 0;
                                      @endphp
                                      @for ($m = 1; $m <= 12; $m++)
                                        @php
                                          $month = 'm'.$m;
                                          !is_null($task->poas->Where('state',true)->Where('month',$m)->first())?
                                            $totalexec += $task->poas->Where('state',true)->Where('month',$m)->first()->$month:
                                            null;
                                        @endphp
                                        <td class="align-top text-right">
                                          {{ !is_null($task->poas->Where('state',true)->Where('month',$m)->first())?
                                              number_format((float)$task->poas->Where('state',true)->Where('month',$m)->first()->$month, 2, '.', ''):
                                              '0.00' }}
                                        </td>
                                      @endfor
                                      <td class="align-top text-right">{{ number_format((float)$totalexec, 2, '.', '') }}</td>
                                    </tr>

                                  </table>
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      @endforeach
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
