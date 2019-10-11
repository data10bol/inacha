@if(isset($plan->id))
  <input type="hidden" value="{{ $task->user_id }}" name="usr_id" id="usr_id">
@endif
<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
  <tr>
    <td class="align-top text-justify">
      <table class="table">
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
                {{ $task->operation->action->department->name}}
              @else
                @if(!isset($operation))
                  {{ Auth::user()->position->department->name }}
                @else
                  {{ $operation->action->department->name }}
                @endif
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
            {!! Form::label('operation_id', 'OPERACIÓN', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            @if(isset($submitButtonText))
              <div class="col-sm-12">
                {{ Form::hidden('operation_id', $task->operation->id) }}
                {{ $task->operation->definitions->first()->description}}
              </div>
            @else
              @if(!isset($operation))
                <div class="col-sm-4">
                  {!! Form::select('operation_id',
                        App\Operation::Join('actions','action_id','=','actions.id')
                                ->Join('goals','goal_id','=','goals.id')
                                ->Select('operations.id AS id', DB::raw("CONCAT(goals.code,'.',actions.code,'.',operations.code) AS period"))
                                ->Where('actions.status',true)
                                ->Where('actions.department_id',Auth::user()->position->department->id)
                                ->OrderBy('goals.code','ASC')
                                ->OrderBy('actions.code','ASC')
                                ->OrderBy('operations.code','ASC')
                                ->get()
                                ->pluck('period','id'),
                        isset($operation->id)?$operation->id:null,
                        ('' == 'required') ? ['class' => 'form-control select2',
                                            'required' => 'required',
                                            'data-style' => "btn btn-info btn-round",
                                            'title' => "Seleccione...",
                                            'onchange'=>"myFunction()"]: [
                                            'class' => 'form-control select2',
                                            'data-style' => "btn btn-info btn-round",
                                            'title' => "Seleccione...",
                                            'onchange'=>"myFunction()"])
                  !!}
                </div>
              @else
                <div class="col-sm-12">
                  {{ Form::hidden('operation_id', $operation->id) }}
                </div>
              @endif
            @endif
            @if(!isset($submitButtonText))
              <div class="col-sm-12" id="descriptiontext"></div>
            @endif
            <div class="col-sm-12">
              <label>
                <code>
                  {!! $errors->first('operation_id', '<p class="help-block">:message</p>') !!}
                </code>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            {!! Form::label('users', 'RESPONSABLE(S)', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              @role('Administrador|Supervisor')
              {!! Form::select('users[]',
                  App\User::where('id','!=',1)
                      ->Where('status',true)
                      ->orderBy('name','ASC')
                      ->pluck('name','id'),
                  isset($task->id)?
                      App\Task::findOrFail($task->id)
                          ->users()->wherePivot('task_id','=',$task->id)
                          ->where('user_id','!=',1)
                          ->orderBy('name','ASC')
                          ->get():
                      null,
                  ('' == 'required') ?
                      ['class' => 'form-control',
                      'disabled' => isset($submitButtonText)?'disabled':null,
                      'required' => 'required',
                      'multiple'] :
                      ['class' => 'form-control',
//                                    'disabled' => isset($submitButtonText)?'disabled':null,
                      'multiple'])
              !!}
              @endrole
              @role('Responsable|Usuario')
              {!! Form::select('users[]',
                  App\User::Join('positions','position_id','=','positions.id')
                      ->Where('positions.department_id',Auth::user()->position->department->id)
                      ->Where('users.status',true)
                      ->pluck('users.name','users.id'),
                  isset($task->id)?
                      App\Task::findOrFail($task->id)
                          ->users()->wherePivot('task_id','=',$task->id)
                          ->where('user_id','!=',1)
                          ->orderBy('name','ASC')
                          ->get():
                      null,
                  ('' == 'required') ?
                      ['class' => 'form-control',
                      'disabled' => isset($submitButtonText)?'disabled':null,
                      'required' => 'required',
                      'multiple'] :
                      ['class' => 'form-control',
//                                    'disabled' => isset($submitButtonText)?'disabled':null,
                      'multiple'])
              !!}
              @endrole
            </div>
            <div class="col-sm-10">
              <label>
                <code>
                  {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
                </code>
              </label>
            </div>
          </td>
        </tr>
        {{ Form::hidden('plan_id', '1') }}
        <tr>
          <th class="align-top text-left">
            {!! Form::label('description', 'DESCRIPCIÓN', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! Form::textarea('description',
                  isset($task)?isset($task->definitions->first()->description)?$task->definitions->first()->description:null:null,
                              ('' == 'required') ? [
                              'class' => 'form-control',
                              'rows' => '3',
                              'maxlength' => '512',
                              'required' => 'required']:[
                              'class' => 'form-control',
                              'rows' => '3'])
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
        {{ Form::hidden('pointer', '0') }}
        {{ Form::hidden('describe', '0') }}
        {{ Form::hidden('base', '0') }}
        {{ Form::hidden('aim', '0') }}
        <tr>
          <th class="align-top text-left">
            {!! Form::label('validation', 'RESULTADO', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! Form::textarea('validation',
              isset($task)?isset($task->definitions->first()->validation)?$task->definitions->first()->validation:null:null,
                          ('' == 'required') ? [
                                  'class' => 'form-control',
                                  'rows' => '3',
                                  'maxlength' => '512',
                                  'required' => 'required']:[
                                  'class' => 'form-control',
                                  'rows' => '3'])
              !!}
            </div>
            <div class="col-sm-10">
              <label>
                <code>{!! $errors->first('validation', '<p class="help-block">:message</p>') !!}</code>
              </label>
            </div>
          </td>
        </tr>
        @if(isset($submitButtonText))
          {{ Form::hidden('edit', true) }}
          <tr>
            <th class="align-top text-left">
              {!! Form::label('reason', 'JUSTIFICATIVO', ['class' => 'col-form-label']) !!}
            </th>
            <td class="align-top text-justify">
              <div class="col-sm-12">
                {!! Form::textarea('reason', null,
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
                    {!! $errors->first('reason', '<p class="help-block">:message</p>') !!}
                  </code>
                </label>
              </div>
            </td>
          </tr>
        @endif
      </table>
    </td>
    <td class="align-top text-justify" bgcolor="#6c757d" style="width: 320px">
      <table class="table table-sm table-bordered table-striped ">
        <tr>
          <th colspan="2" class="align-center text-center">
            CRONOGRAMA
          </th>
        </tr>
        @php
          $months=App\Month::OrderBy('id','ASC')->get();
          $total = 0;
        @endphp
        @foreach($months as $month)
          <tr>
            <th class="align-top text-right">
              {!! Form::label('m'.$month->id,
              ucfirst ($month->name),
              ['class' => 'col-form-label align-center text-right']) !!}
            </th>
            @php
              $m='m'.$month->id;

              if(isset($task))
              {
                  $mmin = $task->operation->definitions->first()->start;
                  $mmax = $task->operation->definitions->first()->finish;
                if(isset($task->poas->first()->$m))
                  $total += $task->poas->first()->$m;
              }
              else
              {
                  $mmin = $operation->definitions->last()->start;
                  $mmax = $operation->definitions->last()->finish;
              }
            @endphp
            <td>
              <div class="align-top text-right">
                {!! Form::text($m,
                isset($task)?isset($task->poas->first()->$m)?$task->poas->first()->$m:'0':'0',
                    ['class' => 'form-control align-top text-right col-sm-12',
                    'required' => 'required',
                    'id' => 'm'.$month->id,
                    'disabled' => !($month->id >= $mmin && $month->id <= $mmax)?true:
                                    (isset($task)?
                                    ($month->id < activemonth() || $month->id == lasttask($task->id))? 'disabled':null:
                                    $month->id < activemonth()? 'disabled':null),
                    'onkeyup' => "calculate()",])
                !!}
              </div>
            </td>
          </tr>
        @endforeach
        <tr>
          <td class="align-top text-center">
            {!! Form::label('total','TOTAL', ['class' => 'col-form-label align-top text-right h6']) !!}
          </td>
          <td>
            <div class="align-top text-right">
              {!! Form::text('total',
                  isset($task)?$total:'0', [
                              'class' => 'form-control align-top text-right',
                              'required' => 'required',
                              'id' => 'total',
                              'disabled' => true])
              !!}
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<!-- /.card-body -->

<div class="card-footer">
  {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'CREAR', ['class' => 'btn btn-secondary btn-sm']) !!}
  {!! Form::reset('BORRAR', ['class' => 'btn btn-danger btn-sm']) !!}
</div>

<script>
  function calculate() {
    var el, i = 1, total = 0;
    var result = document.getElementById('total');
    while (el = document.getElementById('m' + (i++))) {
      el.value = el.value.replace(/\\D/, "");
      total = total + Number(el.value);
    }
    if (total > 100)
      alert("Superó el 100 %");
    result.value = total.toFixed(2);
  }
</script>

<script>

  window.onload = function () {
    var select = $("#operation_id option:selected").text();
    var valor = $("#operation_id").val();
    $.ajax({
      url: "/api/definition",
      type: 'POST',
      data: {
        "id": valor,
        "type": "Operation"
      },
      success: function (data) {
        $('#descriptiontext').fadeIn();
        $('#descriptiontext').html(data);
      }
    })
  }

  function myFunction() {
    var select = $("#operation_id option:selected").text();
    var valor = $("#operation_id").val();
    $.ajax({
      url: "/api/definition",
      type: 'POST',
      data: {
        "id": valor,
        "type": "Operation"
      },
      success: function (data) {
        $('#descriptiontext').fadeIn();
        $('#descriptiontext').html(data);
      }
    })
  }
</script>
