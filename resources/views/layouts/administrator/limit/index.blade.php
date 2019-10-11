@extends('layouts.master')
@section('title', $title)
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'FECHAS LÍMITE',
          'url1' => $data["url1"],
          'search' => false,
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
                @forelse($limit as $item)
                  <tr>
                    <td class="align-top text-center" style="width: 30px">
                      {{ $loop->iteration }}
                    </td>
                    <td class="align-top text-center" style="width: 30px">
                      {{ $item->id }}
                    </td>
                    <td class="align-top text-center">
                      @statusvar($item->status)
                    </td>
                    <td class="align-top text-left">
                      {{ $item->year->name }}
                    </td>
                    <td class="align-top text-left">
                      {{ strtoupper(App\Month::Select('name')
                                          ->Where('id',$item->month)
                                          ->pluck('name')
                                          ->first())
                      }}
                    </td>
                    @php
                      setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
                      setlocale(LC_ALL,"es_BO.UTF-8");
                    @endphp
                    <td class="align-top text-left">
                      {{ date('D, d F Y', strtotime($item->date)) }}
                    </td>
                    @component('layouts.partials.control',[
                        'id' => $item->id,
                        'url1' => $data["url1"],
                        'url2' => $data["url2"],
                        'show' => false,
                        'add' => false,
                        'del' => false,
                        'editenable' => true,
                        ])
                    @endcomponent
                  </tr>
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
            {!! $limit->appends(['search' => Request::get('search')])->render() !!}
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
