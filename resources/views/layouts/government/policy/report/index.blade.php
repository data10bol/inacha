@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "PILARES",
    ])
@endcomponent
        <tbody>
        @forelse($policy as $item)
          <tr>
            <td valign="top" align="center" style="width: 100px">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {{ $item->period->start
                          }}-{{ $item->period->finish }}
                    </span>
            </td>
            <td valign="top" align="center" style="width: 100px">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {{ $item->code }}
                    </span>
            </td>
            <td valign="top" align="left">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {!! $item->descriptions->first()->description !!}
                    </span>
            </td>
          </tr>
        @empty
          {!! emptyrecord(count($arg)) !!}
        @endforelse
        </tbody>
      </table>
    </p>
  </main>
</body>
