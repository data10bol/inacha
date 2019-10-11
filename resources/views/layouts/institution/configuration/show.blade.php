@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>DEFINICIÓN DE GESTIÓN:
                  {{ $configuration->period->start }} - {{
                    $configuration->period->finish }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $configuration->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => true,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $configuration->id,
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
                  <th class="align-top text-left" style="width: 100px">ESTADO</th>
                  <td class="align-top text-justify">
                    @statusvar($configuration->status)
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">EDITAR</th>
                  <td class="align-top text-justify">
                    @if($configuration->edit)
                      <span class="badge badge-success">SI</span>
                    @else
                      <span class="badge badge-danger">NO</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">REFORMULAR</th>
                  <td class="align-top text-justify">
                    @if($configuration->reconfigure)
                      <span class="badge badge-success">SI</span>
                      @php
                          $ref = App\Reformulation::where('month',activemonth())->where('year',activeyear())->first();
                      @endphp
                      
                        <span class="badge badge-warning">
                            {{$ref->reasons}}  
                        </span>
                      
                    @else
                      <span class="badge badge-danger">NO</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">PERÍODO</th>
                  <td class="align-top text-justify">
                    <strong>{{ $configuration->period->start }} - {{
                      $configuration->period->finish }}</strong>
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">MISIÓN</th>
                  <td class="align-top text-justify">
                    {!! $configuration->mission !!}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">VISIÓN</th>
                  <td class="align-top text-justify">
                    {!! $configuration->vision !!}
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
