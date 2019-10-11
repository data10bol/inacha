<div class="card-body p-0">
    <table class="table table-striped">
        <tr>
            <th class="align-top text-left">
                {!! Form::label('year', 'GESTIÓN', ['class' => 'col-form-label']) !!}
            </th>
            <td class="align-center text-justify">
                <div class="col-sm-2">
                    {{ activeyear() }}
                </div>
                <div class="col-sm-10">
                <label>
                    <code>
                    </code>
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <th class="align-top text-left">
                {!! Form::label('department', 'DEPARTAMENTO', ['class' => 'col-form-label']) !!}
            </th>
            <td class="align-center text-justify">
                <div class="col-sm-12">
                    @if(isset($submitButtonText))
                        {{ $subtask->task->operation->action->department->name}}
                    @else
                        {{ $task->operation->action->department->name }}
                    @endif
                </div>
                <div class="col-sm-10">
                <label>
                    <code>
                    </code>
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <th class="align-top text-left" style="width: 120px">
            {!! Form::label('task_id', 'TAREA', ['class' => 'col-form-label']) !!}
            </th>
            <td class="align-top text-justify">
            <div class="col-sm-12">
                @if(isset($submitButtonText))
                    {{ $subtask->task->definitions->first()->description}}
                @else
                    {{ Form::hidden('task_id', $task->id) }}
                    {{ $task->definitions->first()->description }}
                @endif
            </div>
            <div class="col-sm-12">
                <label>
                <code>
                    {!! $errors->first('task_id', '<p class="help-block">:message</p>') !!}
                </code>
                </label>
            </div>
            </td>
        </tr>
        <tr>
            <th class="align-top text-left">
                {!! Form::label('user_id', 'RESPONSABLE', ['class' => 'col-form-label']) !!}
            </th>
            <td class="align-center text-justify">
                <div class="col-sm-2">
                    @if(isset($submitButtonText))
                        {{ $subtask->task->user->name }}
                    @else
                        {{ Form::hidden('user_id', $task->user->id) }}
                        {{ $task->user->name }}
                    @endif
                </div>
                <div class="col-sm-10">
                <label>
                    <code>
                    </code>
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <th class="align-top text-left" style="width: 120px">
                {!! Form::label('status', 'ESTADO', ['class' => 'col-form-label']) !!}
            </th>
            <td class="align-top text-left">
                <div class="col-sm-3">
                    {!! Form::checkbox('status', true,
                        isset($subtask->status)&&($subtask->status)?true:false,
                        ['class' => 'minimal'])
                    !!}
                </div>
            </td>
        </tr>
        <tr>
            <th class="align-center text-left">
            {!! Form::label('start', 'INICIO', ['class' => 'col-form-label']) !!}
            </th>
            <td>
            <div class="col-sm-3 align-top text-left">
                @php
                    $mind=date('Y-m-d', strtotime('-7 day'));
                    $maxd=date('Y-m-d', strtotime('+7 day'));
                @endphp
                {{ Form::date('start',
                            isset($subtask->definition->start)?
                            \Carbon\Carbon::now():
                            null,
                            ('' == 'required') ?
                            ['class' => 'form-control', 'min' => ''.$mind.'', 'max' => ''.$maxd.'', 'required' => 'required'] :
                            ['class' => 'form-control', 'min' => ''.$mind.'', 'max' => ''.$maxd.''])
                }}
            </div>
            <div class="text-left">
                <label>
                <code>
                    {!! $errors->first('start', '<p class="help-block">:message</p>') !!}
                </code>
                </label>
            </div>
            </td>
        </tr>
        <tr>
            <th class="align-center text-left">
                {!! Form::label('finish', 'FINAL', ['class' => 'col-form-label']) !!}
            </th>
            <td>
            <div class="col-sm-3 align-top text-left">
                {{ Form::date('finish',
                    isset($subtask->definition->finish)?
                    \Carbon\Carbon::now():
                    null,
                    ('' == 'required') ?
                    ['class' => 'form-control', 'min' => ''.$mind.'', 'max' => ''.$maxd.'', 'required' => 'required'] :
                    ['class' => 'form-control', 'min' => ''.$mind.'', 'max' => ''.$maxd.''])
                }}
            </div>
            <div class="text-left">
                <label>
                <code>
                    {!! $errors->first('finish', '<p class="help-block">:message</p>') !!}
                </code>
                </label>
            </div>
            </td>
        </tr>
        <tr>
            <th class="align-top text-left">
            {!! Form::label('description', 'DESCRIPCIÓN', ['class' => 'col-form-label']) !!}
            </th>
            <td class="align-top text-justify">
            <div class="col-sm-10">
                {!! Form::textarea('description',
                    isset($subtask)?isset($subtask->description)?$subtask->description:null:null,
                                ('' == 'required') ? [
                                'class' => 'form-control',
                                'rows' => '3',
                                'maxlength' => '512',
                                'required' => 'required']:[
                                'class' => 'form-control',
                                'rows' => '3',
                                'maxlength' => '512'])
                !!}
            </div>
            <div class="col-sm-10">
                <label>
                    <code>
                        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                    </code>
                </label>
            </div>
            </td>
        </tr>
        <tr>
            <th class="align-top text-left">
            {!! Form::label('validation', 'RESULTADO', ['class' => 'col-form-label']) !!}
            </th>
            <td class="align-top text-justify">
            <div class="col-sm-10">
                {!! Form::textarea('validation',
                isset($subtask)?isset($subtask->validation)?$subtask->validation:null:null,
                            ('' == 'required') ? [
                                    'class' => 'form-control',
                                    'rows' => '3',
                                    'maxlength' => '512',
                                    'required' => 'required']:[
                                    'class' => 'form-control',
                                    'rows' => '3',
                                    'maxlength' => '512'])
                !!}
            </div>
            <div class="col-sm-10">
                <label>
                <code>
                    {!! $errors->first('validation', '<p class="help-block">:message</p>') !!}
                </code>
                </label>
            </div>
            </td>
        </tr>
    </table>
</div>
<!-- /.card-body -->

<div class="card-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'CREAR', ['class' => 'btn btn-secondary btn-sm']) !!}
    {!! Form::reset('BORRAR', ['class' => 'btn btn-danger btn-sm']) !!}
</div>

