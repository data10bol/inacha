@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>OPERACIÓN:
                  {{   $operation->action->goal->code }}.{{
                          $operation->action->code }}.{{
                          $operation->code }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $operation->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => true,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $operation->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => false,
                      'del' => false,
                      'editenable' => true
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
                             style="min-width: 210px; height: 350px; margin: 0 auto"></div>
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
                             style="min-width: 210px; height: 350px; margin: 0 auto"></div>
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
                             style="min-width: 210px; height: 350px; margin: 0 auto"></div>
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
                            {{ $operation->action->goal->code }}.{{
                                $operation->action->code }}.{{
                                $operation->code }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          GESTIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {{ $operation->action->year }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          DEPARTAMENTO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {{ $operation->action->department->name }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        @php
                          $pon = quantity_poa_rep($operation->id,'operation');
                        @endphp
                        <th class="align-top text-left">
                          ESTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-3">
                            @if(!$pon)
                              <span class="badge badge-success">VIGENTE</span> @else
                              <span class="badge badge-danger">REPROGRAMADA ({{ $pon }})</span> @endif
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          PILAR
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $operation->action->goal->doing->result->target->policy->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          META
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $operation->action->goal->doing->result->target->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          RESULTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $operation->action->goal->doing->result->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          ACCIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $operation->action->goal->doing->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          A. MEDIANO PLAZO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $operation->action->goal->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          A. CORTO PLAZO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $operation->action->definitions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          DESCRIPCIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $operation->definitions->last()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          PONDERACIÓN (%)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-4">
                            {{ $operation->definitions->last()->ponderation }} %
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          INDICADOR
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-4">
                            {{ $operation->definitions->last()->pointer }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          FÓRMULA
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-4">
                            {!! $operation->definitions->last()->describe !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          BASE (%)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-4">
                            {{ $operation->definitions->last()->base }} %
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          META (%)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-4">
                            {{ $operation->definitions->last()->aim }} %
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          RESULTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $operation->definitions->last()->validation !!}
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                  @php
                    $total = 0;
                    $totalexec = 0;
                    $poas = $operation->poas()->where('month',false)->get()->toarray();
                  @endphp
                  <td class="align-top text-justify" style="width: 390px">
                    <table class="table table-bordered table-striped " cellspacing="0" width="100%">
                      <tr>
                        <th colspan="{{3+$pon}}" class="align-center text-center">
                          CRONOGRAMA
                        </th> 
                      </tr>
                      <tr>
                        <th class="align-center text-center">
                          MES
                        </th>
                        <th class="align-center text-center">
                          ULTIMA PROGRAMACIÓN
                        </th>
                        <th class="align-center text-center">
                          EJECUTADO
                        </th>
                      </tr>
                      @foreach($months AS $month)
                        <tr>
                          <th class="align-top text-left" style="width: 50px">
                            {{ ucfirst ($month->name) }}
                          </th>
                          <td class="text-right">
                            <div class="col-sm-10 align-top ">
                              {{ $operation->poas()->where('state',false)->Where('month',false)->orderby('id','desc')->pluck('m'.$month->id)->first() }}%
                              @php $total += $operation->poas()->where('state',false)->Where('month',false)->orderby('id','desc')->pluck('m'.$month->id)->first() @endphp
                            </div>
                          </td>
                          <td class="text-right">
                            <div class="col-sm-10 align-top ">
                              @php
                                $m = 'm'.$month->id;
                                isset($operation->poas()->where('state',true)->Where('month',false)->orderby('id','desc')->first()->$m)?
                                    $totalexec += $operation->poas()->where('state',true)->Where('month',false)->orderby('id','desc')->first()->$m:
                                    null;
                              @endphp
                              {{ isset($operation->poas()->where('state',true)->Where('month',false)->orderby('id','desc')->first()->$m)?
                                  $operation->poas()->where('state',true)->Where('month',false)->orderby('id','desc')->first()->$m:
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
                            {!! isset($operation->poas->Where('month',$month->id)->first()->success)?
                                $operation->poas->Where('month',$month->id)->first()->success:
                                '<font color="red">SIN REGISTRO</font>'!!}
                          </td>
                          <td>
                            {!! isset($operation->poas->Where('month',$month->id)->first()->failure)?
                                    $operation->poas->Where('month',$month->id)->first()->failure:
                                    '<font color="red">SIN REGISTRO</font>'!!}
                          </td>
                          <td>
                            {!! isset($operation->poas->Where('month',$month->id)->first()->solution)?
                        $operation->poas->Where('month',$month->id)->first()->solution:
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

