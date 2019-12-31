<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
  <tr>
    <th class="align-top text-left" style="width: 120px">
      {!! Form::label('department_id', 'DEPARTAMENTO', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-5">
        {!! Form::select('department_id',
        App\Department::get()->pluck('name','id'),
        isset($action->department_id)?$action->department_id:null,
        ('' == 'required') ?
        ['class' => 'form-control select2', 'required' => 'required', 'data-style' => "btn btn-info  btn-round", 'title' => "Seleccione..."]:
        ['class' => 'form-control select2', 'data-style' => "btn btn-info  btn-round", 'title' => "Seleccione..."])
        !!}
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('department_id', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
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
<!--        <tr>
            <th class="align-top text-left">
                {!! Form::label('code', 'CÓDIGO', ['class' => 'col-form-label']) !!}
  </th>
  <td class="align-top text-justify">
      <div class="col-sm-2">
{!! Form::number('code', isset($code)?$code:null,
                ('' == 'required') ?
                ['class' => 'form-control', 'min' => '1', 'required' => 'required'] :
                ['class' => 'form-control', 'min' => '1',]) !!}
  </div>
  <div class="col-sm-10">
  <label>
      <code>
{!! $errors->first('code', '<p class="help-block">:message</p>') !!}
  </code>
  </label>
</div>
</td>
</tr> -->
  <tr>
    <th class="align-top text-left">
      {!! Form::label('goal_id', 'A. MEDIANO PLAZO', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-3">
        {!! Form::select('goal_id',
        isset($submitButtonText)?
        App\Goal::Join('configurations','configuration_id','=','configurations.id')
                ->Join('periods','period_id','=','periods.id')
                ->Join('doings','doing_id','=','doings.id')
                ->Join('results','result_id','=','results.id')
                ->Join('targets','target_id','=','targets.id')
                ->Join('policys','policy_id','=','policys.id')
                ->Select('goals.id AS id', DB::raw("CONCAT(policys.code,'.',targets.code,'.',results.id,'.',doings.code,'.',goals.code) AS period"))
                ->Where('configurations.status',true)
                ->Where('goals.id',$action->goal->id)
                ->OrderBy('doings.id','ASC')
                ->OrderBy('configurations.id','DESC')
                ->OrderBy('goals.code','ASC')
                ->get()
                ->pluck('period','id')
        :
        App\Goal::Join('configurations','configuration_id','=','configurations.id')
                ->Join('periods','period_id','=','periods.id')
                ->Join('doings','doing_id','=','doings.id')
                ->Join('results','result_id','=','results.id')
                ->Join('targets','target_id','=','targets.id')
                ->Join('policys','policy_id','=','policys.id')
                ->Select('goals.id AS id', DB::raw("CONCAT(policys.code,'.',targets.code,'.',results.id,'.',doings.code,'.',goals.code) AS period"))
                ->Where('configurations.status',true)
                ->OrderBy('doings.id','ASC')
                ->OrderBy('configurations.id','DESC')
                ->OrderBy('goals.code','ASC')
                ->get()
                ->pluck('period','id'),
        isset($goal->id)?$goal->id:null,
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
      <div class="col-sm-6" id="descriptiontext"></div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('goal_id', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('description', 'DENOMINACIÓN', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-10">
        {!! Form::textarea('description',
            isset($action)?isset($action->definitions->first()->description)?$action->definitions->first()->description:null:null,
                        ('' == 'required') ? [
                            'class' => 'form-control',
                            'rows' => '2',
                            'maxlength' => '512',
                            'required' => 'required']:[
                            'class' => 'form-control',
                            'rows' => '2',
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
      {!! Form::label('describe', 'BIEN O SERVICIO', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-10">
        {!! Form::textarea('describe',
            isset($action)?isset($action->definitions->first()->describe)?$action->definitions->first()->describe:null:null,
                    ('' == 'required') ? [
                        'class' => 'form-control',
                        'rows' => '2',
                        'maxlengtht' => '128',
                        'required' => 'required']:[
                        'class' => 'form-control',
                        'rows' => '2',
                        'maxlength' => '128'])
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
      {!! Form::label('pointer', 'INDICADOR', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-6">
        {!! Form::text('pointer',
            isset($action)?isset($action->definitions->first()->pointer)?$action->definitions->first()->pointer:null:null,
                    ('' == 'required') ? [
                        'class' => 'form-control',
                        'maxlength' => '120',
                        'required' => 'required']:[
                        'class' => 'form-control',
                        'maxlength' => '120'])
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
      {!! Form::label('measure', 'FÓRMULA', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-10">
        {!! Form::textarea('measure',
            isset($action)?isset($action->definitions->first()->measure)?$action->definitions->first()->measure:null:null,
                    ('' == 'required') ? [
                        'class' => 'form-control',
                        'rows' => '2',
                        'maxlength' => '127',
                        'required' => 'required']:[
                        'class' => 'form-control',
                        'rows' => '2',
                        'maxlength' => '127'])
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
<!--    <tr>
            <th class="align-top text-left">
                {!! Form::label('measure', 'U. MEDIDA', ['class' => 'col-form-label']) !!}
  </th>
  <td class="align-top text-justify">
      <div class="col-sm-4">
{!! Form::text('measure',
                    isset($action)?isset($action->definitions->first()->measure)?$action->definitions->first()->measure:null:null,
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
{!! $errors->first('measure', '<p class="help-block">:message</p>') !!}
  </code>
</label>
</div>
</td>
</tr> -->
  <tr>
    <th class="align-top text-left">
      {!! Form::label('ponderation', 'PONDERACIÓN (%)', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-2">
        {!! Form::text('ponderation',
            isset($action)?isset($action->definitions->first()->ponderation)?$action->definitions->first()->ponderation:null:null,
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
            {!! $errors->first('ponderation', '<p class="help-block">:message</p>') !!}
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
      <div class="col-sm-2">
        {!! Form::text('base',
            isset($action)?isset($action->definitions->first()->base)?$action->definitions->first()->base:null:null,
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
      <div class="col-sm-2">
        {!! Form::text('aim',
            isset($action)?isset($action->definitions->first()->aim)?$action->definitions->first()->aim:null:null,
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
      {!! Form::label('validation', 'RESULTADO', ['class' => 'col-form-label' ]) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-10">
        {!! Form::textarea('validation',
            isset($action)?isset($action->definitions->first()->validation)?$action->definitions->first()->validation:null:null,
                    ('' == 'required') ? [
                            'class' => 'form-control',
                            'rows' => '2',
                            'maxlength' => '512',
                            'required' => 'required']:[
                            'class' => 'form-control',
                            'rows' => '2',
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
  <tr>
    <th class="align-top text-left">
      {!! Form::label('scode', 'COD. PROG.', ['class' => 'col-form-label', 'style' => 'color:#f96332']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-3">
        {!! Form::text('scode',
            isset($action)?isset($action->structures->first()->code)?$action->structures->first()->code:null:null,
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
            {!! $errors->first('scode', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('name', 'DENOMINACIÓN', ['class' => 'col-form-label', 'style' => 'color:#f96332']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-6">
        {!! Form::text('name',
            isset($action)?isset($action->structures->first()->name)?$action->structures->first()->name:null:null,
                    ('' == 'required') ? [
                        'class' => 'form-control',
                        'max' => '128',
                        'required' => 'required']:[
                        'class' => 'form-control',
                        'max' => '128'])
        !!}
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
<!--    <tr>
            <th class="align-top text-left">
                {!! Form::label('current', 'PRESUPUESTO CORRIENTE (Bs.)', ['class' => 'col-form-label', 'style' => 'color:#f96332']) !!}
  </th>
  <td class="align-top text-justify">
      <div class="col-sm-4">
{!! Form::text('current',
                    isset($action)?isset($action->structures->first()->current)?$action->structures->first()->current:null:null,
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
{!! $errors->first('current', '<p class="help-block">:message</p>') !!}
  </code>
</label>
</div>
</td>
</tr>
<tr>
<th class="align-top text-left">
{!! Form::label('inversion', 'PRESUPUESTO INVERSIÓN (Bs.)', ['class' => 'col-form-label', 'style' => 'color:#f96332']) !!}
  </th>
  <td class="align-top text-justify">
      <div class="col-sm-4">
{!! Form::text('inversion',
                    isset($action)?isset($action->structures->first()->inversion)?$action->structures->first()->inversion:null:null,
                            ('' == 'required') ? [
                                'class' => 'form-control',
                                'max' => '100',
                                'required' => 'required']:[
                                'class' => 'form-control',
                                'max' => '100'])
                !!}
  </div>
  <div class="col-sm-12">
  <label>
          <code>
{!! $errors->first('inversion', '<p class="help-block">:message</p>') !!}
  </code>
</label>
</div>
</td>
</tr>                                                -->
</table>
<!-- /.card-body -->

<div class="card-footer">
  {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'CREAR', ['class' => 'btn btn-secondary btn-sm']) !!}
  {!! Form::reset('BORRAR', ['class' => 'btn btn-danger btn-sm']) !!}
</div>

<script>
  window.onload = function () {
    var select = $("#goal_id option:selected").text();
    var valor = $("#goal_id").val();
    $.ajax({
      url: "/api/description",
      type: 'POST',
      data: {
        "id": valor,
        "type": "Goal"
      },
      success: function (data) {
        $('#descriptiontext').fadeIn();
        $('#descriptiontext').html(data);
      }
    })
  }

  function myFunction() {
    var select = $("#goal_id option:selected").text();
    var valor = $("#goal_id").val();
    $.ajax({
      url: "/api/description",
      type: 'POST',
      data: {
        "id": valor,
        "type": "Goal"
      },
      success: function (data) {
        $('#descriptiontext').fadeIn();
        $('#descriptiontext').html(data);
      }
    })
  }
</script>
