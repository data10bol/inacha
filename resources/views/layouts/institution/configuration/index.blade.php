@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'DEFINICIONES',
          'search' => false,
          'url1' => $data["url1"],
          'editenable' => true,
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
                @forelse($configuration as $item)
                  <tr>
                    <td class="align-top text-center" style="width: 100px">
                      {{ $item->period->start }} - {{ $item->period->finish }}
                    </td>
                    <td class="align-top text-center" style="width: 100px">
                      @statusvar($item->status)
                      <br>Editar:
                      @if($item->edit)
                        <span class="badge badge-success">SI</span>
                      @else
                        <span class="badge badge-danger">NO</span>
                      @endif
                      <br>Refor:
                      @if($item->reconfigure)
                        <span class="badge badge-success">SI</span>
                      @else
                        <span class="badge badge-danger">NO</span>
                      @endif
                    </td>
                    <td class="align-top text-justify">
                      {!! substr($item->mission, 0, 1024) !!}
                      {!! strlen($item->mission)>1024?'...':''!!}
                    </td>
                    <td class="align-top text-justify">
                      {!! substr($item->vision, 0, 1024) !!}
                      {!! strlen($item->vision)>1024?'...':''!!}
                    </td>
                    @component('layouts.partials.control',[
                        'id' => $item->id,
                        'url1' => $data["url1"],
                        'url2' => $data["url2"],
                        'add' => false,
                        'del' => true,
                        'editenable' => true
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
            {!! $configuration->appends(['search' => Request::get('search')])->render() !!}
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
