@section('css')
  <style>
    .active{

    }
  </style>
@endsection
@php
  $months=App\Month::OrderBy('id','ASC')->get();
  $total = 0;
  $totalexec = 0;
@endphp
<table  class="table table-bordered table-striped dataTable"
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
                {{ Form::hidden('year', activeyear()) }}
                <code>
                </code>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th>
            {!! Form::label('month', 'MES', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-center text-justify">
            <div class="col-sm-2">
              {{ ucfirst ($months[(activemonth()-1)]->name) }}
            </div>
            <div class="col-sm-10">
              {{ Form::hidden('month', activemonth()) }}
              <label>
                <code>
                </code>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left" style="width: 120px">
            DEPARTAMENTO
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {{ $operation->action->department->name }}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            ESTADO
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-3">
              @if(!$operation->action->goal->configuration->reconfigure)
                <span class="badge badge-success">VIGENTE</span> @else
                <span class="badge badge-danger">REPROGRAMADA</span> @endif
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            DESCRIPCIÓN
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! $operation->definitions->last()->description !!}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            PONDERACIÓN (%)
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-4">
              {{ $operation->definitions->last()->ponderation }}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            INDICADOR
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-4">
              {{ $operation->definitions->last()->pointer }}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            FÓRMULA
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! $operation->definitions->last()->describe !!}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            BASE (%)
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-4">
              {{ $operation->definitions->last()->base }}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            META (%)
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-4">
              {{ $operation->definitions->last()->aim }}
            </div>
          </td>
        </tr>
        <!-- TAREAS -->
        <tr>
          <td colspan="2">
            <table class="table table-sm table-bordered table-striped">
              <tr>
                <th>
                  TAREA(S)
                </th>
                <th>
                  PROGRAMADO
                </th>
                <th>
                  EJECUTADO
                </th>
                <th>
                  LOGRO(S)
                </th>
                <th>
                  PROBLEMA(S)
                </th>
                <th>
                  SOLUCION(ES)
                </th>
              </tr>
              @php
                $tasks = App\Task::Where('operation_id',$operation->id)
                        ->Orderby('code')
                        ->where('status',true)
                        ->get();
                $mx = 'm'.activemonth();
              @endphp
              @forelse ($tasks as $task)
                <tr>
                  <td>
                    {{ $task->definitions->first()->description}}
                  </td>
                  <td>
                    {{ $task->poas->Where('state',false)->first()->$mx}}
                  </td>
                  <td>
                    {!! isset($task->poas->Where('state',true)->Where('month',activemonth())->first()->$mx)?
                    $task->poas->Where('state',true)->Where('month',activemonth())->first()->$mx:
                    '<font color="red">SIN REGISTRO</font>' !!}
                  </td>
                  <td>
                    {!! isset($task->poas->Where('month',activemonth())->first()->success)?
                        nl2br($task->poas->Where('month',activemonth())->first()->success):
                        '<font color="red">SIN REGISTRO</font>'!!}
                  </td>
                  <td>
                    {!! isset($task->poas->Where('month',activemonth())->first()->failure)?
                            nl2br($task->poas->Where('month',activemonth())->first()->failure):
                            '<font color="red">SIN REGISTRO</font>'!!}
                  </td>
                  <td>
                    {!! isset($task->poas->Where('month',activemonth())->first()->solution)?
                nl2br($task->poas->Where('month',activemonth())->first()->solution):
                '<font color="red">SIN REGISTRO</font>'!!}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" align="center">
                    <span class="text-danger">SIN REGISTROS DE TAREAS</span>
                  </td>
                </tr>
              @endforelse
            </table>
          </td>
        </tr>
        <!-- FIN TAREAS -->
        <tr>
          <th class="align-top text-left">
            {!! Form::label('success', 'LOGROS', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! Form::textarea('success',
                              null,
                              ('' == 'required') ? [
                              'class' => 'form-control ckeditor',
                              'rows' => '6',
//                            'maxlength' => '512',
                              'required' => 'required']:[
                              'class' => 'form-control ckeditor',
                              'rows' => '6',
//                            'maxlength' => '512'
                              ])
              !!}
            </div>
            <div class="col-sm-12">
              <label>
                <code>
                  {!! $errors->first('success', '<p class="help-block">:message</p>') !!}
                </code>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            {!! Form::label('failure', 'PROBLEMAS', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! Form::textarea('failure',
                              null,
                              ('' == 'required') ? [
                                  'class' => 'form-control ckeditor',
                                  'rows' => '6',
//                                  'maxlength' => '512',
                                  'required' => 'required']:[
                                  'class' => 'form-control ckeditor',
                                  'rows' => '6',
//                                  'maxlength' => '512'
                                  ])
              !!}
            </div>
            <div class="col-sm-12">
              <label>
                <code>
                  {!! $errors->first('failure', '<p class="help-block">:message</p>') !!}
                </code>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            {!! Form::label('solution', 'SOLUCIÓN', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! Form::textarea('solution',
                              null,
                              ('' == 'required') ? [
                                  'class' => 'form-control ckeditor',
                                  'rows' => '6',
//                                  'maxlength' => '512',
                                  'required' => 'required']:[
                                  'class' => 'form-control ckeditor',
                                  'rows' => '6',
//                                  'maxlength' => '512'
                                  ])
              !!}
            </div>
            <div class="col-sm-12">
              <label>
                <code>
                  {!! $errors->first('solution', '<p class="help-block">:message</p>') !!}
                </code>
              </label>
            </div>
          </td>
        </tr>
      </table>
    </td>
    @php
        $canti = quantity_poa_rep($operation->id,'operation');
    @endphp
    <td class="align-top text-justify" bgcolor="#6c757d" style="width: 360px">
      <table class="table table-sm">
        <tr>
          <td colspan="3">
            <table class="table table-sm table-bordered table-striped">
              <tr>
                <th class="align-top text-center">MES</th>
                <th class="align-top text-center">PRG</th>
                @if($canti)
                  <th class="align-top text-center">REP</th>
                @endif
                <th class="align-top text-center">EJC</th>
              </tr>
              @php
                $months=App\Month::OrderBy('id','ASC')->get();
                $totalprog = 0;
                $totalprogr = 0;
                $total = 0;
              @endphp
              @foreach($months as $month)
                <tr>
                  <th class="align-top text-right" @if($month->id == activemonth())  bgcolor="#fdff70" @endif>
                    {!! Form::label('m'.$month->id,
                        ucfirst ($month->name),
                        ['class' => 'col-form-label align-center text-right'])
                    !!}
                  </th>
                  <td class="align-center text-right" @if($month->id == activemonth())  bgcolor="#fdff70" @endif>
                    <div class="align-top ">
                      {{ $operation->poas->pluck('m'.$month->id)->first() }}%
                      @php $totalprog += $operation->poas->pluck('m'.$month->id)->first() @endphp
                    </div>
                  </td>
                  @if($canti)
                  <td class="text-right" @if($month->id == activemonth())  bgcolor="#fdff70" @endif>
                      <div class="align-top ">
                        {{ $operation->poas()->where('state',false)->orderby('id','desc')->pluck('m'.$month->id)->first() }}%
                        @php $totalprogr += $operation->poas()->where('state',false)->orderby('id','desc')->pluck('m'.$month->id)->first() @endphp
                      </div>
                    </td>
                  @endif
                  @php
                    $m='m'.$month->id;
                    if(isset($operation))
                    {
                    if(is_null($operation->poas->where('state',true)->sum($m)))
                        $total += $operation->poas->where('state',true)->sum($m);
                    }
                  @endphp
                  <td @if($month->id == activemonth())  bgcolor="#fdff70" @endif>
                    <div class="align-top text-right">
                      {!! Form::text($m,
                      isset($operation)?
                          !is_null($operation->poas->where('state',true)->where('month',false)->pluck('m'.$month->id)->last())?
                          $operation->poas->where('state',true)->where('month',false)->pluck('m'.$month->id)->last():
                          '0':
                          '0',
                          ['class' => 'form-control align-top text-right',
                          'required' => 'required',
                          'id' => 'm'.$month->id,
                          'onkeyup' => "calculate()",
                          'disabled' => 'disabled'
                //        !empty($operation->poas->where('state',true)->sum($m))?'disabled':null
                              ]
                          )
                      !!}
                    </div>
                    @if($month->id == activemonth())
                      {{ Form::hidden('value',
                      !is_null($operation->poas->where('state',true)->where('month',false)->pluck('m'.$month->id)->last())?
                      $operation->poas->where('state',true)->where('month',false)->pluck('m'.$month->id)->last():
                      '0') }}
                    @endif
                  </td>
                </tr>
              @endforeach
              <tr>
                <td class="align-top text-center">
                  {!! Form::label('total','TOTAL', ['class' => 'col-form-label align-top text-right h6']) !!}
                </td>
                <th>
                  <div class="align-center text-right">
                    {{ $totalprog }}%
                  </div>
                </th>
                @if ($canti)
                  <th>
                    <div class="align-center text-right">
                      {{ $totalprogr }}%
                    </div>
                  </th>
                @endif
                <td>
                  <div class="align-top text-right">
                    {!! Form::text('total',
                                    $total, [
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
    result.value = total;
  }

  window.onload = function () {
      calculate();
  };
</script>
