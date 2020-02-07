@php
  $month = 'm'.$currentmonth;
@endphp
@component('layouts.partials.pdfheaderh',[
    'arg'=>$header2,
    'title' => "REPORTE MES: ".strtoupper (App\Month::FindorFail($currentmonth)->name).'/'.activeyear(),
    ])
@endcomponent
  @php
    $month = 'm'.$currentmonth;
    $operations = $operations2;
  @endphp
  @forelse($operations as $operation)
    @php
      $prog=$operation->poas()->
                Where('state',false)->
                Where('month',false)->
                orderby('id','desc')->
                first()->$month;
                
      $exec=$operation->poas()->
                Where('state',true)->
                Where('month',false)->
                orderby('id','desc')->
                first()->$month;

      $accump =accum($operation->id,'Operation',false,$currentmonth);
      $accume = accum($operation->id,'Operation',true,$currentmonth);
    @endphp
    <tr style="font-weight:bold" bgcolor="#f5f5f5">
      <td class="align-top text-left">
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
        {{ $operation->action->goal->code }}.{{
                          $operation->action->code }}.{{
                          $operation->code }}<br>
        @if(!$operation->status)
          <span class="badge badge-danger">REPROG</span>
        @endif
        </span>
      </td>
      <td class="align-top text-justify">
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
        {!! substr($operation->definitions->last()->description, 0, 200) !!}
        {!! strlen($operation->definitions->last()->description) > 200 ? '...' : '' !!}
        </span>
      </td>
      <td class="align-top text-center">
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
        {{ $operation->definitions->last()->dep_ponderation }}
        </span>
      </td>
      <td class="align-top text-center">
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
        {{ $operation->definitions->last()->aim }}
        </span>
      </td>
      <td class="align-top text-center">
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
        {{ $operation->definitions->last()->pointer }}
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
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
        {{ number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '') }}%
        </span>
      </td>
      <td class="align-top text-left">
        <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
            @php
                if(isset($operation->poas->Where('state',true)->Where('month',$currentmonth)->first()->success)){
                  echo $operation->poas->Where('state',true)->Where('month',$currentmonth)->first()->success;
                }else{
                  echo '';
                }
                // $caracteres = strlen($aux);
                // $emp = 0;
                // for ($i=200; $i < $caracteres ; $i=$i+200) { 
                //     $a = substr($aux, $emp, $i);
                //     echo($a.'<br>');
                //     $emp = 0 + $i;
                // }
            @endphp
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
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
        {{ number_format((float)($prog>0)?$exec*100/$prog:0, 2, '.', '') }}%
        </span>
      </td>
      <td class="align-top text-left">
        <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
          {{ $operation->definitions->last()->department->name }}
        </span>
      </td>
    </tr>
    @php
      $tasks = App\Task::Where('operation_id',$operation->id)->get();
    @endphp
    @forelse($tasks as $task)
      @php
        $prog=$task->poas->
                  Where('state',false)->
                  Where('month',false)->
                  first()->$month;
        $exec=$task->poas->
                  Where('state',true)->
                  Where('month',false)->
                  first()->$month;
        $accump =accum($task->id,'Task',false,$currentmonth);
        $accume = accum($task->id,'Task',true,$currentmonth);
      @endphp
      <tr>
        <td class="align-top text-left">
          <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
          {{ $task->operation->action->goal->code }}.{{
                            $task->operation->action->code }}.{{
                            $task->operation->code }}.{{
                            $task->code }}<br>
          @if(!$task->status)
            <span class="badge badge-danger">Borrado</span>
          @endif
          </span>
        </td>
        <td class="align-top text-justify">
          <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
          {!! substr($task->definitions->first()->description, 0, 200) !!}
          {!! strlen($task->definitions->first()->description) > 200 ? '...' : '' !!}
          </span>
        </td>
        <td class="align-top text-center" colspan="3">

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
          <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
          {{ number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '') }}%
          </span>
        </td>
        <td class="align-top text-left">
          <span lang=ES-BO style='font-size:6.0pt;font-family:"Arial",sans-serif'>
            @php
                isset($task->poas->Where('state',true)->Where('month',$currentmonth)->first()->success)?$aux1=$task->poas->Where('state',true)->Where('month',$currentmonth)->first()->success:$aux1='';
                $caracteres1 = strlen($aux1);
            @endphp
            {{ $aux1 }}
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
          <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
          {{ number_format((float)($prog>0)?$exec*100/$prog:0, 2, '.', '') }}%
          </span>
        </td>
        <td class="align-top text-left">
          <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
          @forelse ($task->users as $taskuser)
            <li>{{ $taskuser->name }}</li>
          @empty
            <li>SIN REGISTROS</li>
          @endforelse
          </span>
        </td>
      </tr>
      @empty
      {!! emptyrecord(count($header)) !!}
      @endforelse
      @empty
      {!! emptyrecord(count($header)) !!}
      @endforelse
      
      </tbody>
      </table>
    </p>
  </main>
</body>

