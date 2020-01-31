@extends('layouts.master')
@section('content')
@php
    $user = Auth::user();
@endphp
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'FORMULACIÓN DE OPERACIONES',
          'url1' => $data["url1"],
          'addtop' => false,
          'pdf' => true,
          'urlpdf' => ''.Request::url().'?search='.Request::get('search').'&type=pdf',
          'search' =>false
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
              <table class="table table-hover dataTable"
                    role="grid"
                    aria-describedby="main table" id="operation1">
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
                  @if(isset($item))
                    <tr>
                      <td class="align-top text-center" width="100">
                        {{ $item->action->goal->code }}.{{
                                                $item->action->code }}.{{
                                                $item->code }}<br>
                        @if(!$item->status)
                          <span class="badge badge-danger">REPROG</span>
                        @endif
                      </td>
                      <td class="align-top text-center">
                        {{ $item->action->year }}
                      </td>
                      <td class="align-top text-center">
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
                      @php
                        $pon = $item->definitions->pluck('ponderation')->last();
                      @endphp
                      <td class="align-top text-justify">
                          @if ($pon == 0)
                          <span class="badge badge-danger"><small><i class="fa fa-warning"></i></small></span> 
                          @endif
                        {!! substr($item->definitions->last()->description, 0, 200) !!}
                        {!! strlen($item->definitions->last()->description) > 200 ? '...' : '' !!}
                      </td>
                      <td class="align-top text-center">
                        {{ $pon }}%
                      </td>
                      <td class="align-top text-center">
                        {!!($item->definitions->last()->department)?$item->definitions->last()->department->initial:null!!}
                        [{!!$item->definitions->last()->dep_ponderation!!}%]
                      </td>
                      <td class="align-top text-center">
                        {{ ucfirst($months[$item->definitions->pluck('start')->first()]) }}
                      </td>
                      <td class="align-top text-center">
                        {{ ucfirst($months[$item->definitions->pluck('finish')->first()]) }}
                      </td>
                      <td class="align-top text-center">
                        {{ App\Task::Where('operation_id',$item->id)->where('status',1)->count() }}
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
                     
                      @if ($pon>0)
                          @component('layouts.partials.control',[
                            'id' => $item->id,
                            'url1' => $data["url1"],
                            'url2' => $data["url2"],
                            'add' => $user->hasPermissionTo('create.operation'),
                            'del' => true,
                          ])
                          @endcomponent
                      @else
                      @component('layouts.partials.control',[
                        'id' => $item->id,
                        'url1' => $data["url1"],
                        'url2' => $data["url2"],
                        'add' => false,
                        'del' => false,
                    ])
                    @endcomponent
                      @endif
                      
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
      @unlessrole('Responsable')
        <div class="row">
        <div class="col-sm-12 col-md-5">
          <div class="dataTables_info" role="status" aria-live="polite">
          </div>
        </div>
        <div class="col-sm-12 col-md-7">
          <div class="pagination-wrapper">
            {!! $operation->appends(['search' => Request::get('search')])->render() !!}
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
  $(document).ready(function() {
    $('#operation1').DataTable({
      "columnDefs": [ { "orderable": false, "targets": [11] } ]
    });
  });
  </script>
@endsection