@php
  if(!isset($modal))
      $modal = false;

  if(!isset($status))
      $modal = false;

  if(!isset($del))
    $del=false;

  if(!isset($add))
    $add=false;

  $path = explode("/",$url1);
  $user = Auth::user();

  if(isset($url2))
      $path2 = explode("/",$url2);
  else
      $add = false;

  if(!isset($editenable))
      $editenable = App\Configuration::Select('edit')->Where('status',true)->pluck('edit')->first();

@endphp

<div class="col-sm-12 col-md-6">
  <div align="right" class="dataTables_paginate paging_simple_numbers">
@php
    // {!! Form::button('<i class="fa fa-arrow-left"></i>',
    //             array('title' => 'Regresar',
    //                     'type' => 'button',
    //                     'class' => 'btn btn-default btn-sm',
    //                     'onclick'=>'window.location.href="'. $url1 .'"',))
    // !!}
@endphp
    <a href="javascript:history.go(-1)" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i></a>

    @if($type == 'show')
      {!! Form::button('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
                  array('title' => 'Editar',
                          'type' => 'button',
                          'class' => 'btn btn-secondary btn-sm',
                          'disabled' => ($user->hasPermissionTo('edit.'.end($path))&& $editenable ) ? null :'disabled',
                          'onclick'=>'window.location.href="'. $url1 .'/'. Hashids::encode($id).'/edit"', ))
      !!}
    @endif
    @if($modal)
      <a href="{{ url( $url1 .'/' . Hashids::encode($id) . '/delete') }}"
         rel="modal" {!! !($status)?'onclick="return false;':null !!}>
        {!! Form::button('<i class="fa fa-trash"></i>',
                array('title' => 'Eliminar',
                    'type' => 'button',
                    'class' => 'btn btn-danger btn-round btn-sm',
                    'disabled' => ($user->hasPermissionTo('delete.'.end($path))&&$editenable) ? null :'disabled', ))
        !!}
      </a>
    @else
      @if(($type == 'edit' || $type == 'show'))
        {!! Form::open([ 'method'=>'DELETE',
                            'url' => [$url1, Hashids::encode($id)],
                            'style' => 'display:inline'])
        !!}
        {!! Form::button('<i class="fa fa-trash"></i>',
                array('type' => 'submit',
                        'class' => 'btn btn-danger btn-sm',
                        'title' => 'Eliminar',
                        'disabled' => !$del?'disabled':
                        (($user->hasPermissionTo('delete.'.end($path))&&$editenable) ? null :'disabled'),
                        'onclick'=>'return confirm("¿Desea eliminar?")' ))
        !!}
        {!! Form::close() !!}
      @endif
    @endif
    @if(($type == 'edit' || $type == 'show'))
      {!! Form::open([ 'method'=>'GET',
                      'url' => [$url2.'/create'],
                      'style' => 'display:inline'])
      !!}
      {{ Form::hidden('id', $id) }}
      {!! Form::button('<i class="fa fa-plus-square-o"></i>',
                  array('type' => 'submit',
                          'class' => 'btn btn-dark btn-sm',
                          'title' => 'Agregar',
                  'disabled' => !$add?'disabled':
                  ($user->hasPermissionTo('create.'.end($path2)) ? null :'disabled'),
                  'onclick'=>'return confirm("Agregará a la selección")' ))
      !!}
      {!! Form::close() !!}
    @endif

  </div>
</div>

