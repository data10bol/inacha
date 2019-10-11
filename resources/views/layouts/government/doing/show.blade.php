@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>ACCIÓN: {{ $doing->result->target->policy->code }}.{{
                               $doing->result->target->code }}.{{
                               $doing->result->id }}.{{
                               $doing->code }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                @component('layouts.partials.exportcontrol',[
                    'id' => $doing->id,
                    'excel' => false,
                    'urlexcel' => "#",
                    'word' => false,
                    'urlword' => "#",
                    'pdf' => true,
                    'urlpdf' => ''.Request::url().'-pdf',
                    ])
                @endcomponent
                @component('layouts.partials.hcontrol',[
                    'id' => $doing->id,
                    'url1' => $data["url1"],
                    'url2' => $data["url2"],
                    'type' => 'show',
                    'add' => true,
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
                      <h6>{{ $doing->result->target->policy->period->start }}-{{
                             $doing->result->target->policy->period->finish }}</h6>
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">CÓDIGO</th>
                    <td class="align-top text-justify">
                      {{ $doing->result->target->policy->code }}.{{
                          $doing->result->target->code }}.{{
                          $doing->result->id }}.{{
                          $doing->code }}
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">PILAR</th>
                    <td class="align-top text-justify">
                      {!! $doing->result->target->policy->descriptions->first()->description !!}
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">META</th>
                    <td class="align-top text-justify">
                      {!! $doing->result->target->descriptions->first()->description !!}
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">RESULTADO</th>
                    <td class="align-top text-justify">
                      {!! $doing->result->descriptions->first()->description !!}
                    </td>
                  </tr>
                  <tr>
                    <th class="align-top text-left">ACCIÓN</th>
                    <td class="align-top text-justify">
                      {!! $doing->descriptions->first()->description !!}
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

