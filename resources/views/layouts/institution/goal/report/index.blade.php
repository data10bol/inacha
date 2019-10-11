@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "ACCIONES DE MEDIANO PLAZO",
    ])
@endcomponent
        <tbody>
        @php $tmp = 0; @endphp
        @forelse($goal as $item)
          <tr>
            <td class="align-top text-center" style="width: 100px">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        {{ $item->configuration->period->start }} - {{ $item->configuration->period->finish }}
                      </span>
            </td>
            <td class="align-top text-left" style="width: 100px">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                            {{ $item->doing->result->target->policy->code
                            }}.{{ $item->doing->result->target->code
                            }}.{{ $item->doing->result->id
                            }}.{{ $item->doing->code
                            }}.{{ $item->code }}
                      </span>
            </td>
            <td class="align-top text-justify">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                            @if($item->doing->result->code != $tmp)
                          {!! $item->doing->descriptions->first()->description !!}
                        @endif
                      </span>
            </td>
            <td class="align-top text-justify">
                      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        {!! $item->description !!}
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
