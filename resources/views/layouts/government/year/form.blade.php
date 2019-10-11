<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
    <tr>
      <th class="align-top text-left">
        {!! Form::label('status',
                        'ESTADO',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td class="align-top text-justify">
        <div class="col-sm-2">
          {!! Form::checkbox('status', true,
              isset($year->status)&&($year->status)?true:false,
                  ['class' => 'minimal'])
          !!}
        </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left">
        {!! Form::label('current',
                        'ACTUAL',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td class="align-top text-justify">
        <div class="col-sm-2">
          {!! Form::checkbox('current', true,
              isset($year->current)&&($year->current)?true:false,
                  ['class' => 'minimal'])
          !!}
        </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left">
        {!! Form::label('period_id',
                        'PERÍODO', [
                        'class' => 'col-form-label'
                        ])
        !!}
      </th>
      <td class="align-top text-justify">
        <div class="col-sm-6">
          {!! Form::select('period_id',
              App\Period::Select('id AS id',
                      DB::raw("CONCAT(periods.start,'-',periods.finish) AS period"))
                              ->OrderBy('period','ASC')
                              ->get()
                              ->pluck('period','id'),
              isset($year->period_id)?$year->period_id:null,
              ('' == 'required') ? [
                  'class' => 'selectpicker',
                  'required' => 'required',
                  'data-style' => "btn btn-info btn-round",
                  'disabled' => 'disabled',
                  'title' => "Seleccione..."] : [
                  'class' => 'form-control select2',
                  'data-style' => "btn btn-info btn-round",
                  'disabled' => 'disabled',
                  'title' => "Seleccione..."
                  ])
          !!}
        </div>
        <div class="col-sm-10">
          <label>
            <code>
              {!! $errors->first('period_id', '<p class="help-block">:message</p>') !!}
            </code>
          </label>
        </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left">
        {!! Form::label('name',
                        'GESTIÓN',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td class="align-top text-justify">
        <div class="col-sm-4">
          {!! Form::text('name',
              isset($year->name)?$year->name:null,
              ('' == 'required') ? [
                  'class' => 'form-control',
                  'maxlength' => '4',
                  'disabled' => 'true',
                  'required' => 'required'] : [
                      'class' => 'form-control ',
                      'maxlength' => '4',
                      'disabled' => 'true',
                      ])
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
  {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'CREAR',
  ['class' => 'btn btn-secondary btn-sm']) !!}
  {!! Form::reset('BORRAR', ['class' => 'btn btn-danger btn-sm']) !!}
</div>
