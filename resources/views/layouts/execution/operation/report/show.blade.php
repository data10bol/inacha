@component('layouts.partials.pdfshowheader',[
    'title' => "EJECUCIÓN DE LA OPERACIÓN  A ".
    strtoupper($months->Where('id',activemonth())->first()->name).' DE '.
    activeyear(),
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
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                ESTADO
              </span>
  </th>
  <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                @if(!$operation->action->goal->configuration->reconfigure)
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
                {!! $operation->definitions->last()->description !!}
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
                {{ $operation->definitions->last()->ponderation }}
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
                {{ $operation->definitions->last()->pointer }}
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
                {!! $operation->definitions->last()->describe !!}
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
                {{ $operation->definitions->last()->base }}%
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
                {{ $operation->definitions->last()->aim }}%
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
                {!! $operation->definitions->last()->validation !!}
                </span>
  </td>
</tr>
</table><br>
@php
  $total = 0;
  $totalexec = 0;
@endphp
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
                        {!! 
                            number_format((float)
                            $operation->poas()->where('state',false)->where('month',false)->orderby('id','desc')->pluck('m'.$month->id)->first(),

                            2,'.',' ') 
                        !!}
                      </span>
                      
      </td>
      @php $total += $operation->poas()->where('state',false)->where('month',false)->orderby('id','desc')->pluck('m'.$month->id)->first(); @endphp
    @endforeach
    <td valign="center" align="center">
        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
        <b>{{ number_format((float)$total,2,'.',' ') }}%</b>
        </span>
    </td>
  </tr>
  <tr>
    @foreach($months AS $month)
      @php
        $m = 'm'.$month->id;
        isset($operation->poas->Where('state',true)->Where('month',false)->last()->$m)?
            $totalexec += $operation->poas->Where('state',true)->Where('month',false)->last()->$m:
            null;
      @endphp
      <td valign="center" align="center">
                      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                   {{ isset($operation->poas->Where('state',true)->Where('month',false)->last()->$m)?
          number_format((float)$operation->poas->Where('state',true)->Where('month',false)->last()->$m,2,'.',' '):
          '0.00'}}
                      </span>
      </td>
      @php $total += $operation->poas->pluck('m'.$month->id)->first() @endphp
    @endforeach
    <td valign="center" align="center">
                      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                      <b>{{ number_format((float)$totalexec,2,'.',' ') }}%</b>
                      </span>
    </td>
  </tr>

</table><br>
<table width="100%"
       border="1"
       cellpadding="0"
       cellspacing="0">
  <tr>
    <th colspan="4" valign="center" align="center">
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
        REPORTE
        </span>
    </th>
  </tr>
  <tr>
    <th>
      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
      MES
      </span>
    </th>
    <th>
      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
      LOGRO(S)
      </span>
    </th>
    <th>
      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
      PROBLEMA(S)
      </span>
    </th>
    <th>
      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
      SOLUCION(ES)
      </span>
    </th>
  </tr>
  @forelse($months AS $month)
    <tr>
      <td>
        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
        {{ ucfirst ($month->name) }}
        </span>
      </td>
      <td>
        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
        {!! isset($operation->poas->Where('month',$month->id)->first()->success)?
            $operation->poas->Where('month',$month->id)->first()->success:
            '<font color="red">SIN REGISTRO</font>'!!}
        </span>
      </td>
      <td>
        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
        {!! isset($operation->poas->Where('month',$month->id)->first()->failure)?
                $operation->poas->Where('month',$month->id)->first()->failure:
                '<font color="red">SIN REGISTRO</font>'!!}
        </span>
      </td>
      <td>
        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
        {!! isset($operation->poas->Where('month',$month->id)->first()->solution)?
    $operation->poas->Where('month',$month->id)->first()->solution:
    '<font color="red">SIN REGISTRO</font>'!!}
        </span>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="6" align="center">
        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
        <span class="text-danger">SIN REGISTROS</span>
        </span>
      </td>
    </tr>
  @endforelse
</table>
</p>
</main>
</body>
