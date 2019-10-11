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
            isset($period->status)&&($period->status)?true:false,
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
            isset($period->current)&&($period->current)?true:false,
                ['class' => 'minimal'])
        !!}
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left" style="width: 100px">
      {!! Form::label('start',
                      'INICIO',
                      ['class' => 'col-form-label'])
      !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-3">
        {!! Form::text('start',
                        isset($period->start)?$period->start:null,
                        ['class' => 'form-control',
                        'maxlength'=> '4',
                        'disabled' => isset($submitButtonText)?true:null,
                        'required' => 'required'])
        !!}
      </div>
      <div class="col-sm-4">
        <label>
          <code>
            {!! $errors->first('start', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <th class="align-top text-left">
      {!! Form::label('finish',
                      'FINAL',
                      ['class' => 'col-form-label'])
      !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-3">
        {!! Form::text('finish',
                        isset($period->start)?$period->finish:null,
                        ['class' => 'form-control',
                        'maxlength'=> '4',
                        'disabled' => isset($submitButtonText)?true:null,
                        'required' => 'required'])
        !!}
      </div>
      <div class="col-sm-6">
        <label>
          <code>
            {!! $errors->first('finish', '<p class="help-block">:message</p>') !!}
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
