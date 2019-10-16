@php
  $month = 'm' . $month;
  $type = strtolower($type);
@endphp

<div class="row">
  @forelse($items as $item)
    @if(empty($item->poas()->
    where('month', activemonth())->
    first()->
    $month) &&
    ($item->poas()->
    where('month', '0')->
    Where('state',false)->
    first()->
    $month) > 0 )
      <div class="col-3">
        <div class="card card-dark card-outline">
          <div class="card-header">
            @php
                $ban = false;
            @endphp
            @if($type == 'operation')
              <h3 class="m-0">Operación
                {{   $item->action->goal->code }}.{{
                                $item->action->code }}.{{
                                $item->code }}
              </h3>
              @php
                $ar = quantity_exe($item->id,'operation',activemonth());
              @endphp
                @if ($ar[0]==$ar[1])
                <i class="fa fa-check-square text-success"></i>
                @php
                  $month = 'm' . activemonth();                            
                @endphp
                  @if(!empty($item->poas()->where('month', activemonth())->first()->$month))
                    <i class="fa fa-check-square text-success"></i>
                    @php
                    $ar[3] = false;
                    //ok
                    @endphp
                  @endif
                @else
                <span tabindex="0" role="button" class="badge badge-{{$ar[2]}}" data-toggle="popover" data-trigger="focus" title="Pendientes de Ejecución" data-content="{{$ar[4]}}" ><small>{{$ar[1]}} / {{$ar[0]}}</small></span> Tareas Pendientes
                @php
                    $ban = true;
                @endphp
                @endif

            @elseif($type == 'task')
              <h3 class="m-0">TAREA
                {{   $item->operation->action->goal->code }}.{{
                              $item->operation->action->code }}.{{
                              $item->operation->code }}.{{
                              $item->code }}
              </h3>
            @endif
          </div>
          <div class="card-body">

            <p class="card-text">
              {{ $item->definitions->first()->description }}
            </p>
            @if($type == 'operation')
              <p class="card-text"></p>
              <p class="card-text">
                {{ $item->action->department->name }}
              </p>
            @elseif($type == 'task')
              <p class="card-text"> RESPONSABLE(S) </p>
              <p class="card-text">
              @forelse ($item->users as $taskuser)
                <li>{{ $taskuser->name }}</li>
              @empty
                <li>SIN REGISTROS</li>
              @endforelse
              </p>
            @endif
            {!! Form::button('Avance',
                array('title' => 'Registrar',
                        'type' => 'button',
                        'class' => 'btn btn-danger',
                        'disabled' => $ban,
                        'onclick'=>'window.location.href="/execution/'.$type.'/'.
                        Hashids::encode($item->id).'/edit"', ))
            !!}
          </div>
        </div>
      </div>
    @endif
  @empty
    <div class="col-3">
      <div class="card card-danger card-outline">
        <div class="d-flex justify-content-between">
          <div class="card-header">
            <h3 class="m-0">SIN REGISTROS </h3>
          </div>
          <div class="card-body">
            <p class="card-text">
              SIN REGISTROS
            </p>
          </div>
        </div>
      </div>
    </div>
  @endforelse
</div>
@section('scripts')
<script>
  $(document).ready(function(){
    $('[data-trigger="focus"]').popover({ 
          html:true ,
          trigger: 'focus'
        });  
  });
  </script>
@endsection