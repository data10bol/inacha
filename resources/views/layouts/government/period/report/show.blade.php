@component('layouts.partials.pdfshowheader',[
    'title' => "PERIODO",
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
                  @statusvar($period->status)
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
                  @statusvar($period->current)
                </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                  INICIO
                </span>
          </th>
          <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                  {!! $period->start !!}
                </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                  FINAL
                </span>
          </th>
          <td valign="top" align="justify">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                  {!! $period->finish !!}
                </span>
          </td>
        </tr>
      </table>
    </p>
  </main>
</body>
