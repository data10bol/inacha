@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "ACCIONES",
    ])
@endcomponent
        <tbody>
        @php $tmp = 0; $tmp2 = 0; $tmp3 = 0; @endphp
        @forelse($doing as $item)
          <tr>
            <td class="align-top text-center" style="width: 100px">
                        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                          {{ $item->result->target->policy->code
                          }}.{{ $item->result->target->code
                          }}.{{ $item->result->id
                          }}.{{ $item->code }}
                        </span>
            </td>
            <td class="align-top text-justify">
                        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                          @if($item->result->target->policy->code != $tmp)
                            {!! $item->result->target->policy->descriptions->first()->description !!}
                          @endif
                        </span>
            </td>
            <td class="align-top text-justify">
                        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                          @if($item->result->target->code != $tmp2)
                            {!! $item->result->target->descriptions->first()->description !!}
                          @endif
                        </span>
            </td>
            <td class="align-top text-justify">
                        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                          @if($item->result->code != $tmp3)
                            {!! $item->result->descriptions->first()->description !!}
                          @endif
                        </span>
            </td>
            <td class="align-top text-justify">
                        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {!! $item->descriptions->first()->description !!}
            </td>
          </tr>
          @php
              ($item->result->target->policy->code != $tmp)?$tmp=$item->result->target->policy->code:null;
          ($item->result->target->code != $tmp2)?$tmp2=$item->result->target->code:null;
          ($item->result->code != $tmp3)?$tmp3=$item->result->code:null;
          @endphp
        @empty
          {!! emptyrecord(count($header)) !!}
        @endforelse
        </tbody>
      </table>
    </p>
  </main>
</body>
