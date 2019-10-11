@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>ACCIÓN DE MEDIANO PLAZO: {{ $goal->doing->code }}.{{
                                                $goal->code }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $goal->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => true,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $goal->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => false,
                      'del' => true,
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
                  <th class="align-top text-left" style="width: 100px">PERIODO</th>
                  <td class="align-top text-justify">
                    {{ $goal->configuration->period->start }} - {{
                        $goal->configuration->period->finish }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">CÓDIGO</th>
                  <td class="align-top text-justify">
                    {{ $goal->doing->result->target->policy->code }}.{{
                        $goal->doing->result->target->code }}.{{
                        $goal->doing->result->id }}.{{
                        $goal->doing->code  }}.{{
                        $goal->code }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">PILAR</th>
                  <td class="align-top text-justify">
                    {!! $goal->doing->result->target->policy->descriptions->first()->description !!}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">META</th>
                  <td class="align-top text-justify">
                    {!! $goal->doing->result->target->descriptions->first()->description !!}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">RESULTADO</th>
                  <td class="align-top text-justify">
                    {!! $goal->doing->result->descriptions->first()->description !!}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">ACCIÓN</th>
                  <td class="align-top text-justify">
                    {!! $goal->doing->descriptions->first()->description !!}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">ACCIÓN DE MEDIANO PLAZO</th>
                  <td class="align-top text-justify">
                    {!! $goal->description !!}
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
