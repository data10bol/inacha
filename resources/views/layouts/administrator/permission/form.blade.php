<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
  <tr>
    <th class="align-top text-left" style="width: 100px">
      {!! Form::label('name', 'NOMBRE', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-left">
      <div class="col-sm-6">
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
