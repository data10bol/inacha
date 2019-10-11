@php
  $months=App\Month::OrderBy('id','ASC')->get();
  $total = 0;
  $totalexec = 0;
@endphp
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
              {{ $task->operation->action->department->name }}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            RESPONSABLE
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              <ul>
                @forelse ($task->users as $taskuser)
                  <li>{{ $taskuser->name }}</li>
                @empty
                  <li>SIN REGISTROS</li>
                @endforelse
              </ul>
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            ACCIÓN
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {{ $task->operation->action->goal->code }}.{{
                        $task->operation->action->code }}. {{
                        $task->operation->action->definitions->first()->description  }}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            OPERACIÓN
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {{ $task->operation->action->goal->code }}.{{
                        $task->operation->action->code }}.{{
                        $task->operation->code }}. {{
                        $task->operation->definitions->first()->description }}
            </div>
          </td>
        </tr>

        <tr>
          <th class="align-top text-left">
            TAREA
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {{ $task->operation->action->goal->code }}.{{
                        $task->operation->action->code }}.{{
                        $task->operation->code }}.{{
                        $task->code }}. {!! $task->definitions->first()->description !!}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            RESULTADO
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! $task->definitions->first()->validation !!}
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            ESTADO
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-3">
              @if(!$task->operation->action->goal->configuration->reconfigure)
                <span class="badge badge-success">VIGENTE</span> @else
                <span class="badge badge-danger">REPROGRAMADA</span> @endif
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            {!! Form::label('success', 'LOGROS', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! Form::textarea('success',
                              isset($task)?
                              isset($task->poas->
                              Where('state',true)->
                              Where('month',activemonth())->
                              first()->success)?
                              $task->poas->
                              Where('state',true)->
                              Where('month',activemonth())->
                              first()->success:null:null,
                              ('' == 'required') ? [
                              'class' => 'form-control ckeditor',
                              'rows' => '6',
//                              'maxlength' => '512',
                              'required' => 'required']:[
                              'class' => 'form-control ckeditor',
                              'rows' => '6',
//                              'maxlength' => '512'
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
                              isset($task)?
                              isset($task->poas->
                              Where('state',true)->
                              Where('month',activemonth())->
                              first()->failure)?
                              $task->poas->
                              Where('state',true)->
                              Where('month',activemonth())->
                              first()->failure:null:null,
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
                              isset($task)?
                              isset($task->poas->
                              Where('state',true)->
                              Where('month',activemonth())->
                              first()->solution)?
                              $task->poas->
                              Where('state',true)->
                              Where('month',activemonth())->
                              first()->solution:null:null,
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
    <td class="align-top text-justify" bgcolor="#6c757d" style="width: 400px">
      <table class="table table-sm">
        <tr>
          <td colspan="3">
            <table class="table table-sm table-bordered table-striped">
              <tr>
                <th class="align-top text-center">MES</th>
                <th class="align-top text-center">PRG</th>
                <th class="align-top text-center">EJC</th>
              </tr>
              @php
                $months=App\Month::OrderBy('id','ASC')->get();
                $totalprog = 0;
                $total = 0;
              @endphp
              @foreach($months as $month)
                <tr>
                  <th class="align-top text-right">
                    {!! Form::label('m'.$month->id,
                        ucfirst ($month->name),
                        ['class' => 'col-form-label align-center text-right'])
                    !!}
                  </th>
                  <td class="text-right">
                    <div class="align-top ">
                      {{ $task->poas->pluck('m'.$month->id)->first() }}%
                      @php $totalprog += $task->poas->pluck('m'.$month->id)->first() @endphp
                    </div>
                  </td>
                  @php
                    $m='m'.$month->id;
                    if(isset($task))
                    {
                        $mmin = $task->definitions->first()->start;
                        $mmax = $task->definitions->first()->finish;
                      if(is_null($task->poas->where('state',true)->sum($m)))
                        $total += $task->poas->where('state',true)->sum($m);
                    }
                    else
                    {
                        $mmin = $operation->definitions->first()->start;
                        $mmax = $operation->definitions->first()->finish;
                    }
                  @endphp
                  <td>
                    <div class="align-top text-right">
                      @hasanyrole('Responsable|Supervisor|Administrador')
                      {!! Form::text($m,
                      isset($task)?
                          !is_null($task->poas->where('state',true)->first()->$m)?
                          $task->poas->where('state',true)->first()->$m:
                          '0':
                          '0',
                          ['class' => 'form-control align-top text-right',
                          'required' => 'required',
                          'id' => 'm'.$month->id,
                          'onkeyup' => "calculate()",
                          'onkeypress' => "return decimals(event)",
                          'onPaste' => "return false",
                          'disabled' => ($month->id == activemonth())?null:'disabled',]
                          )
                      !!}
                      @endrole
                      @hasanyrole('Usuario')
                      {!! Form::text($m,
                      isset($task)?
                          !is_null($task->poas->where('state',true)->first()->$m)?
                          $task->poas->where('state',true)->first()->$m:
                          '0':
                          '0',
                          ['class' => 'form-control align-top text-right',
                          'required' => 'required',
                          'id' => 'm'.$month->id,
                          'onkeyup' => "calculate()",
                          'onkeypress' => "return decimals(event)",
                          'onPaste' => "return false",
                          'disabled' =>
                          ($month->id == activemonth())?
                          (!($month->id >= $mmin && $month->id <= $mmax)?true:
                          (isset($task)?
                          ($month->id < activemonth() || $month->id == lasttask($task->id))? 'disabled':null:
                          $month->id < activemonth()? 'disabled':null)):true,]
                          )
                      !!}
                      @endrole
                    </div>
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
  function redondeo(numero, decimales){
    var flotante = parseFloat(numero);
    var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
    return resultado;
  }
  function calculate() {
    var el, i = 1, total = 0;
    var result = document.getElementById('total');
    while (el = document.getElementById('m' + (i++))) {
      el.value = el.value.replace(/\\D/, "");
      total = total + Number(el.value);
    }
    if (total > 100)
      toastr.error("Superó el 100 %");

    result.value = redondeo(total, 2);
  }
  function decimals(evt) {
    evt = (evt) ? evt : window.event
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 46 || charCode > 57)) {
      status = "Solo números"
      return false
    }
    status = ""
    return true
  }
  @if(isset($submitButtonText))
  function zero(){
    for(var i=0; i<13;i++){
      var cam = "input[name=m"+i+"]";
      if(!$(cam).prop('disabled')){
        if($(cam).val()==0){
          if (confirm("¿Esta seguro de CERO avance?")) {
            return true
          } else {
            return false
          }
        }
      }
    }
  }
  @endif
  window.onload = function () {
          calculate();
  };
</script>
