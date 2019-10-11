@php
  $month = 'm'.$currentmonth;
  $cnt = 0;
  $departments = App\Action::Select('department_id')->
                            GroupBy('department_id')->
                            pluck('department_id')->
                            toarray();
  foreach ($departments as $department) {
    $total[$cnt] = 0;
    $aux = 0;
    foreach ($actions as $item){
      if($department == $item->department_id){
        $aux++;
        $total[$cnt] +=accum($item->id,'Action',true,$currentmonth);
        }
    }
    $total[$cnt] /= $aux;
    $cnt++;
  }
@endphp
        @component('layouts.partials.pdfheaderh',[
            'arg'=>$header,
            'title' => "REPORTE MES: ".strtoupper (App\Month::FindorFail($currentmonth)->name)."/".activeyear(),
            ])
        @endcomponent
        @forelse($actions as $item)
          @php
            $month = 'm'.$currentmonth;
            $prog=$item->poas()->
                        Where('state',false)->
                        Where('month',false)->
                        orderby('id','desc')->
                        first()->$month;
            $exec=$item->poas()->
                        Where('state',true)->
                        Where('month',false)->
                        orderby('id','desc')->
                        first()->$month;
          $accump =accum($item->id,'Action',false,$currentmonth);
          $accume = accum($item->id,'Action',true,$currentmonth);
          @endphp
          <tr>
            <td class="align-top text-center" width="50">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ $item->goal->code }}.{{
                                  $item->code }}<br>
              @if(!$item->status)
                <span class="badge badge-danger">REPROG</span>
              @endif
              </span>
            </td>
            <td class="align-top text-justify">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ $item->definitions->first()->description }}
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ $item->definitions->first()->ponderation }}
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ $item->definitions->first()->aim }}
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ $item->definitions->first()->pointer }}
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ number_format((float)$accump, 2, '.', '') }}
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ number_format((float)$accume, 2, '.', '') }}
              </span>
            </td>
            <td class="align-top text-center">
              {!! arrows(number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '')) !!}
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '') }}%
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ $prog }}
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ $exec  }}
              </span>
            </td>
            <td class="align-top text-center">
              {!! arrows(number_format((float)($prog>0)?$exec*100/$prog:0, 2, '.', '')) !!}
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ number_format((float)($prog>0)?$exec*100/$prog:0, 2, '.', '') }}%
              </span>
            </td>
            <td class="align-top text-center">
              <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ $item->department->name }}
              </span>
            </td>
          </tr>
        @empty
          {!! emptyrecord(count($header)) !!}
        @endforelse
      </table>
    </p>
  </main>
</body>

