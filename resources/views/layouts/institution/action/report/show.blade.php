@component('layouts.partials.pdfshowheader',[
    'title' => "ACCIÓN DE MEDIANO PLAZO",
    ])
@endcomponent
<tr>
  <th valign="top" align="left" style="width: 120px">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                CÓDIGO
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{$action->goal->doing->code }}.{{
                                          $action->goal->code }}.{{
                                          $action->code }}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left" style="width: 120px">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                GESTIÓN
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ $action->year }}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left" style="width: 120px">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                DEPARTAMENTO
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ $action->department->name }}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                ESTADO
              </span>
  </th>
  @php
  $state = cheking_poa_rep($action->id, 'action');
  
@endphp
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                @if(!$state)
                    <span class="badge badge-success">VIGENTE</span> @else
                    <span class="badge badge-danger">REPROGRAMADA</span> @endif
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                PILAR
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->goal->doing->result->target->policy->descriptions->first()->description !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                META
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->goal->doing->result->target->descriptions->first()->description !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                RESULTADO
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->goal->doing->result->descriptions->first()->description !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              ACCIÓN
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->goal->doing->descriptions->first()->description !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              A. MEDIANO PLAZO
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->goal->description !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              A. CORTO PLAZO
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->definitions->first()->description !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              BIEN / SERVICIO
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->definitions->first()->describe !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              PONDERACIÓN (%)
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ $action->definitions->first()->ponderation }}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              INDICADOR
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ $action->definitions->first()->pointer }}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              FÓRMULA
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->definitions->first()->measure !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                BASE (%)
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ $action->definitions->first()->base }}%
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              META (%)
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ $action->definitions->first()->aim }}%
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              RESULTADO
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->definitions->first()->validation !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              COD. PROG.
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->structures->first()->code !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              DENOMINACIÓN
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $action->structures->first()->name !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              PRESUPUESTO CORRIENTE (Bs.)
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                CALCULAR
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              PRESUPUESTO INVERSIÓN (Bs.)
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                CALCULAR
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              TOTAL (Bs.)
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                CALCULAR
                </span>
  </td>
</tr>
</table><br>
@php
  $total = 0;
  $state = cheking_poa_rep($action->id, 'action');
  //dd($state);
@endphp
@if($state)
<table width="100%"
      border="1"
      cellpadding="0"
      cellspacing="0">
  <tr valign="center" align="center">
    <th colspan="14" valign="center" align="center">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    CRONOGRAMA
                    </span>
    </th>
  </tr>
  <tr>
    <th>
      
    </th>
    @foreach($months AS $month)
      <th valign="center" align="center">
                      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                      {{ substr(ucfirst ($month->name), 0, 3) }}
                      </span>
      </th>
    @endforeach
    <th valign="center" align="center">
                    <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                      TOT
                    </span>
    </th>
  </tr>

  <tr>
    <td><span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>Prog.</span></td>
    @foreach($months AS $month)
      <td valign="center" align="center">
                      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                        @php
                          $totalmonth = 0;
                          $m = 'm'.$month->id;
/*                          $poa = App\Poa::Select('m'.$month->id)
                                          ->WhereIn('poa_id',$ids)
                                          ->Where('poa_type','App\Operation')
                                          ->Pluck('m'.$month->id)
                                          ->all();
                          for($i=0;$i<count($ids);$i++)
                              $totalmonth += $poa[$i]/100*$ponderation[$i]; */
                        $totalmonth = $action->poas()->
                                            Where('state',false)->
                                            Where('month', false)->

                                            first()->$m;
                        @endphp
                        {{ number_format((float)$totalmonth,2,'.',' ') }}%
                        @php $total += $totalmonth; @endphp
                      </span>
      </td>
    @endforeach
    <td valign="center" align="center">
                      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                      <b>{{ $total }}%</b>
                      </span>
    </td>
  </tr>
@php
    $total = 0;
@endphp

  <tr>
      <td>
        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
          Repr.
        </span>
      </td>
      @foreach($months AS $month)
      <td valign="center" align="center">


          <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
              @php
                $totalmonth = 0;
                $m = 'm'.$month->id;
                
              $totalmonth = $action->poas()->
                                    Where('state',false)->
                                    Where('month', false)->
                                    orderby('id','desc')->
                                    
                                    first()->$m;
              @endphp
              {{ number_format((float)$totalmonth,2,'.',' ') }}%
              @php $total += $totalmonth; @endphp

            </span>


      </td>
      @endforeach
      <td valign="center" align="center">
                        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                        <b>{{ $total }}%</b>
                        </span>
      </td>
    </tr>
  
</table>
@else
<table width="100%"
      border="1"
      cellpadding="0"
      cellspacing="0">
  <tr valign="center" align="center">
    <th colspan="13" valign="center" align="center">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    CRONOGRAMA
                    </span>
    </th>
  </tr>
  <tr>
    @foreach($months AS $month)
      <th valign="center" align="center">
                      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                      {{ substr(ucfirst ($month->name), 0, 3) }}
                      </span>
      </th>
    @endforeach
    <th valign="center" align="center">
                    <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                      TOT
                    </span>
    </th>
  </tr>
  <tr>
    @foreach($months AS $month)
      <td valign="center" align="center">
                      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                        @php
                          $totalmonth = 0;
                          $m = 'm'.$month->id;
/*                          $poa = App\Poa::Select('m'.$month->id)
                                          ->WhereIn('poa_id',$ids)
                                          ->Where('poa_type','App\Operation')
                                          ->Pluck('m'.$month->id)
                                          ->all();
                          for($i=0;$i<count($ids);$i++)
                              $totalmonth += $poa[$i]/100*$ponderation[$i]; */
                        $totalmonth = $action->poas()->
                                            Where('state',false)->
                                            Where('month', false)->
                                            first()->$m;
                        @endphp
                        {{ number_format((float)$totalmonth,2,'.',' ') }}%
                        @php $total += $totalmonth; @endphp
                      </span>
      </td>
    @endforeach
    <td valign="center" align="center">
                      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                      <b>{{ $total }}%</b>
                      </span>
    </td>
  </tr>
</table>
@endif
<br>
<table width="100%"
       border="1"
       cellpadding="0"
       cellspacing="0">
  <tr>
    <th colspan="2" valign="center" align="center">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    OPERACIONES
                    </span>
    </th>
  </tr>
  <tr>
    <th valign="center" align="center">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      CODIGO
                    </span>
    </th>
    <th valign="center" align="center">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      DESCRIPCIÓN
                    </span>
    </th>
  </tr>
  @forelse($ops AS $op)
    <tr>
      <td valign="top" align="left">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        {{ $op->action->goal->code }}.{{
                            $op->action->code }}.{{
                            $op->code }}
                            @php
                                $pon = $op->definitions->last()->ponderation;
                            @endphp
                            @if($pon==0)
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>Anulada</span>
                            @endif
                      </span>
      </td>
      <td avalign="top" align="justify">
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
        &nbsp; {!! substr($op->definitions->first()->description, 0, 200) !!}
        </span>
      </td>
    </tr>
  @empty
    {!! emptyrecord("2") !!}
  @endforelse
</table>
</p>
</main>
</body>
