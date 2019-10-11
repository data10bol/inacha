@component('layouts.partials.pdfshowheader',[
    'title' => "DEFINICIÓN DE GESTIÓN",
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
            @statusvar($configuration->status)
            </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
            EDITAR
            </span>
          </th>
          <td valign="top" align="justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
            @if($configuration->edit)
                <span class="badge badge-success">SI</span>
              @else
                <span class="badge badge-danger">NO</span>
              @endif
            </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              REFORMULAR
            </span>
          </th>
          <td valign="top" align="justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
            @if($configuration->reconfigure)
                <span class="badge badge-success">SI</span>
              @else
                <span class="badge badge-danger">NO</span>
              @endif
            </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              PERÍODO
            </span>
          </th>
          <td valign="top" align="justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
            <strong>{{ $configuration->period->start }} - {{
                              $configuration->period->finish }}</strong>
            </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              MISIÓN
            </span>
          </th>
          <td valign="top" align="justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
            {!! $configuration->mission !!}
            </span>
          </td>
        </tr>
        <tr>
          <th valign="top" align="left">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              VISIÓN
            </span>
          </th>
          <td valign="top" align="justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
            {!! $configuration->vision !!}
            </span>
          </td>
        </tr>
      </table>
    </p>
  </main>
</body>
