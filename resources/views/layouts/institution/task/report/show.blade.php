@component('layouts.partials.pdfshowheader',[
    'title' => "TAREA",
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
                    {{  $task->operation->action->goal->code }}.{{
                        $task->operation->action->code }}.{{
                        $task->operation->code }}.{{
                        $task->code }}
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
                {{ $task->operation->action->year }}
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
                {{ $task->operation->action->department->name }}
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
                @if(!$task->operation->action->goal->configuration->reconfigure)
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
                {!! $task->operation->action->goal->doing->result->target->policy->descriptions->first()->description !!}
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
                {!! $task->operation->action->goal->doing->result->target->descriptions->first()->description !!}
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
                {!! $task->operation->action->goal->doing->result->descriptions->first()->description !!}
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
                {!! $task->operation->action->goal->doing->descriptions->first()->description !!}
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
                {!! $task->operation->action->goal->description !!}
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
                {!! $task->operation->action->definitions->first()->description !!}
                </span>
  </td>
</tr>
<tr>
  <th valign="top" align="left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                OPERACIÓN
              </span>
  </th>
  <td valign="top" align="justify">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $task->operation->definitions->first()->description !!}
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
                {!! $task->definitions->first()->description !!}
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
                {!! $task->operation->definitions->first()->validation !!}
                </span>
  </td>
</tr>
</table><br>
@php
  $total = 0;
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
                        {!! number_format((float)$task
                        ->poas
                        ->pluck('m'.$month->id)
                        ->first(),2,'.',' ') !!}
                      </span>
      </td>
      @php $total += $task->poas->pluck('m'.$month->id)->first() @endphp
    @endforeach
    <td valign="center" align="center">
                      <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
                      <b>{{ $total }}%</b>
                      </span>
    </td>
  </tr>
</table><br>
</p>
</main>
</body>
