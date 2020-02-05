@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>TAREA:
                  {{   $task->operation->action->goal->code }}.{{
                          $task->operation->action->code }}.{{
                          $task->operation->code }}.{{
                          $task->code }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $task->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => true,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $task->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => false,
                      'del' => false,
                      'editenable' => ($task->status)?true:false
                  ])

                  @endcomponent
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      @php $month = ucfirst(\App\Month::Where('id', activemonth())->first()->name) @endphp
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-title align-top text-center">
              <h3 class="card-title">
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-header no-border">
                      <div>
                        <h3 class="card-title">
                          <strong>
                            <span>Avance anual</span>
                          </strong>
                        </h3>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="d-flex">
                        <div id="charta"
                             style="min-width: 450px; height: 350px; margin: 0 auto"></div>
                        {!! $chart_accum !!}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-header no-border">
                      <div>
                        <h3 class="card-title">
                          <strong>
                            <strong>
                              <span>Avance de {{ $month }}</span>
                            </strong>
                          </strong>
                        </h3>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="d-flex">
                        <div id="chartm"
                             style="min-width: 450px; height: 350px; margin: 0 auto"></div>
                        {!! $chart_month  !!}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-header no-border">
                      <div>
                        <h3 class="card-title">
                          <strong>
                            <strong>
                              <span>Avance acumulado a {{ $month }}</span>
                            </strong>
                          </strong>
                        </h3>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="d-flex">
                        <div id="chartam"
                             style="min-width: 450px; height: 350px; margin: 0 auto"></div>
                        {!! $chart_accum_month !!}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-bordered table-condensed dataTable"
                     role="grid"
                     aria-describedby="main table">
                <tr>
                  <td class="align-top text-justify">
                    <table class="table dataTable" width="100%">
                      <tr>
                        <th class="align-top text-left" style="width: 120px">
                          CÓDIGO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-3">
                            {{  $task->operation->action->goal->code }}.{{
                                    $task->operation->action->code }}.{{
                                    $task->operation->code }}.{{
                                    $task->code }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          GESTIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {{ $task->operation->action->year }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left" style="width: 100px">
                          DEPARTAMENTO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {{ $task->operation->action->department->name }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          RESPONSABLE(S)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            <ul>
                              @forelse ($task->users as $taskuser)
                                <li>{{ $taskuser->name }}</li>
                              @empty
                                <li>SIN REGISTROS</li>
                              @endforelse
                            </ul>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          ESTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-3">
                            @if($task->status)
                              <span class="badge badge-success">VIGENTE</span> @else
                              <span class="badge badge-danger">ELIMINADA</span> @endif
                          </div>
                        </td>
                      </tr>
                      @if(!$task->status)
                        <tr>
                          <th class="align-top text-left h6">
                            RAZÓN
                          </th>
                          <td class="align-top text-justify">
                            <div class="col-sm-12">
                              {{ $task->reason }}
                            </div>
                          </td>
                        </tr>
                      @endif
                      <tr>
                        <th class="align-top text-left">
                          PILAR
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->doing->result->target->policy->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          META
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->doing->result->target->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          RESULTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->doing->result->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          ACCIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->doing->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          A. MEDIANO PLAZO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          A. CORTO PLAZO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->definitions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          OPERACIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->definitions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          DESCRIPCIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->definitions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                    <!--                        <tr>
                          <th class="align-top text-left">
                            U. MEDIDA
                          </th>
                          <td class="align-top text-justify">
                            <div class="col-sm-4">
                              {{ $task->definitions->first()->measure }}
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">
                      BASE (%)
                    </th>
                    <td class="align-top text-justify">
                      <div class="col-sm-4">
{{ $task->definitions->first()->base }}
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">
                      META (%)
                    </th>
                    <td class="align-top text-justify">
                      <div class="col-sm-4">
{{ $task->definitions->first()->aim }}
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">
                      DESCRIBE
                    </th>
                    <td class="align-top text-justify">
                      <div class="col-sm-4">
{{ $task->definitions->first()->describe }}
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">
                      INDICADOR
                    </th>
                    <td class="align-top text-justify">
                      <div class="col-sm-4">
{{ $task->definitions->first()->pointer }}
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">
                      VALIDACIÓN
                    </th>
                    <td class="align-top text-justify">
                      <div class="col-sm-12">
{!! $task->definitions->first()->validation !!}
                      </div>
                    </td>
                  </tr> -->
                    </table>
                  </td>
                  @php
                    $total = 0;
                    $totalexec = 0;
                  @endphp
                  <td class="align-top text-justify" style="width: 390px">
                    <table class="table table-bordered table-striped " cellspacing="0" width="100%">
                      <tr>
                        <th colspan="3" class="align-center text-center">
                          CRONOGRAMA
                        </th>
                      </tr>
                      <tr>
                        <th class="align-center text-center">
                          MES
                        </th>
                        <th class="align-center text-center">
                          PROGRAMADO
                        </th>
                        <th class="align-center text-center">
                          EJECUTADO
                        </th>
                      </tr>
                      @foreach($months AS $month)
                        <tr>
                          <th class="align-top text-left">
                            {{ ucfirst ($month->name) }}
                          </th>
                          <td class="text-right">
                            <div class="col-sm-10 align-top ">
                              {{ $task->poas->pluck('m'.$month->id)->first() }}%
                              @php $total += $task->poas->pluck('m'.$month->id)->first() @endphp
                            </div>
                          </td>
                          <td class="text-right">
                            <div class="col-sm-10 align-top ">
                              @php
                                $m = 'm'.$month->id;
                                isset($task->poas->Where('state',true)->Where('month',$month->id)->first()->$m)?
                                    $totalexec += $task->poas->Where('state',true)->Where('month',$month->id)->first()->$m:
                                    null;
                              @endphp
                              {{ isset($task->poas->Where('state',true)->Where('month',$month->id)->first()->$m)?
                                  $task->poas->Where('state',true)->Where('month',$month->id)->first()->$m:
                                  '0'}}%

                            </div>
                          </td>
                        </tr>
                      @endforeach
                      <tr>
                        <th class="align-top text-center">
                          TOTAL
                        </th>
                        <th>
                          <div class="col-sm-10 align-top text-right">
                            {{ $total }}%
                          </div>
                        </th>
                        <th>
                          <div class="col-sm-10 align-top text-right">
                            {{ $totalexec }}%
                          </div>
                        </th>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-bordered table-condensed dataTable"
                     role="grid"
                     aria-describedby="main table">
                <tr>
                  <td colspan="2">
                    <table class="table table-sm table-bordered table-striped">
                      <tr>
                        <th>
                          MES
                        </th>
                        <th>
                          LOGRO(S)
                        </th>
                        <th>
                          PROBLEMA(S)
                        </th>
                        <th>
                          SOLUCION(ES)
                        </th>
                      </tr>
                      @forelse($months AS $month)
                        <tr>
                          <td>
                            {{ ucfirst ($month->name) }}
                          </td>
                          <td>
                            {!! isset($task->poas->Where('month',$month->id)->first()->success)?
                                    nl2br($task->poas->Where('month',$month->id)->first()->success):
                                '<font color="red">SIN REGISTRO</font>'!!}
                          </td>
                          <td>
                            {!! isset($task->poas->Where('month',$month->id)->first()->failure)?
                                    nl2br($task->poas->Where('month',$month->id)->first()->failure):
                                    '<font color="red">SIN REGISTRO</font>'!!}
                          </td>
                          <td>
                            {!! isset($task->poas->Where('month',$month->id)->first()->solution)?
                                    nl2br($task->poas->Where('month',$month->id)->first()->solution):
                        '<font color="red">SIN REGISTRO</font>'!!}
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="6" align="center">
                            <span class="text-danger">SIN REGISTROS</span>
                          </td>
                        </tr>
                      @endforelse
                    </table>
                  </td>
                </tr>
              </table>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
