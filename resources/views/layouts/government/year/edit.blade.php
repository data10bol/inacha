@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">
                <b>EDITAR GESTIÓN: {{ $year->name }}</b>
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
                      'pdf' => false,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                @component('layouts.partials.hcontrol',[
                    'id' => $year->id,
                    'url1' => $data["url1"],
                    'url2' => $data["url2"],
                    'type' => 'edit',
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
              <!-- /.card-header -->
              <!-- form start -->
              {!! Form::model($year,
                  [ 'method' => 'PATCH',
                    'url' => [$data["url1"], Hashids::encode($year->id)],
                    'class' => 'form-horizontal',
                    'files' => true ])
              !!}
              {{ Form::hidden('id', Hashids::encode($year->id)) }}
              {{ Form::hidden('period_id', $year->period_id) }}
              {{ Form::hidden('name', $year->name) }}
              @include ('layouts.government.year.form', ['submitButtonText' => 'ACTUALIZAR'])
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection

