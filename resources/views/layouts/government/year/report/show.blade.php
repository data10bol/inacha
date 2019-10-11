@component('layouts.partials.pdfshowheader',[
    'title' => "GESTIÓN",
    ])
@endcomponent
        <tr>
          <th valign="top" align="left" style="width: 100px">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    ESTADO
                    </span>
          </th>
          <td valign="top" align="justify">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      @statusvar($year->status)
                    </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left" style="width: 100px">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    ACTUAL
                    </span>
          </th>
          <td valign="top" align="justify">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      @statusvar($year->current)
                    </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    PERIODO
                    </span>
          </th>
          <td valign="top" align="justify">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {{ $year->period->start }} - {{ $year->period->finish }}
                    </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    GESTIÓN
                    </span>
          </th>
          <td valign="top" align="justify">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {{ $year->name }}
                    </span>
          </td>
        </tr>
      </table>
    </p>
  </main>
</body>
