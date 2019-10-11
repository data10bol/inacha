@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "RESULTADOS",
    ])
@endcomponent
        <tbody>
        @php $tmp = 0; $tmp2 = 0; @endphp
        @forelse($result as $item)
          <tr>
            <td class="align-top text-center" style="width: 30px">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {{ $item->target->policy->code
                          }}.{{ $item->target->code
                          }}.{{ $item->id }}
                    </span>
            </td>
            <td class="align-top text-left">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      @if($item->target->policy->code != $tmp)
                        {!! $item->target->policy->descriptions->first()->description !!}
                      @endif
                    </span>
            </td>
            <td class="align-top text-justify">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      @if($item->target->code != $tmp2)
                        {!! $item->target->descriptions->first()->description !!}
                      @endif
                    </span>
            </td>
            <td class="align-top text-justify" style="width: 50%">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {!! $item->descriptions->first()->description !!}
                    </span>
            </td>
          </tr>
          @php
              ($item->target->policy->code != $tmp)?$tmp=$item->target->policy->code:null;
          ($item->target->code != $tmp2)?$tmp2=$item->target->code:null;
          @endphp
        @empty
          {!! emptyrecord(count($header)) !!}
        @endforelse
        </tbody>
      </table>
    </p>
  </main>
</body>
