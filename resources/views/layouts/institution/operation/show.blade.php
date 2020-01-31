@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                @php
                  $pon = $operation->definitions->last()->ponderation ;
                @endphp
                <b>OPERACIÓN: {{ $operation->action->goal->code }}.{{
                                  $operation->action->code }}.{{
                                  $operation->code }}</b>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $operation->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => true,
                      'urlword' => "#",
                      'pdf' => true,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                @if ($pon >0)
                  @component('layouts.partials.hcontrol',[
                    'id' => $operation->id,
                    'url1' => $data["url1"],
                    'url2' => $data["url2"],
                    'type' => 'show',
                    'add' => true,
                    'del' => true,
                  ])
                  @endcomponent
                @else
                @component('layouts.partials.hcontrol',[
                  'id' => $operation->id,
                  'url1' => $data["url1"],
                  'url2' => $data["url2"],
                  'type' => 'show',
                  'add' => false,
                  'del' => false,
              ])
              @endcomponent
                @endif
                  

                </div>
                <!-- /.card-header -->
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
                        DEPARTAMENTO A.C.P.
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12">
                          {{ $operation->action->department->name }}
                        </div>
                      </td>
                    </tr>
                    @php
                        $state = cheking_poa_rep($operation->id, 'operation');
                    @endphp
                    <tr>
                      <th class="align-top text-left">
                        ESTADO
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-3">
                          @if(!$state)
                            <span class="badge badge-success">VIGENTE</span> 
                            @else
                              @if($pon != 0)
                                <span class="badge badge-danger">REPROGRAMADA ({{quantity_poa_rep($operation->id, 'operation')}})</span>
                              @else
                              <span class="badge badge-danger">ANULADA</span>
                              @endif
                            @endif
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
                    @php
                        $des_es = chek_definition($operation->id, 'operation', 'description');
                    @endphp
                    <tr>
                      <th class="align-top text-left">
                        DESCRIPCIÓN
                        @if ($des_es)
                          @php
                            $retur = get_definition($operation->id, 'operation', 'description');
                            $tex = '';
                            for($i = 0 ; $i < count($retur);$i++){
                              $tex .= '<strong>'.$months[$retur[$i]['in']-1]->name."</strong><br> ".$retur[$i]['description']."<hr>";
                            }
                          @endphp
                          <i tabindex="0" class="fa fa-history text-info" role="button" data-toggle="popover" data-trigger="focus" title="Reprogramaciones" data-content="{{$tex}}"></i>
                        @endif
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12">
                          {!! $operation->definitions->last()->description !!}
                        </div>
                      </td>
                    </tr>
                    @php
                        $des_es = chek_definition($operation->id, 'operation', 'ponderation');
                    @endphp
                    <tr>
                      <th class="align-top text-left">
                        PONDERACIÓN (%)
                        @if ($des_es)
                          @php
                            $retur = get_definition($operation->id, 'operation', 'ponderation');
                            $tex = '';
                            for($i = 0 ; $i < count($retur);$i++){
                              $tex .= '<strong>'.$months[$retur[$i]['in']-1]->name."</strong><br> ".$retur[$i]['ponderation']."<hr>";
                            }
                          @endphp
                          <i tabindex="0" class="fa fa-history text-info" role="button" data-toggle="popover" data-trigger="focus" title="Reprogramaciones" data-content="{{$tex}}"></i>
                        @endif
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12"> 
                          
                          {{ $pon }} % 
                          
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th class="align-top text-left">
                        DEPARTAMENTO OPERATIVO
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12">
                          {!! (isset($operation->definitions->last()->department))? $operation->definitions->last()->department->name : null!!}
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th class="align-top text-left">
                        PONDERACIÓN DE DEPARTAMENTO
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12">
                          {!! $operation->definitions->last()->dep_ponderation !!} %
                        </div>
                      </td>
                    </tr>
                    @php
                        $des_es = chek_definition($operation->id, 'operation', 'pointer');
                    @endphp
                    <tr>
                      <th class="align-top text-left">
                        INDICADOR
                        @if ($des_es)
                        @php
                          $retur = get_definition($operation->id, 'operation', 'pointer');
                          $tex = '';
                          for($i = 0 ; $i < count($retur);$i++){
                            $tex .= '<strong>'.$months[$retur[$i]['in']-1]->name."</strong><br> ".$retur[$i]['pointer']."<hr>";
                          }
                        @endphp
                        <i tabindex="0" class="fa fa-history text-info" role="button" data-toggle="popover" data-trigger="focus" title="Reprogramaciones" data-content="{{$tex}}"></i>
                      @endif
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12">
                          {{ $operation->definitions->last()->pointer }}
                        </div>
                      </td>
                    </tr>
                    @php
                        $des_es = chek_definition($operation->id, 'operation', 'describe');
                    @endphp
                    <tr>
                      <th class="align-top text-left">
                        FÓRMULA
                        @if ($des_es)
                          @php
                            $retur = get_definition($operation->id, 'operation', 'describe');
                            $tex = '';
                            for($i = 0 ; $i < count($retur);$i++){
                              $tex .= '<strong>'.$months[$retur[$i]['in']-1]->name."</strong><br> ".$retur[$i]['describe']."<hr>";
                            }
                          @endphp
                          <i tabindex="0" class="fa fa-history text-info" role="button" data-toggle="popover" data-trigger="focus" title="Reprogramaciones" data-content="{{$tex}}"></i>
                        @endif
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12">
                          {!! $operation->definitions->last()->describe !!}
                        </div>
                      </td>
                    </tr>
                    @php
                        $des_es = chek_definition($operation->id, 'operation', 'base');
                    @endphp
                    <tr>
                      <th class="align-top text-left">
                        BASE (%)
                        @if ($des_es)
                          @php
                            $retur = get_definition($operation->id, 'operation', 'base');
                            $tex = '';
                            for($i = 0 ; $i < count($retur);$i++){
                              $tex .= '<strong>'.$months[$retur[$i]['in']-1]->name."</strong><br> ".$retur[$i]['base']."<hr>";
                            }
                          @endphp
                          <i tabindex="0" class="fa fa-history text-info" role="button" data-toggle="popover" data-trigger="focus" title="Reprogramaciones" data-content="{{$tex}}"></i>
                        @endif
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12">
                          {{ $operation->definitions->first()->base }} %
                        </div>
                      </td>
                    </tr>
                    @php
                        $des_es = chek_definition($operation->id, 'operation', 'aim');
                    @endphp
                    <tr>
                      <th class="align-top text-left">
                        META (%)
                        @if ($des_es)
                          @php
                            $retur = get_definition($operation->id, 'operation', 'aim');
                            $tex = '';
                            for($i = 0 ; $i < count($retur);$i++){
                              $tex .= '<strong>'.$months[$retur[$i]['in']-1]->name."</strong><br> ".$retur[$i]['aim']."<hr>";
                            }
                          @endphp
                          <i tabindex="0" class="fa fa-history text-info" role="button" data-toggle="popover" data-trigger="focus" title="Reprogramaciones" data-content="{{$tex}}"></i>
                        @endif
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12">
                          {{ $operation->definitions->first()->aim }} %
                        </div>
                      </td>
                    </tr>
                    @php
                        $des_es = chek_definition($operation->id, 'operation', 'validation');
                    @endphp
                    <tr>
                      <th class="align-top text-left">
                        RESULTADO
                        @if ($des_es)
                          @php
                            $retur = get_definition($operation->id, 'operation', 'validation');
                            $tex = '';
                            for($i = 0 ; $i < count($retur);$i++){
                              $tex .= '<strong>'.$months[$retur[$i]['in']-1]->name."</strong><br> ".$retur[$i]['validation']."<hr>";
                            }
                          @endphp
                          <i tabindex="0" class="fa fa-history text-info" role="button" data-toggle="popover" data-trigger="focus" title="Reprogramaciones" data-content="{{$tex}}"></i>
                        @endif
                      </th>
                      <td class="align-top text-justify">
                        <div class="col-sm-12">
                          {!! $operation->definitions->first()->validation !!}
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
                @php
                  $total = 0;
                  
                  $total_r = 0;
                @endphp
                <td class="align-top" style="width: 320px" bgcolor="#dc3545">
                  <table class="table table-bordered table-striped" cellspacing="0">
                    <tr>
                      <th colspan="@if($state) 3 @else 2 @endif" class="align-center text-center">
                        CRONOGRAMA
                      </th>
                    </tr>
                    @if ($state)
                      <tr>
                        <th>MES</th>
                        <th>PROG.</th>
                        <th>REPRG.</th>
                      </tr>
                    @endif
                    @foreach($months AS $month)
                      <tr>
                        <th class="align-top text-left">
                          {{ ucfirst ($month->name) }}
                        </th>
                        <td class="text-right">
                          <div class="col-sm-10 align-top ">
                            {{ $operation->poas->pluck('m'.$month->id)->first() }}%
                            @php $total += $operation->poas->pluck('m'.$month->id)->first() @endphp
                          </div>
                        </td>
                        @if ($state)
                          <td>
                            {{$operation->poas->where('state', 0)->where('value', $operation->action()->pluck('current')->first())->pluck('m'.$month->id)->first() }} %
                            @php $total_r += $operation->poas->where('state', 0)->where('value', $operation->action()->pluck('current')->first())->pluck('m'.$month->id)->first()  @endphp
                          </td>
                        @endif
                      </tr>
                    @endforeach
                    <tr>
                      <td class="align-top text-center">
                        <b>TOTAL</b>
                      </td>
                      <td>
                        <div class="col-sm-10 align-top text-right">
                          <b>{{ $total }}%</b>
                        </div>
                      </td>
                      @if ($state)
                        <td>
                            <div class="col-sm-10 align-top text-right">
                          <b>{{ $total_r }}%</b>
                            </div>
                        </td>
                      @endif
                    </tr>
                  </table>
                  <br>
                  <table width="100%" class="table table-bordered table-striped " cellspacing="0">
                    <tr>
                      <td colspan="2" class="align-center text-center">
                        <b>TAREAS</b>
                      </td>
                    </tr>
                    <tr>
                      <th class="align-center text-center">COD</th>
                      <th class="align-center text-center">DESC</th>
                    </tr>
                    @forelse($tks AS $tk)
                      <tr>
                        <td class="align-top text-left">
                          <a href="/institution/task/{{ Hashids::encode($tk->id)}}">
                            {{ $tk->operation->action->goal->code }}.{{
                                                                        $tk->operation->action->code }}.{{
                                                                        $tk->operation->code }}.{{
                                                                        $tk->code }}
                          </a>
                          @php
                              
                          @endphp
                          @if (!$tk->status)
                            <span class="badge badge-danger">Borrado</span> 
                          @endif
                        </td>
                        <td class="text-left">
                          {!! substr($tk->definitions->first()->description, 0, 200) !!}
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

@section('scripts')
<script>
    $(document).ready(function(){
      $('[data-trigger="focus"]').popover({ 
        html:true ,
          trigger: 'focus'
        });  
    });
</script>
@endsection