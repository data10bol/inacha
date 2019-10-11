@foreach($goal as $item)
<table>
    <tr>
        <th>PILAR</th>
        <th>{!! $item->doing->result->target->policy->code !!}</th>
        <td colspan="17">
        {!! strtoupper($item->doing->result->target->policy->descriptions->first()->description) !!}
        </td>
    </tr>
    <tr>
        <th>META</th>
        <th>{!! $item->doing->result->target->code !!}</th>
        <td colspan="17">
        {!! strtoupper($item->doing->result->target->descriptions->first()->description) !!}
        </td>
    </tr>
    <tr>
        <th>RESULTADO</th>
        <th>{!! $item->doing->result->code !!}</th>
        <td colspan="17">
        {!! strtoupper($item->doing->result->descriptions->first()->description) !!}
        </td>
    </tr>
    <tr>
        <th>A. M. PLAZO</th>
        <th>{!! $item->code !!}</th>
        <td colspan="17">
        {!! strtoupper($item->description) !!}
        </td>
    </tr>
    @php
        $actions = App\Action::Where('goal_id',$item->id)->get();
        $totalaction =0;
    @endphp
    <tr>
        <th colspan="2"></th>
        <th>DESCRIPCIÓN</th>
        <th>PND.</th>
        <th></th>
        <th>RESPONSABLE<br></th>
        <th>E</th>
        <th>F</th>
        <th>M</th>
        <th>A</th>
        <th>M</th>
        <th>J</th>
        <th>J</th>
        <th>A</th>
        <th>S</th>
        <th>O</th>
        <th>N</th>
        <th>D</th>
        <th>TT</th>
    </tr>
    @foreach($actions as $action)
    <tr>
        <th>ACCIÓN</th>
        <th>{{ $action->code }}</th>
        <td>{!! $action->definitions->first()->description !!}</td>
        <td>{{ $action->definitions->first()->ponderation }}%</td>
        @php
            $total = 0;
            $totalaction += $action->definitions->first()->ponderation;
            $ids = App\Operation::Select('id')
                    ->Where('action_id',$action->id)
                    ->pluck('id')
                    ->all();
            $ponderation = App\Definition::Select('ponderation')
                        ->WhereIn('definition_id',$ids)
                        ->Where('definition_type','App\Operation')
                        ->Pluck('ponderation')
                        ->all();
        @endphp
        <td>{{ $action->definitions->first()->measure }}</td>
        <td>{{ $action->department->name }}</td>
        @for ($m = 1; $m <= 12; $m++)
            @php
            $totalmonth = progexecop($ids, $m);
            $total += $totalmonth;
            @endphp
            <td>{{ $totalmonth }}</td>
        @endfor
        <td>{{ number_format((float)$total, 2, '.', '') }}</td>
    </tr>

    <tr>
        <th>E. ACCIÓN</th>
        <th>{{ $action->code }}</th>
        <td></td>
        <td></td>
        @php
            $ids = App\Task::Select('id')->Where('operation_id',$action->id)->pluck('id')->toarray();
            $total = 0;
        @endphp
        <td></td>
        <td></td>
        @for ($m = 1; $m <= 12; $m++)
            @php
                $opmonth = progexecop($ids,$m);
                $total += $opmonth;
            @endphp
            <td>{{ $opmonth }}</td>
        @endfor
        <td>{{ $total }}</td>
    </tr>

    @php
        $operations = App\Operation::Where('action_id',$action->id)->get();
        $totaloperation =0;
    @endphp
    @foreach($operations as $operation)
    <tr>
        <th>OPERACIÓN</th>
        <th>{{ $operation->code }}</th>
        <td>{!! $operation->definitions->first()->description !!}</td>
        <td>{{ $operation->definitions->first()->ponderation }}%</td>
        @php
            $total = 0;
            $totaloperation += $operation->definitions->first()->ponderation;
        @endphp
        <td>{{ $operation->definitions->first()->measure }}</td>
        <td>{{ $operation->action->department->name }}</td>
        @for ($m = 1; $m <= 12; $m++)
            @php
            $month = 'm'.$m;
            $total +=  $operation->poas->first()->$month;
            @endphp
            <td>{{ $operation->poas->first()->$month }}</td>
        @endfor
        <td>{{ $total }}</td>
        </tr>
        <tr>
        <th>E. OPERACIÓN</th>
        <th>{{ $operation->code }}</th>
        <td></td>
        <td></td>
        @php
            $exops = App\Poa::Where('poa_id',$operation->id)
                            ->Where('poa_type','App\Operation')
                            ->Where('state',true)
                            ->first();
            $total = 0;
        @endphp
        <td></td>
        <td></td>
            @for ($m = 1; $m <= 12; $m++)
            @php
                $month = 'm'.$m;
                $total += $exops->$month;
            @endphp
            <td>{{ $exops->$month }}</td>
            @endfor
            <td>{{ $total }}</td>
    </tr>
        @php
            $tasks = App\Task::Where('operation_id',$operation->id)->get();
            $totaltask = [0,0,0,0,0,0,0,0,0,0,0,0];
        @endphp
        @foreach($tasks as $task)
    <tr>
            <th>TAREA
                @if(!$task->status)
                <br><span>ELIMINADA</span>
                @endif
            </th>
            <th>{{ $task->code }}</th>
            <td>{!! $task->definitions->first()->description !!}</td>
            <td></td>
            @php
            $total = 0;
            @endphp
            <td>{{ $task->definitions->first()->measure }}</td>
            <td>
            <ul>
                @forelse ($task->users as $taskuser)
                    <li>{{ $taskuser->name }}</li>
                @empty
                    <li>SIN REGISTROS</li>
                @endforelse
            </ul>
        </td>
            @for ($m = 1; $m <= 12; $m++)
            @php
                $month = 'm'.$m;
                $total +=  $task->poas->first()->$month;
                if($task->poas->first()->$month > 0)
                $totaltask[$m-1]++;
            @endphp
            <td>{{ $task->poas->first()->$month }}</td>
            @endfor
        <td>{{ $total }}</td>
    </tr>
    <tr>
            <th>EJECT</th>
            <th>{{ $task->code }}</th>
            <td></td>
            <td></td>
            @php
                $total = 0;
                $totalexec = 0;
            @endphp
            <td></td>
            <td></td>
            @for ($m = 1; $m <= 12; $m++)
                @php
                $month = 'm'.$m;
                !is_null($task->poas->Where('state',true)->Where('month',$m)->first())?
                    $totalexec += $task->poas->Where('state',true)->Where('month',$m)->first()->$month:
                    null;
                @endphp
                <td>{{ !is_null($task->poas->Where('state',true)->Where('month',$m)->first())?
                    $task->poas->Where('state',true)->Where('month',$m)->first()->$month:
                    0.00 }}</td>
            @endfor
            <td>{{ $totalexec }}</td>
    </tr>
        @endforeach
    <tr>
        <th colspan="6">TOTAL TAREAS</th>
        @for ($m = 1; $m <= 12; $m++)
            <td>{{ $totaltask[$m-1] }}</td>
        @endfor
        <td></td>
    </tr>
    @endforeach
    <tr>
        <th colspan="3">TOTAL OPERACIONES</th>
        <td>{{ $totaloperation }}%</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>0</td>
    </tr>
    @endforeach
    <tr>
        <th colspan="3">TOTAL ACCIÓN</th>
        <td>{{ $totalaction }}%</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>0</td>
    </tr>
</table>
@endforeach
