@if(isset($plan->id))
  <input type="hidden" value="{{ $plan->position_id }}" name="pos_id" id="pos_id">
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
            isset($plan->status)&&($plan->status)?true:false,
            ['class' => 'minimal'])
        !!}
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
            isset($plan->position->department->id)?$plan->position->department->id:null,
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
      {!! Form::label('name', 'POAI', ['class' => 'col-form-label']) !!}
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
        str1 = str1.concat("<select name='position_id' id='department_id' class='form-control select2' title='Seleccione...'>");

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
  }

  function myFunction() {
    var select = $("#department_id option:selected").text();
    var valor = $("#department_id").val();
    var pos_id = $("#pos_id").val();
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
        str1 = str1.concat("<select name='position_id' id='department_id' class='form-control select2' title='Seleccione...'>");

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
  }

</script>
