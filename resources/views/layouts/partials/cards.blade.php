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
            @if($type == 'operation')
              <h3 class="m-0">Operación
                {{   $item->action->goal->code }}.{{
                                $item->action->code }}.{{
                                $item->code }}
              </h3>
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
