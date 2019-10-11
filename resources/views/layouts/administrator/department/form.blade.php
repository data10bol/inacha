<table class="table table-bordered table-striped dataTable"
         role="grid"
         aria-describedby="main table">
  <tr>
    <th class="align-top text-left">
      {!! Form::label('name', 'NOMBRE', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
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
  <tr>
    <th class="align-top text-left">
      {!! Form::label('dependency',
                      'DEPARTAMENTO',
                      ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-6">
        {!! Form::select('dependency',
            App\Department::get()->pluck('name','id'),
            isset($department->dependency)?$department->dependency:null,
            ['class' => 'form-control select2', 'title' => "Seleccione..."])
        !!}
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
      {!! Form::label('user_id', 'RESPONSABLE', ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-6">
        {!! Form::select('user_id',
            App\User::OrderBy('name','ASC')->get()->pluck('name','id'),
            isset($department->user_id)?$department->user_id:null,
            ['class' => 'form-control select2', 'title' => "Seleccione..."])
        !!}
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
</table>
<!-- /.card-body -->

<div class="card-footer">
  {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'CREAR', ['class' => 'btn btn-secondary btn-sm']) !!}
  {!! Form::reset('BORRAR', ['class' => 'btn btn-danger btn-sm']) !!}
</div>
