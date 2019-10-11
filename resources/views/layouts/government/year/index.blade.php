@extends('layouts.master')
@section('content')
  <section>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
        @component('layouts.partials.icontrol',[
    'title' => 'GESTIONES',
    'search' => false,
    'url1' => $data["url1"],
    'addtop' => false,
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
                  @forelse($year as $item)
                    <tr>
                      <td class="align-top text-center" style="width: 30px">
                        {{ $loop->iteration }}
                      </td>
                      <td class="align-top text-left" style="width: 30px">
                        {{ $item->id }}
                      </td>
                      <td class="align-top text-center">
                        @statusvar($item->current)
                      </td>
                      <td class="align-top text-center">
                        @statusvar($item->status)
                      </td>
                      <td class="align-top text-left">
                        {{ $item->period->start }} - {{ $item->period->finish }}
                      </td>
                      <td class="align-top text-left">
                        {{ $item->name }}
                      </td>
                      @component('layouts.partials.control',[
                          'id' => $item->id,
                          'url1' => $data["url1"],
                          'url2' => $data["url2"],
                          'show' => false,
                          'del' => false,
                          'add' => false,
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
              {!! $year->appends(['search' => Request::get('search')])->render() !!}
            </div>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
  </section>
@endsection
