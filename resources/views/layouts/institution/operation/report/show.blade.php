@component('layouts.partials.pdfshowheader',[
    'title' => "OPERACIÓN",
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
                    {{ $operation->action->goal->code }}.{{
                                      $operation->action->code }}.{{
                                      $operation->code }}
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
                {{ $operation->action->year }}
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
                {{ $operation->action->department->name }}
                </span>
  </td>
</tr>
@php
    $pon = $operation->definitions->last()->ponderation;
    $state = cheking_poa_rep($operation->id, 'operation');
    
@endphp
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                ESTADO
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                @if(!$state)
                    <span class="badge badge-success">VIGENTE</span> 
                @else
                  @if($pon>0)
                  @php
                      $cant = quantity_poa_rep($operation->id, 'operation');
                  @endphp
                    <span class="badge badge-danger">REPROGRAMADA (v. {{$cant}})</span> 
                  @else
                    <span class="badge badge-danger">ANULADA</span> 
                  @endif
                @endif
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
                {!! $operation->action->goal->doing->result->target->policy->descriptions->first()->description !!}
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
                {!! $operation->action->goal->doing->result->target->descriptions->first()->description !!}
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
                {!! $operation->action->goal->doing->result->descriptions->first()->description !!}
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
                {!! $operation->action->goal->doing->descriptions->first()->description !!}
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
                {!! $operation->action->goal->description !!}
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
                {!! $operation->action->definitions->first()->description !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                DESCRIPCIÓN
              </span>
  </th>
  <td valign="top" align="justify">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $operation->definitions->first()->description !!}
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
                {{ $pon }}
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
                {{ $operation->definitions->first()->pointer }}
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
                {!! $operation->definitions->first()->describe !!}
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
                {{ $operation->definitions->first()->base }}%
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
                {{ $operation->definitions->first()->aim }}%
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
                {!! $operation->definitions->first()->validation !!}
                </span>
  </td>
</tr>
</table><br>
@php
  $total = 0;
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
    <td>
      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
        Prog.
      </span>
    </td>
    @foreach($months AS $month)
      <td valign="center" align="center">
        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
          {!! number_format((float)$operation
          ->poas
          ->pluck('m'.$month->id)
          ->first(),2,'.',' ') !!}
        </span>
      </td>
      @php $total += $operation->poas->pluck('m'.$month->id)->first() @endphp
    @endforeach
    <td valign="center" align="center">
      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
      <b>{{ $total }}%</b>
      </span>
    </td>
  </tr>
  @php
      $total =0;
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
            {!! number_format((float)$operation->poas->
              Where('value',activeReprogId())->
              Where('state',false)->
              pluck('m'.$month->id)->
              first(),2,'.',' ') 
            !!}
          </span>
        </td>
        @php $total += $operation->poas->Where('value',activeReprogId())->Where('state',false)->pluck('m'.$month->id)->first() @endphp
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
                        {!! number_format((float)$operation
                        ->poas
                        ->pluck('m'.$month->id)
                        ->first(),2,'.',' ') !!}
                      </span>
      </td>
      @php $total += $operation->poas->pluck('m'.$month->id)->first() @endphp
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
                    TAREAS
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
  @forelse($tks AS $tk)
    <tr>
      <td valign="top" align="left">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        {{ $tk->operation->action->goal->code }}.{{
                          $tk->operation->action->code }}.{{
                          $tk->operation->code }}.{{
                              $tk->code }}
                      </span>
      </td>
      <td avalign="top" align="center">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {!! substr($tk->definitions->first()->description, 0, 200) !!}
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
