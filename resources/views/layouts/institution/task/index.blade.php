
@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'FORMULACIÓN DE TAREAS',
          'url1' => $data["url1"],
          'addtop' => false,
          'editenable' => true,
          'pdf' => true,
          'urlpdf' => ''.Request::url().'?search='.Request::get('search').'&type=pdf',
          'search' => false
      ])
      @endcomponent
     
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-hover dataTable"
                    role="grid"
                    aria-describedby="main table" id="task1">
                @component('layouts.partials.theader',[
                    'arg'=>$header
                    ])
                @endcomponent
                <tbody>
                @php $tmp = 0; $tmp2 = 0; @endphp
                @forelse($idt as $id)
                  @php
                    $item = $task->Where('id',$id)->first();
                  @endphp
                  @if(isset($item))
                    <tr @if(!$item->status) class="text-muted" @endif>
                      <td class="align-top text-center" style="width: 100px">
                        {{  $item->operation->action->goal->code }}.{{
                            $item->operation->action->code }}.{{
                            $item->operation->code }}.{{
                            $item->code }}<br>
                        @if(!$item->status)
                          <span class="badge badge-danger">ELIMINADA</span>
                        @endif
                      </td>
                      <td class="align-top text-justify">
                        @if($item->operation->action->department->id != $tmp2)
                          {{ $item->operation->action->department->name }}
                        @endif
                      </td>
                      <td class="align-top text-justify">
                        @if($item->operation->action->goal->doing->code.'.'.
                                $item->operation->action->goal->code.'.'.
                                $item->operation->action->code.'.'.
                                $item->operation->code  != $tmp)
                          {!! substr($item->operation->definitions->first()->description, 0, 200) !!}
                          {!! strlen($item->operation->definitions->first()->description) > 200 ? '...' : '' !!}
                        @endif
                      </td>
                      <td class="align-top text-justify">
                        {!! substr($item->definitions->pluck('description')->first(), 0, 200) !!}
                        {!! strlen($item->definitions->pluck('description')->first()) > 200 ? '...' : '' !!}
                      </td>
                      <td class="align-top text-left">
                        <ul>
                          @forelse ($item->users as $taskuser)
                            <li>{{ $taskuser->name }}</li>
                          @empty
                            <li>SIN REGISTROS</li>
                          @endforelse
                        </ul>
                      </td>
                      <td class="align-top text-center">
                        @php
                          $prog = App\Poa::Where('poa_type','App\Task')->Where('poa_id',$item->id)->first()->toarray();
                          $totalprog = 0;
                          for($i=1;$i<=12;$i++)
                              $totalprog += $prog["m".$i];
                        @endphp
                        {{ $totalprog }}%
                      </td>
                      <td class="align-top text-center">
                        @php
                          $totalexec = 0;
                          for($i=1;$i<=12;$i++)
                          {
                          $m = 'm'.$i;
                          isset($item->poas->Where('state',true)->Where('month',$i)->first()->$m)?
                              $totalexec += $item->poas->Where('state',true)->Where('month',$i)->first()->$m:
                              null;
                          }
                        @endphp
                        {{ $totalexec }}%
                      </td>
                      <td class="align-top text-center">
                        {{ ucfirst($months[$item->definitions->pluck('start')->first()]) }}
                      </td>
                      <td class="align-top text-center">
                        {{ ucfirst($months[$item->definitions->pluck('finish')->first()]) }}
                      </td>
                      @if(!$item->status)
                          @component('layouts.partials.control',[
                            'id' => $item->id,
                            'url1' => $data["url1"],
                            'url2' => $data["url2"],
                            'add' => false,
                            'modal' => false,
                            'status' => $item->status,
                            'editenable' => false,
                            'reconfigureenable' => false,
                          ])
                          @endcomponent
                      @else
                        @component('layouts.partials.control',[
                          'id' => $item->id,
                          'url1' => $data["url1"],
                          'url2' => $data["url2"],
                          'add' => false,
                          'modal' => ($item->status)?
                                                    (Auth::user()->hasPermissionTo('delete.task'))?
                                                      true
                                                      :
                                                      false
                                                    :
                                                    false,
                          'status' => $item->status,
                          'editenable' => ($item->status)?
                                                    (Auth::user()->hasPermissionTo('delete.task'))?
                                                      true
                                                      :
                                                      false
                                                    :
                                                    false,
                          'reconfigureenable' => ($item->status)?
                                                    (Auth::user()->hasPermissionTo('delete.task'))?
                                                      true
                                                      :
                                                      false
                                                    :
                                                    false,
                        ])
                        @endcomponent
                      @endif
                    </tr>
                    @php
                      ($item->operation->action->goal->doing->code.'.'.
                              $item->operation->action->goal->code.'.'.
                              $item->operation->action->code.'.'.
                              $item->operation->code != $tmp)?
                        $tmp=$item->operation->action->goal->doing->code.".".
                              $item->operation->action->goal->code.".".
                              $item->operation->action->code.".".
                              $item->operation->code:
                        null;
                      ($item->operation->action->department->id != $tmp2)?
                        $tmp2=$item->operation->action->department->id:
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

    </div>
    <div class="modal fade" id="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">ELIMINAR TAREA</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
          </div>
          <!-- Modal footer -->
          <div class="modal-footer">
            <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button> -->
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
    $('#task1').DataTable({
      "columnDefs": [ { "orderable": false, "targets": [6] } ]
    });
  });
  </script>
@endsection