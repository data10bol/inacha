@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>GESTIÓN: {{ $year->name }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $year->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => true,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $year->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => false
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
                    @statusvar($year->status)
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left" style="width: 100px">ACTUAL</th>
                  <td class="align-top text-justify">
                    @statusvar($year->current)
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">PERIODO</th>
                  <td class="align-top text-justify">
                    {{ $year->period->start }} - {{ $year->period->finish }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">GESTIÓN</th>
                  <td class="align-top text-justify">
                    {{ $year->name }}
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
