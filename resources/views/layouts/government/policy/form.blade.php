<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
  <tr>
    <th class="align-top text-left" style="width: 120px">
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
                    'min' => '1',
                    ])
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
      {!! Form::label('id',
                      'PERÍODO', [
                      'class' => 'col-form-label'
                      ])
      !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-2">
        {!! Form::select('period_id',
            App\Period::Select('id AS id',
                    DB::raw("CONCAT(periods.start,'-',periods.finish) AS period"))
                            ->OrderBy('period','ASC')
                            ->get()
                            ->pluck('period','id'),
            isset($period->id)?$period->id:null,
            ('' == 'required') ? [
                'class' => 'selectpicker',
                'required' => 'required',
                'data-style' => "btn btn-info btn-round",
                'disabled' => isset($submitButtonText)?true:false,
                'title' => "Seleccione..."] : [
                'class' => 'form-control select2',
                'data-style' => "btn btn-info btn-round",
                'disabled' => isset($submitButtonText)?true:false,
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
      {!! Form::label('description',
                      'DESCRIPCIÓN',
                      ['class' => 'col-form-label'])
      !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-10">
        {!! Form::textarea('description',
            isset($description)?$description:null,
            ('' == 'required') ? [
                'class' => 'form-control ckeditor',
                'rows' => '6',
                'maxlength' => '255',
                'required' => 'required'] : [
                    'class' => 'form-control ckeditor',
                    'rows' => '3',
                    'maxlength' => '255'
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
  {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'CREAR',
  ['class' => 'btn btn-secondary btn-sm']) !!}
  {!! Form::reset('BORRAR', ['class' => 'btn btn-danger btn-sm']) !!}
</div>
