@extends('layouts.master')
@section('title', 'Item')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>DEPARTAMENTO: {{ strtoupper($department->name) }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $department->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => false,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $department->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => false,
                      'del' => true,
                      'editenable' => true
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
                    {{ $department->id }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">NOMBRE</th>
                  <td class="align-top text-justify">
                    {{ $department->name }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">DEPENDENCIA</th>
                  <td class="align-top text-justify">
                    {{ App\Department::Select('name')
                                        ->Where('id',$department->dependency)
                                        ->pluck('name')
                                        ->first()
                    }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">RESPONSABLE</th>
                  <td class="align-top text-justify">
                    {{ $department->responsable->name }}
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
