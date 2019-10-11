@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "TAREAS",
    ])
@endcomponent
      <tbody>
        @php $tmp = 0; $tmp2 = 0; @endphp
          @forelse($idt as $id)
              @php
                  $item = $task->Where('id',$id)->first();
              @endphp
              @if(isset($item))
              <tr>
                  <td class="align-top text-center" style="width: 60px">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                          {{  $item->operation->action->goal->code }}.{{
                              $item->operation->action->code }}.{{
                              $item->operation->code }}.{{
                              $item->code }}<br>
                          @if(!$item->status)
                          <span class="badge badge-danger">ELIMINADA</span>
                          @endif
                    </span>
                  </td>
                  <td class="align-top text-justify">
                    @if($item->operation->action->department->id != $tmp2)
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {{ $item->operation->action->department->name }}
                    </span>
                    @endif
                  </td>
                  <td class="align-top text-justify">
                    @if($item->operation->action->goal->doing->code.'.'.
                                  $item->operation->action->goal->code.'.'.
                                  $item->operation->action->code.'.'.
                                  $item->operation->code  != $tmp)
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {!! $item->operation->definitions->first()->description !!}
                    </span>
                    @endif
                  </td>
                  <td class="align-top text-justify">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      {!! $item->definitions->pluck('description')->first() !!}
                    </span>
                  </td>
                  <td class="align-top text-left">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      <ul>
                      @forelse ($item->users as $taskuser)
                          <li>{{ $taskuser->name }}</li>
                      @empty
                          <li>SIN REGISTROS</li>
                      @endforelse
                      </ul>
                    </span>
                  </td>
                  <td class="align-top text-center">
                    <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
                      @php
                      $prog = App\Poa::Where('poa_type','App\Task')->Where('poa_id',$item->id)->first()->toarray();
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
                      isset($item->poas->Where('state',true)->Where('month',$i)->first()->$m)?
                          $totalexec += $item->poas->Where('state',true)->Where('month',$i)->first()->$m:
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
              </tr>
              @php
                                  ($item->operation->action->goal->doing->code.'.'.
                                          $item->operation->action->goal->code.'.'.
                                          $item->operation->action->code.'.'.
                                          $item->operation->code != $tmp)?
              $tmp=$item->operation->action->goal->doing->code.".".
              $item->operation->action->goal->code.".".
              $item->operation->action->code.".".
              $item->operation->code:null;
              ($item->operation->action->department->id != $tmp2)?
              $tmp2=$item->operation->action->department->id:null;
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
