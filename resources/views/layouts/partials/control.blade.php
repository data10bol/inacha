@php

  if(!isset($modal))
      $modal = false;

  if(!isset($status))
      $modal = false;

  if(!isset($show))
    $show = true;

  if(!isset($add))
    $add = false;

  if(!isset($del))
      $del = false;

  $path = explode("/",$url1);
  $user = Auth::user();

  if(isset($url2))
      $path2 = explode("/",$url2);
  else
      $add = false;

  if(!isset($editenable)){
      $editenable = App\Configuration::Select('edit')->
                              Where('status',true)->
                              pluck('edit')->
                              first();
      $reconfigureenable = App\Configuration::Select('reconfigure')->
                              Where('status',true)->
                              pluck('reconfigure')->
                              first();
  }

@endphp
<td class="align-top text-center" style="width: 170px">
  {!! Form::button('<i class="fa fa-eye"></i>',
                  array('title' => 'Ver',
                          'type' => 'button',
                          'class' => 'btn btn-default btn-sm',
                          'disabled' => ($show)?
                                          (($user->hasPermissionTo('read.'.end($path)))? 
                                                    null : 'disabled')
                                        :'disabled',

                          'onclick'=>isset($extra)?
                                                    'window.location.href="'. $url1 .'/'. Hashids::encode($id).'?month='.$extra.'"':
                                                    'window.location.href="'. $url1 .'/'. Hashids::encode($id).'"', ))
  !!}
  {!! Form::button('<i class="fa fa-pencil-square-o"></i>',
                  array('title' => 'Editar',
                          'type' => 'button',
                          'class' => 'btn btn-secondary btn-sm',
                          'disabled' => ($user->hasPermissionTo('edit.'.end($path)) && ($editenable || $reconfigureenable)) ? null :'disabled',
                          'onclick'=>'window.location.href="'. $url1 .'/'. Hashids::encode($id).'/edit"', ))
  !!}
  @if($modal)
    <a href="{{ url( $url1 .'/' . Hashids::encode($id) . '/delete') }}"
       rel="modal" {!! !($status)?'onclick="return false;':null !!}>
      {!! Form::button('<i class="fa fa-trash"></i>',
                    array('type' => 'submit',
                            'class' => 'btn btn-danger btn-sm',
                            'title' => 'Eliminar',
                            'disabled' => $del?'disabled':
                            (($user->hasPermissionTo('delete.'.end($path))&&$editenable) ? null :'disabled'),
                            'onclick'=>'return confirm("¿Desea eliminar?")' ))
    !!}
    </a>
  @else
    {!! Form::open([ 'method'=>'DELETE',
                    'url' => [$url1, Hashids::encode($id)],
                    'style' => 'display:inline'])
    !!}
    {!! Form::button('<i class="fa fa-trash"></i>',
                    array('type' => 'submit',
                            'class' => 'btn btn-danger btn-sm',
                            'title' => 'Eliminar',
                            'disabled' => $del?'disabled':
                            (($user->hasPermissionTo('delete.'.end($path))&&$editenable) ? null :'disabled'),
                            'onclick'=>'return confirm("¿Desea eliminar?")' ))
    !!}
    {!! Form::close() !!}
  @endif

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

</td>
