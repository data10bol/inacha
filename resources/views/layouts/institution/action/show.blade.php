@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>ACCIÓN DE CORTO PLAZO:
                  {{ $action->goal->doing->code }}.{{
                        $action->goal->code }}.{{
                        $action->code }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $action->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => true,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $action->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => true,
                      'del' => true,
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
                          <div class="col-sm-3">{{ $action->goal->doing->code }}.{{
                                                    $action->goal->code }}.{{
                                                    $action->code }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left" style="width: 120px">
                          GESTIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-3">
                            {{ $action->year }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left" style="width: 120px">
                          DEPARTAMENTO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {{ $action->department->name }}
                          </div>
                        </td>
                      </tr>
                      @php
                          $state = quantity_poa_rep($action->id, 'action');
                      @endphp
                      <tr>
                        <th class="align-top text-left">
                          ESTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-3">
                            @if(!$state)
                              <span class="badge badge-success">VIGENTE</span> @else
                              <span class="badge badge-danger">REPROGRAMADA ({{quantity_poa_rep($action->id, 'action')}})</span> @endif
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          PILAR
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $action->goal->doing->result->target->policy->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          META
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $action->goal->doing->result->target->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          RESULTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $action->goal->doing->result->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          ACCIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $action->goal->doing->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          A. MEDIANO PLAZO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $action->goal->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          A. CORTO PLAZO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $action->definitions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          BIEN / SERVICIO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $action->definitions->first()->describe !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          PONDERACIÓN (%)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-4">
                            {{ $action->definitions->first()->ponderation }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          INDICADOR
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {{ $action->definitions->first()->pointer }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          FÓRMULA
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $action->definitions->first()->measure !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          BASE (%)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-4">
                            {{ $action->definitions->first()->base }}%
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          META (%)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-4">
                            {{ $action->definitions->first()->aim }}%
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          RESULTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $action->definitions->first()->validation !!}
                          </div>
                        </td>
                      </tr>
                      <!--tr>
                        <th class="align-top text-left">
                          COD. PROG.
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            @php
                            //{!! $action->structures->first()->code !!}
                            @endphp
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          DENOMINACIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            @php
                              //{!! $action->structures->first()->name !!}
                            @endphp
                          </div>
                        </td>
                      </tr >
                      <tr>
                        <th class="align-top text-left">
                          PRESUPUESTO CORRIENTE (Bs.)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            CALCULAR
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          PRESUPUESTO INVERSIÓN (Bs.)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            CALCULAR
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          TOTAL (Bs.)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">totalmonth
                            CALCULAR
                          </div>
                        </td>
                      </tr-->
                    </table>
                  </td>
                  @php
                  $totales = array();
                  for($j = 0 ; $j < $state+1;$j++){
                    $totales[$j]= 0;
                  }
                  $poas = $action->poas()->where('state',false)->get()->toarray();///sacamos todas las reformulaciones que tenga el poa
                  ///asignamos ceros a los sumadores
                  $total = 0;
                  $totalr = 0;
                  @endphp
                  <td class="align-top" style="width: 320px" bgcolor="#dc3545">
                    <table class="table table-bordered table-striped " cellspacing="0">
                      <tr>
                        <td colspan=" @if($state) {{$state+2}} @else 2 @endif" class="align-center text-center">
                          <b>CRONOGRAMA</b>
                        </td>
                      </tr>
                      @if ($state)
                      <tr>
                        <th>MES</th>                        
                        <th>PROG.</th>
                        @for ($i = 0; $i < $state ; $i++)
                          <th>REPR. ({{strtoupper($months[($poas[$i+1]['value'])-1]->name)}})</th>
                        @endfor
                      </tr>
                      @endif
                      @foreach($months AS $month)
                        <tr>
                          <th class="align-top text-left">
                            {{ ucfirst ($month->name) }}
                          </th>
                          @php
                                $totalmonth = 0;
                                $m = 'm'.$month->id;
                          @endphp
                          @for ($i = 0; $i < $state+1; $i++)
                          <td class="text-right">
                            <div class="col-sm-10 align-top ">
                              {{$poas[$i][$m]}}%
                            </div>
                            @php
                                $totales[$i] += $poas[$i][$m]; 
                            @endphp
                          </td>
                          @endfor
                        </tr>
                      @endforeach
                      <tr>
                        <td class="align-top text-center">
                          <b>TOTAL</b>
                        </td>
                        @for ($i = 0; $i < $state+1; $i++)
                        <td>
                          <div class="col-sm-10 align-top text-right">
                            <b>{{ number_format($totales[$i], 2) }}%</b>
                          </div>
                        </td>
                        @endfor
                      </tr>
                    </table>
                    <br>
                    <table width="100%" class="table table-bordered table-striped " cellspacing="0">
                      <tr>
                        <td colspan="2" class="align-center text-center">
                          <b>OPERACIONES</b>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-center text-center">COD</th>
                        <th class="align-center text-center">DESC</th>
                      </tr>
                      @forelse($ops AS $op)
                        <tr>
                          <td class="align-top text-left">
                            @php
                              $pon = $op->definitions->last()->ponderation;
                            @endphp
                            <a href="/institution/operation/{{ Hashids::encode($op->id)}}">
                              {{ $op->action->goal->code }}.{{
                                  $op->action->code }}.{{
                                  $op->code }}
                                @if($pon==0)
                                  <span class="badge badge-danger">Anulada</span>
                                @endif
                            </a>
                          </td>
                          <td class="text-left">
                            {!! substr($op->definitions->first()->description, 0, 200) !!}
                          </td>
                        </tr>
                      @empty
                        {!! emptyrecord("2") !!}
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
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
