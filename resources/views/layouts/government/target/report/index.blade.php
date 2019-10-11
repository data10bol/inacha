@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "METAS",
    ])
@endcomponent
        <tbody>
        @php $tmp = 0; @endphp
        @forelse($target as $item)
          <tr>
            <td valign="top" align="center" style="width: 30px">
                        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                          {{ $item->policy->code
                              }}.{{ $item->code }}
                        </span>
            </td>
            <td valign="top" align="justify">
                        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                          @if($item->policy->code != $tmp)
                            {!! $item->policy->descriptions->first()->description !!}
                          @endif
                        </span>
            </td>
            <td valign="top" align="justify" style="width: 80%">
                        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                              {!! $item->descriptions->first()->description !!}
                        </span>
            </td>
          </tr>
          @php
              ($item->policy->code != $tmp)?$tmp=$item->policy->code:null;
          @endphp
        @empty
          {!! emptyrecord(count($header)) !!}
        @endforelse
        </tbody>
      </table>
    </p>
  </main>
</body>
