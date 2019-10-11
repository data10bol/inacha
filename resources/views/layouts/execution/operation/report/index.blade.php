@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "EJECUCIÓN DE OPERACIONES A ".
    strtoupper($months[activemonth()]).' DE '.
    activemonth(),
    ])
@endcomponent
    <tbody>
        @php $tmp = 0; $tmp2 = 0; @endphp
        @forelse($ido as $id)
            @php
                $item = $operation->Where('id',$id)->first();
            @endphp
            @if(isset($item))
                <tr>
                <td class="align-top text-center" style="width: 60px">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    {{ $item->action->goal->code }}.{{
                        $item->action->code }}.{{
                        $item->code }} <br>
                    @if(!$item->status)
                    <span class="badge badge-danger">ELIMINADA</span>
                    @endif
                  </span>
                </td>
                <td class="align-top text-center" style="width: 60px">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                        {{ $item->action->year }}
                  </span>
                </td>
                <td class="align-top text-justify">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    @if($item->action->department->id != $tmp2)
                    {{ $item->action->department->name }}
                    @endif
                  </span>
                </td>
                <td class="align-top text-justify">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    @if($item->action->goal->doing->code.'.'.
                    $item->action->goal->code.'.'.
                    $item->action->code != $tmp)
                        {!! $item->action->definitions->first()->description !!}
                    @endif
                  </span>
                </td>
                <td class="align-top text-justify">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    {!! $item->definitions->pluck('description')->first() !!}
                  </span>
                </td>
                <td class="align-top text-center">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    @php
                    $prog = App\Poa::Where('poa_type','App\Operation')
                            ->Where('poa_id',$item->id)
                            ->first()
                            ->toarray();
                    $totalprog = 0;
                    for($i=1;$i<=12;$i++)
                        $totalprog += $prog["m".$i];
                    @endphp
                    {{ $totalprog }}%
                  </span>
                </td>
                <td class="align-top text-center">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    @php
                    $totalexec = 0;
                    for($i=1;$i<=12;$i++)
                    {
                    $m = 'm'.$i;
                    isset($item->poas->Where('state',true)->Where('month',false)->first()->$m)?
                        $totalexec += $item->poas->Where('state',true)->Where('month',false)->first()->$m:
                        null;
                    }
                    @endphp
                    {{ $totalexec }}%
                  </span>
                </td>
                <td class="align-top text-center">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    {{ ucfirst($months[$item->definitions->pluck('start')->first()]) }}
                  </span>
                </td>
                <td class="align-top text-center">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    {{ ucfirst($months[$item->definitions->pluck('finish')->first()]) }}
                  </span>
                </td>
                <td class="align-top text-center">
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                    {{ App\Task::Where('operation_id',$item->id)->where('status',true)->count() }}
                  </span>
                </td>
                <td class="align-top text-center">

                  @php
                    $cant = quantity_poa_rep($item->id, 'operation');
                    $pon = $item->definitions->pluck('ponderation')->last();
                  @endphp
                  @if ($cant>0)
                    @if ($pon > 0)
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'><small>R. ({{$cant}})</small></span>     
                    @else
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'><small>A.</small></span> 
                        
                    @endif
                    
        
                  @else
                  <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'><small>V.</small></span> 
                  @endif
              </td>
                </tr>
                @php
                ($item->action->goal->doing->code.'.'.
                        $item->action->goal->code.'.'.
                        $item->action->code != $tmp)?
                    $tmp=$item->action->goal->doing->code.".".
                        $item->action->goal->code.".".
                        $item->action->code:null;
                ($item->action->department->id != $tmp2)?
                    $tmp2=$item->action->department->id:null;
                @endphp
            @endif
        @empty
            {!! emptyrecord(count($header)) !!}
        @endforelse
    </tbody>
</table>
</p>
</main>
</body>
