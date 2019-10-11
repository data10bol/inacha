@extends('layouts.master')
@section('content')
<section class="content">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title"><b>REPORTE</b></h4>
      <div class="card-tools">
      </div>
    </div>
    <div class="card-body">
      <div class="filters m-b-45">
      </div>
      <div class="table-responsive">
            <table Width="100%" border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="27" align="center"><b>FORMULARIO N° 1</b></td>
                </tr>
                <tr>
                    <td colspan="27" align="center"><b>ARTICULACIÓN POA Y PRESUPUESTO ANUAL <br/>2019</b></td>
                </tr>
                <tr>
                    <td colspan="5"><b>ENTIDAD:</b></td>
                    <td colspan="22">159 - OFICINA TECNICA PARA EL FORTALECIMIENTO DE LA EMPRESA PÚBLICA</td>
                </tr>
                <tr>
                    <td colspan="5"><b>MISIÓN:</b></td>
                    <td colspan="22">{!! $config["mission"] !!}</td>
                </tr>
                <tr>
                    <td colspan="5"><b>VISIÓN:</b></td>
                    <td colspan="22">{!! $config["vision"] !!}</td>
                </tr>
                <tr>
                    <td colspan="4" align="center"><b>ESTRUCTURA PROGRAMATICA DEL PDES<br/>(A)</b></td>
                    <td colspan="2" rowspan="2" align="center"><b>ACCIÓN DE MEDIANO PLAZO<br/>(B)</b></td>
                    <td colspan="3" rowspan="2" align="center"><b>ACCIÓN DE CORTO PLAZO 2019<br/>(C )</b></td>
                    <td rowspan="2" align="center"><b>PRODUCTOS ESPERADOS<br/>(D)</b></td>
                    <td colspan="4" align="center"><b>INDICADOR<br/>(E )</b></td>
                    <td colspan="5" align="center"><b>ESTRUCTURA PROGRAMÁTICA</b></td>
                    <td colspan="4" align="center"><b>SECTOR ECONÓMICO</b></td>
                    <td colspan="4" align="center"><b>PROGRAMACION DE EJECUCION TRIMESTRAL (%)</b></td>
                </tr>
                <tr>
                     <td colspan="4" align="center"><b>PDES</b></td>
                     <td rowspan="2" align="center"><b>Descripción del Indicador</b></td>
                     <td rowspan="2" align="center"><b>Unidad de Medida</b></td>
                     <td align="center"><b>LINEA BASE</b></td>
                     <td align="center"><b>META</b></td>
                     <td rowspan="2" align="center"><b>Cod. PROG.</b></td>
                     <td rowspan="2" align="center"><b>Denominación</b></td>
                     <td colspan="3" align="center"><b>Presupuesto en Bs.</b></td>
                     <td colspan="3" rowspan="2" align="center"><b>Cod. Sector</b></td>
                     <td rowspan="2" align="center"><b>Denominación</b></td>
                     <td rowspan="2" align="center"><b>I</b></td>
                     <td rowspan="2" align="center"><b>II</b></td>
                     <td rowspan="2" align="center"><b>III</b></td>
                     <td rowspan="2" align="center"><b>IV</b></td>
                </tr>
                <tr>
                     <td align="center"><b>P</b></td>
                     <td align="center"><b>M</b></td>
                     <td align="center"><b>R</b></td>
                     <td align="center"><b>A</b></td>
                     <td align="center"><b>Cod. PEI</b></td>
                     <td align="center"><b>Denominación</b></td>
                     <td colspan="2" align="center"><b>Cod. POA</b></td>
                     <td align="center"><b>Denominación</b></td>
                     <td align="center"><b>(BIEN O SERVICIO)</b></td>
                     <td align="center"><b>2018</b></td>
                     <td align="center"><b>2019</b></td>
                     <td align="center"><b>Corriente</b></td>
                     <td align="center"><b>Inversión</b></td>
                     <td align="center"><b>Total</b></td>
                </tr>
                @forelse($goals as $goal)
                    @php
                        $actions = App\Action::Where('goal_id',$goal->id)
                                ->Where('year',activeyear())
                                ->OrderBy('code','ASC')
                                ->get();
                        $aux=0;
                    @endphp
                    <tr >
                        <td rowspan="{{ $actions->count() }}">{!! $goal->doing->result->target->policy->code !!}</td>
                        <td rowspan="{{ $actions->count() }}">{!! $goal->doing->result->target->code !!}</td>
                        <td rowspan="{{ $actions->count() }}">{!! $goal->doing->result->code !!}</td>
                        <td rowspan="{{ $actions->count() }}">{!! $goal->doing->code !!}</td>
                        <td rowspan="{{ $actions->count() }}">{!! $goal->code !!}</td>
                        <td rowspan="{{ $actions->count() }}">{!! $goal->description !!}</td>
                    @forelse($actions as $action)
                        @if($aux>0)
                            <tr>
                        @else
                        @php $aux++; @endphp
                        @endif
                        <td >{!! $action->goal->code !!}</td>
                        <td >{!! $action->code !!}</td>
                        <td >{!! $action->definitions->first()->description !!}</td>
                        <td >{!! $action->definitions->first()->describe !!}</td>
                        <td >{!! $action->definitions->first()->pointer !!}</td>
                        <td >%</td>
                        <td >{{ $action->definitions->first()->base }}%</td>
                        <td >{{ $action->definitions->first()->aim }}%</td>
                        <td >{!! $action->structures->first()->code !!}</td>
                        <td >{!! $action->structures->first()->name !!}</td>
                        <td >390066,0</td>
                        <td> </td>
                        <td >390066,0</td>
                        <td >18</td>
                        <td >2</td>
                        <td >1</td>
                        <td >PROGRAMAS MULTI-SECTORIALES</td>
                        <td >25%</td>
                        <td >17%</td>
                        <td >29%</td>
                        <td >29%</td>

                    @empty
                    <tr>
                        <td colspan="27">SIN DATOS</td>
                    </tr>
                    @endforelse
                    </tr>
                @empty
                <tr>
                    <td colspan="27">SIN DATOS</td>
                </tr>
                @endforelse
                <tr >
                     <td colspan="15"></td>
                     <td colspan="3" >TOTALES</td>
                     <td >7153996,0</td>
                     <td colspan="9"></td>
                </tr>
                <tr>
                     <td colspan="18"></td>
                     <td >7.153.996,000</td>
                     <td colspan="9"></td>
                </tr>
            </table>
      </div>
      <div class="pagination-wrapper">
      </div>
    </div>
  </div>
</section>
@endsection
