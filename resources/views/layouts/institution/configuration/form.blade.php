<table class="table table-bordered table-striped dataTable"
       role="grid"
       aria-describedby="main table">
    <tr>
      <th class="align-top text-left" style="width: 120px">
        {!! Form::label('status',
                        'ESTADO',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td colspan="3" class="align-top text-justify">
        <div class="col-sm-2">
          {!! Form::checkbox('status',
                              true,
          isset($configuration->status)&&($configuration->status)?true:false,
                              [])
          !!}
        </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left">
        {!! Form::label('edit',
                        'EDITAR',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td colspan="3" class="align-top text-justify">
        <div class="col-sm-2">
          {!! Form::checkbox('edit',
                              true,
          isset($configuration->edit)&&($configuration->edit)?true:false,
                              ['id' => 'editt',
                              'onchange' => 'tok();'])
          !!}
        </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left">
        {!! Form::label('reconfigure',
                        'REFORMULAR',
                        ['class' => 'col-form-label'])
        !!}        
      </th>
      <td colspan="3" class="align-top text-justify">
       <div class="row">
          <div class="col-sm-2">
              {!! Form::checkbox('reconfigure',
                                  true,
              isset($configuration->reconfigure)&&($configuration->reconfigure)?true:false,
                                  [
                                  'id' => 'reconf',
                                  'onchange' => 'verif();'
                                  ])
              !!}
              
            </div>
            @if (reprogcheck())
            <div class="col-sm-8">
                
                @php
                    $ref = App\Reformulation::where('month',activemonth())->where('year',activeyear())->first();
                @endphp  
                  @if(!is_null($ref))
                    <div class="alert alert-warning">
                      <strong>Reformulación Activada</strong>
                        {{$ref->reasons}}  
                    </div> 
                  @endif                
            </div>
            @endif
       </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left">
        {!! Form::label('period_id',
                        'GESTIÓN',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td colspan="3" class="align-top text-justify">
        <div class="col-sm-2">
          {!! Form::select('period_id',
              App\Period::Select('id AS id',
                      DB::raw("CONCAT(periods.start,'-',periods.finish) AS period"))
                              ->OrderBy('period','ASC')
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
              {!! $errors->first('period_id', '<p class="help-block">:message</p>') !!}
            </code>
          </label>
        </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left">
        {!! Form::label('mission',
                        'MISIÓN',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td colspan="3" class="align-top text-justify">
        <div class="col-sm-10">
          {!! Form::textarea('mission',
                              null,
          ('' == 'required') ? ['class' => 'form-control ckeditor',
                                  'rows' => '3',
                                  'maxlength' => '512',
                                  'required' => 'required'] :
                                  ['class' => 'form-control ckeditor',
                                  'rows' => '3',
                                  'maxlength' => '512'])
          !!}
        </div>
        <div class="col-sm-10">
          <label>
            <code>
              {!! $errors->first('mission', '<p class="help-block">:message</p>') !!}
            </code>
          </label>
        </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left">
        {!! Form::label('vision',
                        'VISIÓN',
                        ['class' => 'col-form-label'])
        !!}
      </th>
      <td colspan="3" class="align-top text-justify">
        <div class="col-sm-10">
          {!! Form::textarea('vision',
                              null,
          ('' == 'required') ? ['class' => 'form-control ckeditor',
                                  'rows' => '3',
                                  'maxlength' => '512',
                                  'required' => 'required'] :
                                  ['class' => 'form-control ckeditor',
                                  'rows' => '3',
                                  'maxlength' => '512'])
          !!}
        </div>
        <div class="col-sm-10">
          <label>
            <code>
              {!! $errors->first('vision', '<p class="help-block">:message</p>') !!}
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



<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Atención Modo Reformulación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          Atención usted esta activando el modo <strong>reformulación</strong>, para ello el sistema hará el corte de actividades ejecutadas al ultimo registro, las acciones de corto plazo y tareas quedaran deshabilitadas.
          <br>
          {!! Form::label('reasons',
                        'Razones de la Reformulación',
                        ['class' => 'col-form-label'])
          !!}
          <br>
          {{ Form::textarea('reasons',null,['id'=>'reasons', 'rows'=>'6', 'cols'=>'55'])}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="tik();" data-dismiss="modal">Confirmar</button>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script>
    function verif() {
      var token = $('input[name=_token]').val();
      var isChecked = document.getElementById('reconf').checked;
      if(isChecked){
        document.getElementById("reconf").checked = false;
        ///
        $.ajax({
            type: 'post',
            url: '/statusReprogramation',
            data: {
                '_token': token
            },
            success: function(data){
              if(data != 0){
                toastr.success('Se realizo una reprogramacion este año','Modo Reprogramacion Activado en este mes');
                $('textarea#reasons').val(data.reasons);
              }else{
                toastr.warning('Atención Modo Reformulación');
              }
                //con esto llenamos a todas las variables de forma de objetos
            }
        });
        $("#exampleModalCenter").modal();
      }
    }
    function tik(){
      document.getElementById("reconf").checked = true;
      document.getElementById("editt").checked = false;
      sve();
    }
    function tok(){
      document.getElementById('reconf').checked= false;
      
      //aqui debemos consultar si existe o no una reformulacion en ese mes y ese año
      //de ser asi llenamos en el campo correspondiente
    }
    function sve(){
      ///guardamos la reformulacion 
      //y lo ponemos en estado descactivado
      var token = $('input[name=_token]').val();
      var reasons = $('textarea#reasons').val();

      $.ajax({
            type: 'post',
            url: '/addReformulation',
            data: {
                '_token': token,
                'reasons': reasons
            },
            success: function(data){
              if(data == 1)
                toastr.success('Por favor guardar los cambios para que surtan efecto','Modo Reprogramacion Activado');
              else
                toastr.error('Fallo al asignar reprogamacion', 'Error al reprogramar');
                //con esto llnamos a todas las variables de forma de objetos
            }
      });
    }
</script>    
@endsection