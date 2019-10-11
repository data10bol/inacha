@component('layouts.partials.pdfshowheader',[
    'title' => "PILAR",
    ])
@endcomponent
        <tr>
          <th class="align-top text-left" style="width: 100px">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      PERÍODO
                    </span>
          </th>
          <td class="align-top text-justify">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {{ $policy->period->start
                          }}-{{ $policy->period->finish }}
                    </span>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      CÓDIGO
                    </span>
          </th>
          <td class="align-top text-justify">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {{ $policy->code }}
                    </span>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    DESCRIPCIÓN
                    </span>
          </th>
          <td class="align-top text-justify">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {!! $policy->descriptions->pluck('description')->first() !!}
                    </span>
          </td>
        </tr>
      </table>
    </p>
  </main>
</body>

