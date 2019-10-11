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
                {{ $operation->action->department->name}}
              @else
                {{ $action->department->name }}
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
            {!! Form::label('action_id', 'A. CORTO PLAZO', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              @if(isset($submitButtonText))
                {{ Form::hidden('action_id', $operation->action->id) }}
                {{ $operation->action->definitions->first()->description}}
              @else
                {{ Form::hidden('action_id', $action->id) }}
                {{ $action->definitions->first()->description }}
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
          <th class="align-top text-left">
            {!! Form::label('description', 'DESCRIPCIÓN', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! Form::textarea('description',
                  isset($operation)?isset($operation->definitions->first()->description)?$operation->definitions->first()->description:null:null,
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
            {!! Form::label('ponderation', 'PONDERACIÓN (%)', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-3">
              {!! Form::text('ponderation',
              isset($operation)?isset($operation->definitions->first()->ponderation)?$operation->definitions->first()->ponderation:null:null,
                      ('' == 'required') ? [
                              'class' => 'form-control',
                              'rows' => '3',
                              'max' => '100',
                              'required' => 'required']:[
                              'class' => 'form-control',
                              'rows' => '3',
                              'onkeyup' => "calculate()",
                              'id'=>'ponderation',
                              'max' => '100'])
              !!}
            </div>
            <div class="col-sm-10">
              <label>
                <code>
                  {!! $errors->first('ponderation', '<p class="help-block">:message</p>') !!}
                </code>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            {!! Form::label('pointer', 'INDICADOR', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-10">
              {!! Form::text('pointer',
              isset($operation)?isset($operation->definitions->first()->pointer)?$operation->definitions->first()->pointer:null:null,
                      ('' == 'required') ? [
                              'class' => 'form-control',
                              'rows' => '3',
                              'maxlength' => '150',
                              'required' => 'required']:[
                              'class' => 'form-control',
                              'rows' => '3',
                              'maxlength' => '150'])
              !!}
            </div>
            <div class="col-sm-10">
              <label>
                <code>
                  {!! $errors->first('pointer', '<p class="help-block">:message</p>') !!}
                </code>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            {!! Form::label('describe', 'FÓRMULA', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-12">
              {!! Form::textarea('describe',
              isset($operation)?isset($operation->definitions->first()->describe)?$operation->definitions->first()->describe:null:null,
              ('' == 'required') ? [
                              'class' => 'form-control',
                              'rows' => '3',
                              'maxlength' => '150',
                              'required' => 'required']:[
                              'class' => 'form-control',
                              'rows' => '3',
                              'maxlength' => '150'])
              !!}
            </div>
            <div class="col-sm-10">
              <label>
                <code>
                  {!! $errors->first('describe', '<p class="help-block">:message</p>') !!}
                </code>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            {!! Form::label('base', 'BASE(%)', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-3">
              {!! Form::text('base',
                  isset($operation)?isset($operation->definitions->first()->base)?$operation->definitions->first()->base:null:null,
                      ('' == 'required') ? [
                              'class' => 'form-control',
                              'max' => '100',
                              'required' => 'required']:[
                              'class' => 'form-control',
                              'max' => '100'])
              !!}
            </div>
            <div class="col-sm-10">
              <label>
                <code>
                  {!! $errors->first('base', '<p class="help-block">:message</p>') !!}
                </code>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th class="align-top text-left">
            {!! Form::label('aim', 'META (%)', ['class' => 'col-form-label']) !!}
          </th>
          <td class="align-top text-justify">
            <div class="col-sm-3">
              {!! Form::text('aim',
              isset($operation)?isset($operation->definitions->first()->aim)?$operation->definitions->first()->aim:null:null,
                      ('' == 'required') ? [
                              'class' => 'form-control',
                              'max' => '100',
                              'required' => 'required']:[
                              'class' => 'form-control',
                              'max' => '100'])
              !!}
            </div>
            <div class="col-sm-10">
              <label>
                <code>
                  {!! $errors->first('aim', '<p class="help-block">:message</p>') !!}
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
            <div class="col-sm-12">
              {!! Form::textarea('validation',
              isset($operation)?isset($operation->definitions->first()->validation)?$operation->definitions->first()->validation:null:null,
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
    </td>
    <td class="align-top text-justify"
        bgcolor="{{reprogcheck()?'#da2031':'#6c757d'}}"
        style="width: 320px">
      <table class="table table-sm table-bordered table-striped " cellspacing="0">
        <tr>
          <th colspan="2" class="align-center text-center">
            CRONOGRAMA
          </th>
        </tr>
        @php
        //dd($operation);

          $months=App\Month::OrderBy('id','ASC')->get();

          
          $total = 0;
          if(isset($operation)){
            $mmin = $operation->definitions->first()->start;
            $mmax = $operation->definitions->first()->finish;
          }else{
            $mmin = activeReprogMonth();
            $mmax = 12;
          }
          //dd($mmax);
        @endphp
        @foreach($months as $month)
          @php
            $m='m'.$month->id;
            if(isset($operation))
            {
              if(isset($operation->poas->first()->$m))
                if(reprogcheck())
                  $total += reprogvalue($month,$operation,true);
                else
                  $total += $operation->poas->first()->$m;
            }
          @endphp
          <tr>
            <th class="align-top text-right" style="width: 100px">
              {!! Form::label('m'.$month->id, ucfirst ($month->name),
                  ['class' => 'col-form-label align-center text-right']) !!}
              <small>
                @if(isset($operation))
                  @if(reprogcheck())
                    {{'('.$operation->poas->first()->$m.')'}}
                  @else
                    {{null}}
                  @endif
                @endif
              </small>
            </th>
            <td>
              <div class="align-top text-right">
                {!! Form::text($m,
                isset($operation)?
                  isset($operation->poas->first()->$m)?
                    reprogcheck()?
                      reprogvalue($month,$operation,true):
                $operation->poas->first()->$m:
                '0':
                '0',
                    ['class' => 'form-control align-top text-right',
                    'required' => 'required',
                    'id' => 'm'.$month->id,
                    'disabled' =>
                          ($month->id >= activemonth())?
                          false
                          :true,
                    'onkeyup' => "calculate()",])
                !!}
                @if(isset($operation))
                {!! ($month->id >= activemonth())?
                          null:
                          Form::hidden($m, reprogvalue($month,$operation,true))
                !!}
                @endif
                
              </div>
            </td>
          </tr>
        @endforeach
        @php
            $total = number_format($total,2);
        @endphp
        <tr>
          <td class="align-top text-center">
            {!! Form::label('total','TOTAL', ['class' => 'col-form-label align-top text-right']) !!}
            <small><div id=res > @if(isset($operation)) ({{  number_format(100-$total,2) }}) @endif</div></small>
          </td>
          <td>
            <div class="align-top text-right">
              {!! Form::text('total',
                  isset($operation)?$total:'0', [
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
  {!! Form::submit(isset($submitButtonText) ?
    $submitButtonText :
    'CREAR',
    ['class' => 'btn btn-secondary btn-sm',
    'disabled' => 'true',
    'id'=>'su']) !!}
  {!! Form::reset('BORRAR', ['class' => 'btn btn-danger btn-sm']) !!}
</div>
@php
  $gl = 100;
  /**
   *  Al momento de generar una reprogramacion el sistema calculara 
   *  la diferencia que hay entre lo planificado y lo ejecutado y lo agregara a la meta
   */
  // if(reprogcheck()){
  //   $pl = accum($operation->id,'Operation',0,activemonth()-1);
  //   $ex = accum($operation->id,'Operation',1,activemonth()-1);
  //   $gl = 100+($pl-$ex);
  // }else{
  //   $gl = 100;
  // }
@endphp
<script>


function redondeo(numero, decimales){
  var flotante = parseFloat(numero);
  var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
  return resultado;
}

  var goal = {{$gl}};

  function calculate() {
    
    var el, i = 1, total = 0;
    var result = document.getElementById('total');
    while (el = document.getElementById('m' + (i++))) {
      el.value = el.value.replace(/\\D/, "");
      total = total + Number(el.value);
    }
    if(isNaN(total))
      toastr.error("Debe ingresar un dato numerico en los campos de planificacion");
      
    if (total > goal)
      toastr.error("Superó el "+goal+" % de programacion");
    
    var res = 100-total;
    res = redondeo(res, 2);
    total = redondeo(total, 2);
    result.value = total.toFixed(2);

    $("#res").html('('+res+')');

    if(total==goal){
        document.getElementById('su').disabled=false;
        return false;
    }

    var pon = document.getElementById('ponderation').value;
    // toastr.success(pon);
    if(pon == 0 && pon != '' && !isNaN(pon))
        document.getElementById('su').disabled=false;
    else
        document.getElementById('su').disabled=true;
    
  }
</script>
@php
function reprogvalue($month,$operation,$status)
{
$m = 'm'.$month->id;
if ($month->id >= activemonth())
{
  if ($month->id == activemonth())
  {
    return $operation->poas->
            where('state',true)->
            where('month',false)->
            first()->$m;
    }
  else
    return '0.00';
}
else
return $operation->poas->where('state', 1)->first()->$m;
}

@endphp