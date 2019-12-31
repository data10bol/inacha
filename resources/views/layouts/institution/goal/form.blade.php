<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
    <tr>
      <th class="align-top text-left" style="width: 120px">
        {!! Form::label('configuration_id',
                        'PERÍODO',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td colspan="3" class="align-top text-justify">
        <div class="col-sm-2">
          {!! Form::select('configuration_id',
              App\Configuration::Join('periods','period_id','=','periods.id')
              ->Select('configurations.id AS id',  DB::raw("CONCAT(periods.start,'-',periods.finish) AS period"))
              ->where('periods.status',true)
              ->get()
              ->pluck('period','id'),
              isset($period->id)?$period->id:null,
              ('' == 'required') ? [
                  'class' => 'form-control select2',
                  'required' => 'required',
                  'data-style' => "btn btn-info btn-round",
                  'title' => "Seleccione..."] : [
                  'class' => 'form-control select2',
                  'data-style' => "btn btn-info btn-round",
                  'title' => "Seleccione..."
                  ])
          !!}
        </div>
        <div class="col-sm-10">
          <label>
            <code>
              {!! $errors->first('configuration_id', '<p class="help-block">:message</p>') !!}
            </code>
          </label>
        </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left" style="width: 120px">
        {!! Form::label('year',
                        'GESTIÓN',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td >
        <div class="col-sm-2">
        @php
            if(isset($goal)){
              $deef = null;
            }else{
              $deef = activeyear();
            }
        @endphp
        {!! Form::select('year',['2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020'], $deef,
        [
          'class' => 'form-control select2',
          'data-style' => "btn btn-info btn-round",
          'title' => "Seleccione..."
          ])!!}
        </div>
      </td>
    </tr>
    @if(isset($doing))
      <tr>
        <th class="align-top text-left">
          {!! Form::label('doing_id',
                          'ACCIÓN', [
                          'class' => 'col-form-label'
                          ])
          !!}
        </th>
        <td class="align-top text-justify">
          <div class="col-sm-2">
            {{ Form::hidden('doing_id', $doing->id) }}
            {{ $doing->result->target->policy->code
            }}.{{ $doing->result->target->code
                                }}.{{ $doing->result->id
                                }}.{{ $doing->code }}
          </div>
          <div class="col-sm-10" id="descriptiontext"></div>
          <div class="col-sm-10">
            <label>
              <code>
                {!! $errors->first('doing_id', '<p class="help-block">:message</p>') !!}
              </code>
            </label>
          </div>
        </td>
      </tr>
    @else
      <tr>
        <th class="align-top text-left">
          {!! Form::label('doing_id',
                          'ACCIÓN', [
                          'class' => 'col-form-label'
                          ])
          !!}
        </th>
        <td class="align-top text-justify">
          <div class="col-sm-2">
            {!! Form::select('doing_id',
            App\Doing::Join('results',
                            'result_id','=','results.id')
                        ->Join('targets',
                            'target_id','=','targets.id')
                        ->Join('policys',
                                'policy_id','=','policys.id')
                        ->Join('periods',
                                'period_id','=','periods.id')
                        ->Select('doings.id AS id',
                        DB::raw("CONCAT(policys.code,'.',targets.code,'.',results.id,'.',doings.code) AS period"))
                        ->Where('periods.status',true)
                        ->OrderBy('periods.id','DESC')
                        ->OrderBy('policys.code','ASC')
                        ->OrderBy('targets.code','ASC')
                        ->OrderBy('results.id','ASC')
                        ->OrderBy('doings.code','ASC')
                        ->get()
                        ->pluck('period','id'),
                        isset($doing->id)?
                            $doing->id:
                            null,
                        ('' == 'required') ? [
                        'class' => 'form-control select2',
                        'required' => 'required',
                        'data-style' => "btn btn-info btn-round",
                        'title' => "Seleccione..."] : [
                        'class' => 'form-control select2',
                        'data-style' => "btn btn-info btn-round",
                        'title' => "Seleccione...",
                        'onchange'=>"myFunction()"])
            !!}
          </div>
          <div class="col-sm-10" id="descriptiontext"></div>
          <div class="col-sm-10">
            <label>
              <code>
                {!! $errors->first('doing_id', '<p class="help-block">:message</p>') !!}
              </code>
            </label>
          </div>
        </td>
      </tr>
    @endif
    <tr>
      <th class="align-top text-left">
        {!! Form::label('description',
                        'DESCRIPCIÓN',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td colspan="3" class="align-top text-justify">
        <div class="col-sm-10">
          {!! Form::textarea('description',
                              null,
          ('' == 'required') ? ['class' => 'form-control ckeditor',
                                  'rows' => '3',
                                  'maxlength' => '512',
                                  'required' => 'required'] :
                                  ['class' => 'form-control',
                                  'rows' => '3',
                                  'maxlength' => '512'])
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
    var select = $("#doing_id option:selected").text();
    var valor = $("#doing_id").val();
    $.ajax({
      url: "/api/description",
      type: 'POST',
      data: {
        "id": valor,
        "type": "Doing"
      },
      success: function (data) {
        $('#descriptiontext').fadeIn();
        $('#descriptiontext').html(data);
      }
    })
  }

  function myFunction() {
    var select = $("#doing_id option:selected").text();
    var valor = $("#doing_id").val();
    $.ajax({
      url: "/api/description",
      type: 'POST',
      data: {
        "id": valor,
        "type": "Doing"
      },
      success: function (data) {
        $('#descriptiontext').fadeIn();
        $('#descriptiontext').html(data);
      }
    })
  }
</script>
