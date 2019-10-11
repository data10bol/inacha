@if(isset($user->id))
  <input type="hidden" value="{{ $user->position_id }}" name="pos_id" id="pos_id">
  <input type="hidden" value="{{ $user->dependency }}" name="dep_id" id="dep_id">
  <input type="hidden" value="{{ $user->id }}" name="usr_id" id="usr_id">
@endif
<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
  <tr>
    <th class="align-top text-left" style="width: 120px">
      {!! Form::label('status', 'ESTADO', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-left">
      <div class="col-sm-3">
        {!! Form::checkbox('status', true,
            isset($user->status)&&($user->status)?true:false,
            ['class' => 'minimal'])
        !!}
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('employee', 'C.I.', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-3">
        {!! Form::text('employee', null, ('' == 'required') ?
        ['class' => 'form-control', 'required' => 'required'] :
        ['class' => 'form-control'])
        !!}
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('employee', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('username', 'USERNAME', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-3">
        {!! Form::text('username', null, ('' == 'required') ?
            ['class' => 'form-control', 'required' => 'required'] :
            ['class' => 'form-control'])
        !!}
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('username', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('password', 'PASSWORD', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-3">
        {!! Form::password('password', ('' == 'required') ?
        ['class' => 'form-control', 'required' => 'required'] :
        ['class' => 'form-control'])
        !!}
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('name', 'NOMBRE', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-5">
        {!! Form::text('name', null, ('' == 'required') ?
            ['class' => 'form-control', 'required' => 'required'] :
            ['class' => 'form-control'])
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
  <tr>
    <th class="align-top text-left">
      {!! Form::label('department_id', 'DEPARTAMENTO', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-6">
        {!! Form::select('department_id',
            App\Department::get()->pluck('name','id'),
            isset($user->position->department->id)?$user->position->department->id:null,
            ['class' => 'form-control select2', 'title' => "Seleccione...",
            'onchange' => "myFunction()"])
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
      {!! Form::label('position_id', 'CARGO', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-6" id="position">
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('position_id', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('dependency', 'DEPENDE', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-6" id="dependency">
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('dependency', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('permissions', 'PERMISOS', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-6">
        {!! Form::select('roles[]',
        Spatie\Permission\Models\Role::get()->pluck('name','name'),
        isset($user)?$user->getRoleNames():null,
        ('' == 'required') ?
        ['class' => 'form-control', 'required' => 'required','multiple'] :
        ['class' => 'form-control','multiple'])
        !!}
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('roles', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
</table>
<!-- /.card-body -->

<div class="card-footer">
  {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'CREAR', ['class' => 'btn btn-secondary btn-sm']) !!}
  {!! Form::reset('BORRAR', ['class' => 'btn btn-danger btn-sm']) !!}
</div>

<script>

  window.onload = function () {
    var select = $("#department_id option:selected").text();
    var valor = $("#department_id").val();
    var pos_id = $("#pos_id").val();
    var dep_id = $("#dep_id").val();
    var usr_id = $("#usr_id").val();
    //alert(valor);
    //alert(select);
    $.ajax({
      url: "/api/position",
      type: 'POST',
      data: {
        "id": valor
      },
      success: function (data) {
        $('#position').fadeIn();
        var str1 = "";
        str1 = str1.concat("<select name='position_id' id='position_id' class='form-control select2' title='Seleccione...'>");

        $.each(data, function (index, val) {
          str1 = str1.concat("<option value=' ");
          str1 = str1.concat(val.id);
          str1 = str1.concat("' ");
          if (val.id == pos_id)
            str1 = str1.concat("selected");
          str1 = str1.concat(">");
          str1 = str1.concat(val.name);
          str1 = str1.concat("</option>");
        });

        str1 = str1.concat("</select>");
        $('#position').html(str1);
      }
    })
    $.ajax({
      url: "/api/dependency",
      type: 'POST',
      data: {
        "id": valor
      },
      success: function (data) {
        $('#dependency').fadeIn();
        var str2 = "";
        str2 = str2.concat("<select name='dependency' id='dependency' class='form-control select2' title='Seleccione...'>");

        $.each(data, function (index, val) {
          if (val.id != usr_id) {
            str2 = str2.concat("<option value=' ");
            str2 = str2.concat(val.id);
            str2 = str2.concat("' ");
            if (val.id == dep_id)
              str2 = str2.concat("selected");
            str2 = str2.concat(">");
            str2 = str2.concat(val.name);
            str2 = str2.concat("</option>");
          }
        });

        str2 = str2.concat("</select>");
        $('#dependency').html(str2);
      }
    })
  }

  function myFunction() {
    var select = $("#department_id option:selected").text();
    var valor = $("#department_id").val();
    var pos_id = $("#pos_id").val();
    var dep_id = $("#dep_id").val();
    var usr_id = $("#usr_id").val();
    //alert(valor);
    //alert(select);
    $.ajax({
      url: "/api/position",
      type: 'POST',
      data: {
        "id": valor
      },
      success: function (data) {
        $('#position').fadeIn();
        var str1 = "";
        str1 = str1.concat("<select name='position_id' id='position_id' class='form-control select2' title='Seleccione...'>");

        $.each(data, function (index, val) {
          str1 = str1.concat("<option value=' ");
          str1 = str1.concat(val.id);
          str1 = str1.concat("' ");
          if (val.id == pos_id)
            str1 = str1.concat("selected");
          str1 = str1.concat(">");
          str1 = str1.concat(val.name);
          str1 = str1.concat("</option>");
        });

        str1 = str1.concat("</select>");
        $('#position').html(str1);
      }
    })
    $.ajax({
      url: "/api/dependency",
      type: 'POST',
      data: {
        "id": valor
      },
      success: function (data) {
        $('#dependency').fadeIn();
        var str2 = "";
        str2 = str2.concat("<select name='dependency' id='dependency' class='form-control select2' title='Seleccione...'>");

        $.each(data, function (index, val) {
          if (val.id != usr_id) {
            str2 = str2.concat("<option value=' ");
            str2 = str2.concat(val.id);
            str2 = str2.concat("' ");
            if (val.id == dep_id)
              str2 = str2.concat("selected");
            str2 = str2.concat(">");
            str2 = str2.concat(val.name);
            str2 = str2.concat("</option>");
          }
        });

        str2 = str2.concat("</select>");
        $('#dependency').html(str2);
      }
    })
  }

</script>
