@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "GESTIONES",
    ])
@endcomponent
        <tbody>
        @forelse($year as $item)
          <tr>
            <td valign="top" align="center" style="width: 30px">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        {{ $loop->iteration }}
                      </span>
            </td>
            <td valign="top" align="left" style="width: 30px">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        {{ $item->id }}
                      </span>
            </td>
            <td valign="top" align="center">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        @statusvar($item->current)
                      </span>
            </td>
            <td valign="top" align="center">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        @statusvar($item->status)
                      </span>
            </td>
            <td valign="top" align="left">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        {{ $item->period->start }} - {{ $item->period->finish }}
                      </span>
            </td>
            <td valign="top" align="left">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        {{ $item->name }}
                      </span>
            </td>
          </tr>
        @empty
          {!! emptyrecord(count($header)) !!}
        @endforelse
        </tbody>
      </table>
    </p>
  </main>
</body>
