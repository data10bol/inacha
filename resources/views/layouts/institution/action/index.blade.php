@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'ACCIONES DE CORTO PLAZO',
          'url1' => $data["url1"],
          'addtop' => true,
          'pdf' => true,
          'urlpdf' => ''.Request::url().'?search='.Request::get('search').'&type=pdf',
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
                     aria-describedby="main table">
                @component('layouts.partials.theader',[
                    'arg'=>$header
                    ])
                @endcomponent
                <tbody>
                @php $tmp = 0; $tmp2 = 0; @endphp
                @forelse($action as $item)
                  <tr>
                    <td class="align-top text-center" style="width: 100px">
                      {{ $item->goal->code }}.{{
                                            $item->code }}<br>
                      @if(!$item->status)
                        <span class="badge badge-danger">REPROG</span>
                      @endif
                    </td>
                    <td class="align-top text-center">
                      {{ $item->year }}
                    </td>
                    <td class="align-top text-left">
                      @if($item->department->id != $tmp)
                        {{ $item->department->name }}
                      @endif
                    </td>
                    <td class="align-top text-justify">
                      @if($item->goal->id != $tmp2)
                        {!! substr($item->goal->description, 0, 200) !!}
                        {!! strlen($item->goal->description) > 200 ? '...' : '' !!}
                      @endif
                    </td>
                    <td class="align-top text-justify">
                      {!! substr($item->definitions->first()->description, 0, 200) !!}
                      {!! strlen($item->definitions->first()->description) > 200 ? '...' : '' !!}
                    </td>
                    <td class="align-top text-center">
                      {{ $item->definitions->first()->ponderation }}%
                    </td>
                    <td class="align-top text-center">
                      {{ App\Operation::Where('action_id',$item->id)->count() }}
                    </td>
                    @php
                      $to = current_ponderation($item->id);
                      
                    @endphp
                    <td>
                      <span class="badge @if($to<100) badge-danger @else badge-success @endif">
                        @if($to<100){{$to}} @else <i class="fa fa-check-circle"></i>@endif
                      </span> 
                    </td>
                    @component('layouts.partials.control',[
                        'id' => $item->id,
                        'url1' => $data["url1"],
                        'url2' => $data["url2"],
                        'add' => true,
                        'del' => true,
                    ])
                    @endcomponent
                  </tr>
                  @php
                      ($item->department->id != $tmp)?
                  $tmp=$item->department->id:null;
                  ($item->goal->id != $tmp2)?
                  $tmp2=$item->goal->id:null;
                  @endphp
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
        <div class="row">
        <div class="col-sm-12 col-md-5">
          <div class="dataTables_info" role="status" aria-live="polite">
          </div>
        </div>
        <div class="col-sm-12 col-md-7">
          <div class="pagination-wrapper">
            {!! $action->appends(['search' => Request::get('search')])->render() !!}
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
