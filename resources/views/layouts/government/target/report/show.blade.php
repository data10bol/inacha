@component('layouts.partials.pdfshowheader',[
    'title' => "META",
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
                        {{ $target->policy->period->start
                            }}-{{ $target->policy->period->finish }}
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
                        {{ $target->policy->code }}.{{ $target->code }}
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
                        {!! $target->policy->descriptions->pluck('description')->first() !!}
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
                        {!! $target->descriptions->pluck('description')->first() !!}
                      </span>
          </td>
        </tr>
      </table>
    </p>
  </main>
</body>
