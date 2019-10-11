@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">
                <b>EDITAR ROL: {{ strtoupper($role->name) }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $role->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => false,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent

                  @component('layouts.partials.hcontrol',[
                      'id' => $role->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'edit',
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
              <!-- /.card-header -->
              <!-- form start -->
              {!! Form::model($role,
                      [ 'method' => 'PATCH',
                      'url' => [$data["url1"], Hashids::encode($role->id)],
                      'class' => 'form-horizontal',
                      'files' => true ])
              !!}
              {{ Form::hidden('id', Hashids::encode($role->id)) }}
              @include ('layouts.administrator.role.form', ['submitButtonText' => 'ACTUALIZAR'])
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
