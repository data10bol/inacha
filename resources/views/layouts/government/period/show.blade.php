@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>PERIODO: {{ $period->start }} - {{ $period->finish }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $period->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => true,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $period->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => false,
                      'del' => false
                  ])
                  @endcomponent
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-bordered table-condensed dataTable"
                     role="grid"
                     aria-describedby="main table">
                <tr>
                  <th class="align-top text-left" style="width: 100px">ESTADO</th>
                  <td class="align-top text-justify">
                    @statusvar($period->status)
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left" style="width: 100px">ACTUAL</th>
                  <td class="align-top text-justify">
                    @statusvar($period->current)
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">INICIO</th>
                  <td class="align-top text-justify">
                    {!! $period->start !!}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">FINAL</th>
                  <td class="align-top text-justify">
                    {!! $period->finish !!}
                  </td>
                </tr>
              </table>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
