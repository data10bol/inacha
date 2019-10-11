@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "ACCIONES DE CORTO PLAZO",
    ])
@endcomponent
    <tbody>
    @php $tmp = 0; $tmp2 = 0; @endphp
    @forelse($action as $item)
        <tr>
            <td class="align-top text-center" style="width: 100px">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ $item->goal->code }}.{{
                $item->code }}<br>
            @if(!$item->status)
                <span class="badge badge-danger">REPROG</span>
            @endif
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ $item->year }}
              </span>
            </td>
            <td class="align-top text-left">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                @if($item->department->id != $tmp)
                    {{ $item->department->name }}
                @endif
              </span>
            </td>
            <td class="align-top text-justify">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                @if($item->goal->id != $tmp2)
                {!! $item->goal->description !!}
                @endif
              </span>
            </td>
            <td class="align-top text-justify">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {!! $item->definitions->first()->description !!}
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ $item->definitions->first()->ponderation }}%
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                {{ App\Operation::Where('action_id',$item->id)->count() }}
              </span>
            </td>
            @php
              $to = current_ponderation($item->id);
            @endphp
            <td>
              <span class="align-top text-center">
                <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                  {{$to}}
                </span>
              </span> 
            </td>
        </tr>
    @php
        ($item->department->id != $tmp)?
            $tmp=$item->department->id:null;
        ($item->goal->id != $tmp2)?
            $tmp2=$item->goal->id:null;
    @endphp
    @empty
        {!! emptyrecord(count($header)) !!}
    @endforelse
    </tbody>
</table>
</p>
</main>
</body>
