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
            isset($position->status)&&($position->status)?true:false,
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
            isset($position->department_id)?$position->department_id:null,
            ['class' => 'form-control select2', 'title' => "Seleccione..."])
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
      {!! Form::label('name', 'CARGO', ['class' => 'col-form-label']) !!}
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
