@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'EJECUCIÓN DE OPERACIONES',
          'url1' => $data["url1"],
          'add' => false,
          'addtop' => false,
          'pdf' => true,
          'urlpdf' => ''.Request::url().'?search='.Request::get('search').'&type=pdf',
          'search' => false
      ])
      @endcomponent
      <!-- /.card-header -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <marquee align="center" width="50%" bgcolor="red" behavior="scroll" direction="left">
                <font color="white">
                  <strong>
                    ANTES DE EJECUTAR UNA OPERACIÓN, VERIFIQUE QUE TODAS LAS TAREAS FUERON CUMPLIDAS.
                  </strong>
                </font>
              </marquee>
              <table class="table table-hover dataTable"
                     role="grid"
                     aria-describedby="main table" id="operation2">
                @component('layouts.partials.theader',[
                    'arg'=>$header
                    ])
                @endcomponent
                <tbody>
                @php $tmp = 0; $tmp2 = 0; @endphp
                @forelse($ido as $id)
                  @php
                    $item = $operation->Where('id',$id)->first();
                  @endphp
                  @if(isset($item) && accum($item->id, 'Operation', false, activemonth())>0)
                    <tr>
                      <td class="align-top text-center">
                        {{ $item->action->goal->code }}.{{
                                                    $item->action->code }}.{{
                                                    $item->code }} <br>
                        @if(!$item->status)
                          <span class="badge badge-danger">ELIMINADA</span>
                        @endif
                      </td>
                      <td class="align-top text-center">
                        {{ $item->action->year }}
                      </td>
                      <td class="align-top text-justify">
                        @if($item->action->department->id != $tmp2)
                          {{ $item->action->department->name }}
                        @endif
                      </td>
                      <td class="align-top text-justify">
                        @if($item->action->goal->doing->code.'.'.
                        $item->action->goal->code.'.'.
                        $item->action->code != $tmp)
                          {!! substr($item->action->definitions->first()->description, 0, 200) !!}
                          {!! strlen($item->action->definitions->first()->description) > 200 ? '...' : '' !!}
                        @endif
                      </td>
                      <td class="align-top text-justify">
                        {!! substr($item->definitions->pluck('description')->last(), 0, 200) !!}
                        {!! strlen($item->definitions->pluck('description')->last()) > 200 ? '...' : '' !!}
                      </td>
                      <td class="align-top text-center">
                        {{ accum($item->id, 'Operation', false, 12) }}%
                      </td>
                      <td class="align-top text-center">
                        {{ accum($item->id, 'Operation', true, activemonth()) }}%
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
                            @endphp
                          @else
                            <i class="fa fa-exclamation-triangle text-warning fa-blink" title="Operación Pendiente"></i>
                          @endif
                        @else
                          <span tabindex="0" role="button" class="badge badge-{{$ar[2]}}" data-toggle="popover" data-trigger="focus" title="Pendientes de Ejecución" data-content="{{$ar[4]}}" ><small>{{$ar[1]}} / {{$ar[0]}}</small></span>
                        @endif
                      </td>
                      <td class="align-top text-center">
                        {{ ucfirst($months[$item->definitions->pluck('start')->last()]) }}
                      </td>
                      <td class="align-top text-center">
                        {{ ucfirst($months[$item->definitions->pluck('finish')->last()]) }}
                      </td>
                      <td class="align-top text-center">
                        {{ App\Task::Where('operation_id',$item->id)->where('status',true)->count() }}
                      </td>
                      <td>
                        @php
                            $cant = quantity_poa_rep($item->id, 'operation');
                        @endphp
                        @if ($cant>0)
                        <span class="badge badge-danger"><small>R. ({{$cant}})</small></span> 
                        @else
                        <span class="badge badge-success"><small>V.</small></span> 
                        @endif
                      </td>
                      @component('layouts.partials.control',[
                                  'id' => $item->id,
                                  'url1' => $data["url1"],
                                  'url2' => $data["url2"],
                                  'add' => false,
                                  'del' => false,
                                  'editenable' => $ar[3],
                                  'reconfigureenable' => $ar[3]
                                ])
                      @endcomponent
                    </tr>
                    @php
                    ($item->action->goal->doing->code.'.'.
                            $item->action->goal->code.'.'.
                            $item->action->code != $tmp)?
                    $tmp=$item->action->goal->doing->code.".".
                    $item->action->goal->code.".".
                    $item->action->code:
                    null;
                    ($item->action->department->id != $tmp2)?
                    $tmp2=$item->action->department->id:
                    null;
                    @endphp
                  @endif
                @empty
                  {!! emptyrecord(count($header)+1) !!}
                @endforelse
                </tbody>
                @component('layouts.partials.tfoot',[
                    'arg'=>$header
                    ])
                @endcomponent
              </table>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
      @unlessrole('Responsable|Usuario')
      <div class="row">
        <div class="col-sm-12 col-md-5">
          <div class="dataTables_info" role="status" aria-live="polite">
          </div>
        </div>
        <div class="col-sm-12 col-md-7">
          <div class="pagination-wrapper">
            
          </div>
        </div>
      </div>
      @endunlessrole
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
@section('scripts')
<script>
  $(document).ready(function(){
    $('[data-trigger="focus"]').popover({ 
          html:true ,
          trigger: 'focus'
        });  
  });
  $(document).ready(function() {
    $('#operation2').DataTable({
      "columnDefs": [ { "orderable": false, "targets": [11] } ]
    });
  });
  </script>
@endsection