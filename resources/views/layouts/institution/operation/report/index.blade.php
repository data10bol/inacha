@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "OPERACIONES",
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
      <td valign="top" align="center" width="60px">
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                              {{ $item->action->goal->code }}.{{
                              $item->action->code }}.{{
                              $item->code }}<br>
                              @if(!$item->status)
                                <span class="badge badge-danger">REPROG</span>
                              @endif
                            </span>
      </td>
      <td valign="top" align="center" width="60px">
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                                {{ $item->action->year }}
                            </span>
      </td>
      <td valign="top" align="center">
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                              @if($item->action->department->id != $tmp2)
                                {{ $item->action->department->name }}
                              @endif
                            </span>
      </td>
      <td valign="top" align="justify">
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                                  @if($item->action->goal->doing->code.'.'.
                                      $item->action->goal->code.'.'.
                                      $item->action->code != $tmp)
                                {!! substr($item->action->definitions->first()->description, 0, 200) !!}
                                {!! strlen($item->action->definitions->first()->description) > 200 ? '...' : '' !!}
                              @endif
                            </span>
      </td>
      <td valign="top" align="justify">
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                              {!! $item->definitions->first()->description !!}
                            </span>
      </td>
      <td valign="top" align="center">
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                              {{ $item->definitions->pluck('ponderation')->last() }}%
                            </span>
      </td>
      <td valign="top" align="center">
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                              {{ ucfirst($months[$item->definitions->pluck('start')->first()]) }}
                            </span>
      </td>
      <td valign="top" align="center">
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                              {{ ucfirst($months[$item->definitions->pluck('finish')->first()]) }}
                            </span>
      </td>
      <td valign="top" align="center">
                            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                              {{ App\Task::Where('operation_id',$item->id)->count() }}
                            </span>
      </td>
      <td valign="top" align="center">

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
</body>                        <
