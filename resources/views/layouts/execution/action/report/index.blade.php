@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "EJECUCIÓN DE ACCIONES DE CORTO PLAZO A ".
    strtoupper($months[activemonth()]).' DE '.
    activemonth(),
    ])
@endcomponent
<tbody>
@php
  $tmp = 0;
  $tmp2 = 0;
  $m = 'm'.activemonth();
@endphp
@forelse($action as $item)
  <tr>
    <td class="align-top text-center">
      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
      {{ $item->goal->code }}.{{
          $item->code }} <br>
      @if(!$item->status)
          <span class="badge badge-danger">ELIMINADA</span>
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
          {!! substr($item->goal->description, 0, 200) !!}
          {!! strlen($item->goal->description) > 200 ? '...' : '' !!}
        @endif
      </span>
    </td>
    <td class="align-top text-justify">
      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
      {!! substr($item->definitions->first()->description, 0, 200) !!}
        {!! strlen($item->definitions->first()->description) > 200 ? '...' : '' !!}
      </span>
    </td>
    <td class="align-top text-center">
      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
      {{ $item->poas()->
      Where('state',false)->
      Where('month',false)->
      first()->$m }}
      </span>
    </td>
    <td class="align-top text-center">
      <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
      {{ $item->poas()->
      Where('state',true)->
      Where('month',false)->
      first()->$m }}
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
      {{ App\Operation::Where('action_id',$item->id)->count() }}
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
