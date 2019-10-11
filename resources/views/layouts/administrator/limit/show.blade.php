@extends('layouts.master')
@section('title', $title)
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>FECHA LÍMITE: {{ $limit->date }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $limit->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => false,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $limit->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => false,
                      'del' => true,
                      'editenable' => true,
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
                  <th class="align-top text-left" style="width: 120px">ID</th>
                  <td class="align-top text-justify">
                    {{ $limit->id }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">ESTADO</th>
                  <td class="align-top text-justify">
                    @statusvar($limit->status)
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">GESTIÓN</th>
                  <td class="align-top text-justify">
                    {{ $limit->year->name }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">MES</th>
                  <td class="align-top text-justify">
                    {{ strtoupper(App\Month::Select('name')
                                        ->Where('id',$limit->month)
                                        ->pluck('name')
                                        ->first())
                    }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">FECHA LÍMITE</th>
                  <td class="align-top text-justify">
                    {{ date('D, d F Y', strtotime($limit->date)) }}
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
