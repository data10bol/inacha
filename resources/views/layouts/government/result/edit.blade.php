@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">
                <b>RESULTADO: {{ $result->target->policy->code }}.{{
                                   $result->target->code }}.{{
                                   $result->id }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $result->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => false,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent

                  @component('layouts.partials.hcontrol',[
                      'id' => $result->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'edit',
                      'add' => true
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
              <!-- /.card-header -->
              <!-- form start -->
              {!! Form::model($result,
                  [ 'method' => 'PATCH',
                  'url' => [$data["url1"], Hashids::encode($result->id)],
                  'class' => 'form-horizontal',
                  'files' => true ])
              !!}
              {{ Form::hidden('id', Hashids::encode($result->id)) }}
              @include ('layouts.government.result.form', [
                      'submitButtonText' => 'ACTUALIZAR'])
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
