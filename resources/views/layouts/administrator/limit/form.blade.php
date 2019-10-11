<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
  <tr>
    <th class="align-top text-left" style="width: 120px">
      {!! Form::label('status',
                      'ESTADO',
                      ['class' => 'col-form-label']) !!}
    </th>
    <td class="align-top text-left">
      <div class="col-sm-3">
        {!! Form::checkbox('status', true,
            isset($limit->status)&&($limit->status)?true:false,
            ['class' => 'minimal'])
        !!}
      </div>
    </td>
  </tr>
  {{ Form::hidden('year_id', App\Year::Where('current',true)->first()->id) }}
  <tr>
    <th class="align-top text-left">
      {!! Form::label('year_id',
                      'GESTIÓN',
                      ['class' => 'col-form-label'])
      !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-2">
        {{ activeyear() }}
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('year_id', '<p class="help-block">:message</p>') !!}
          </code>
        </label>
      </div>
    </td>
  </tr>
  <tr class="align-top text-left">
    <th class="align-center text-left" style="width: 130px">
      {!! Form::label('month',
                      'MES',
                      ['class' => 'col-form-label h6'])
      !!}
    </th>
    <td>
      <div class="align-top text-left col-sm-6">
        {!! Form::select('month',
            App\Month::OrderBy('id','ASC')
                    ->get()
                    ->pluck('name','id'),
            isset($limit->month)?($limit->month):null,
                    ('' == 'required') ? [
                        'class' => 'form-control select2',
                        'required' => 'required',
                        'data-style' => "btn btn-info btn-round",
                        'title' => "Seleccione..."]:[
                        'class' => 'form-control select2',
                        'data-style' => "btn btn-info btn-round",
                        'title' => "Seleccione..."])
        !!}
      </div>
      <div class="text-left">
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
      {!! Form::label('date',
                      'FECHA',
                      ['class' => 'col-form-label'])
      !!}
    </th>
    <td class="align-top text-justify">
      <div class="col-sm-6">
        {!! Form::input('date', 'date',
            isset($limit->date)?
                date('Y-m-d', strtotime($limit->date)):
                null,
            ('' == 'required') ?
            ['class' => 'form-control', 'required' => 'required'] :
            ['class' => 'form-control'])
        !!}
      </div>
      <div class="col-sm-10">
        <label>
          <code>
            {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
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
