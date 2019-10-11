@component('layouts.partials.pdfshowheader',[
    'title' => "ACCIÓN DE MEDIANO PLAZO",
    ])
@endcomponent
        <tr>
          <th valign="top" align="left" style="width: 100px">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              PERIODO
            </span>
          </th>
          <td valign="top" align="justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
            {{ $goal->configuration->period->start }} - {{
            $goal->configuration->period->finish }}
            </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              CÓDIGO
            </span>
          </th>
          <td valign="top" align="justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
            {{ $goal->doing->result->target->policy->code
            }}.{{ $goal->doing->result->target->code
            }}.{{ $goal->doing->result->id
            }}.{{ $goal->doing->code
            }}.{{ $goal->code }}
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
            {!! $goal->doing->result->target->policy->descriptions->first()->description !!}
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
            {!! $goal->doing->result->target->descriptions->first()->description !!}
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
            {!! $goal->doing->result->descriptions->first()->description !!}
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
            {!! $goal->doing->descriptions->first()->description !!}
            </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              ACCIÓN DE MEDIANO PLAZO
            </span>
          </th>
          <td valign="top" align="justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
            {!! $goal->description !!}
            </span>
          </td>
        </tr>
      </table>
    </p>
  </main>
</body>
