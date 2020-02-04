@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'EJECUCIÓN DE TAREAS',
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
              <table  class="table table-hover dataTable"
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
                    $mess = 'm'.activemonth();
                    
                  @endphp
                  @if(isset($item)&& (float)$item->poas->where('state',false)->pluck($mess)[0]>0)
                    <tr @if(!$item->status) class="text-muted" @endif>
                      <td class="align-top text-center ">
                        {{ $item->operation->action->goal->code }}.{{
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
                          $meep = 0;
                          for($i=1;$i<=12;$i++){
                            $totalprog += $prog["m".$i];
                            if($i==activemonth()){
                              $meep = $prog["m".$i];
                            }
                          }
                            
                        @endphp
                        {{ $totalprog }}%
                        @php
                            $month = 'm' . activemonth();                            
                          @endphp
                        @if(!empty($item->poas()->where('month', activemonth())->first()->$month))
                          <i class="fa fa-check-square text-success" title="Tarea Ejecutada"></i>
                        @else
                          @if ($meep>0)
                          <i class="fa fa-exclamation-triangle text-warning fa-blink" title="Tarea Pendiente"></i>
                          @endif
                        @endif
                        @if(!empty($item->operation->poas()->where('month', activemonth())->first()->$month))
                          <i class="fa fa-check-square text-success" title="Operacion Ejecutada"></i>
                        @endif
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
                      @php
                      $ed = true;
                      if ( $totalexec >= $totalprog ) {
                        $ed = false;
                      }
                      @endphp
                      @component('layouts.partials.control',[
                                  'id' => $item->id,
                                  'url1' => $data["url1"],
                                  'url2' => $data["url2"],
                                  'add' => false,
                                  'del' => false,
                                  'editenable' => ($item->status)? $ed : false,
                                  'reconfigureenable' => ($item->status)? $ed : false
                                ])
                      @endcomponent
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
    <!-- /.container-fluid -->
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