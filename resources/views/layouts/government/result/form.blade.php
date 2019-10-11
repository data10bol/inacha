<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
  <tr>
    <th class="align-top text-left">
      {!! Form::label('code',
                      'CÓDIGO',
                      ['class' => 'col-form-label'])
      !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-2">
        {!! Form::number('code',
                isset($code)?$code:null,
                ('' == 'required') ? [
                    'class' => 'form-control',
                    'min' => '1',
                    'required' => 'required'] : [
                    'class' => 'form-control',
                    'min' => '1',])
        !!}
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('target_id',
                      'META', [
                      'class' => 'col-form-label'
                      ])
      !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-2">
        {!! Form::select('target_id',
        App\Target::Join('policys',
                        'policy_id','=','policys.id')
                    ->Join('periods',
                            'periods.id','=','policys.period_id')
                    ->Select('targets.id AS id',
                    DB::raw("CONCAT(policys.code,'.',targets.code) AS period"))
                    ->OrderBy('periods.id','DESC')
                    ->OrderBy('policys.code','ASC')
                    ->OrderBy('targets.code','ASC')
                    ->get()
                    ->pluck('period','id'),
                    isset($target->id)?
                        $target->id:
                        null,
                    ('' == 'required') ? [
                    'class' => 'form-control select2',
                    'required' => 'required',
                    'data-style' => "btn btn-info btn-round",
                    'title' => "Seleccione..."] : [
                    'class' => 'form-control select2',
                    'data-style' => "btn btn-info btn-round",
                    'title' => "Seleccione...",
                    'onchange'=>"myFunction()"
                    ])
        !!}
      </div>
      <div class="col-sm-10" id="descriptiontext"></div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('target_id', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('description',
                      'DESCRIPCIÓN', [
                      'class' => 'col-form-label'])
      !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-10">
        {!! Form::textarea('description',
                        isset($description)?$description:null,
                        ('' == 'required') ? [
                            'class' => 'form-control ckeditor',
                            'rows' => '3',
                            'maxlength' => '512',
                            'required' => 'required'] : [
                            'class' => 'form-control ckeditor',
                            'rows' => '3', 'maxlength' => '512'
                            ])
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

</table>
<!-- /.card-body -->

<div class="card-footer">
  {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'CREAR', ['class' => 'btn btn-secondary btn-sm']) !!}
  {!! Form::reset('BORRAR', ['class' => 'btn btn-danger btn-sm']) !!}
</div>

<script>
  window.onload = function () {
    var select = $("#target_id option:selected").text();
    var valor = $("#target_id").val();
    $.ajax({
      url: "/api/description",
      type: 'POST',
      data: {
        "id": valor,
        "type": "Target"
      },
      success: function (data) {
        $('#descriptiontext').fadeIn();
        $('#descriptiontext').html(data);
      }
    })
  }

  function myFunction() {
    var select = $("#target_id option:selected").text();
    var valor = $("#target_id").val();
    $.ajax({
      url: "/api/description",
      type: 'POST',
      data: {
        "id": valor,
        "type": "Target"
      },
      success: function (data) {
        $('#descriptiontext').fadeIn();
        $('#descriptiontext').html(data);
      }
    })
  }
</script>
