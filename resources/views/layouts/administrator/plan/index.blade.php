@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'PLANES OPERATIVOS ANUALES INDIVIDUALES',
          'url1' => $data["url1"],
          'editenable' => true,
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
                @forelse($plan as $item)
                  <tr>
                    <td class="align-top text-center" style="width: 30px">
                      {{ $loop->iteration }}
                    </td>
                    <td class="align-top text-left" style="width: 30px">
                      {{ $item->id }}
                    </td>
                    <td class="align-top text-center">
                      @statusvar($item->status)
                    </td>
                    <td class="align-top text-left">
                      @if($item->position_id != $tmp2)
                      {{ $item->department }}
                      @endif
                    </td>
                    <td class="align-top text-left">
                      @if($item->position_id != $tmp2)
                      {{ $item->position }}
                      @endif
                    </td>
                    <td class="align-top text-left">{{ $item->name }}</td>
                    @component('layouts.partials.control',[
                        'id' => $item->id,
                        'url1' => $data["url1"],
                        'url2' => $data["url2"],
                        'show' => false,
                        'add' => false,
                        'del' => true,
                        'editenable' => true,
                    ])
                    @endcomponent
                  </tr>
                  @php
                     ($item->department_id != $tmp)?
                  $tmp=$item->department_id:null;
                  ($item->position_id != $tmp2)?
                  $tmp2=$item->position_id:null;
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
            {!! $plan->appends(['search' => Request::get('search')])->render() !!}
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
