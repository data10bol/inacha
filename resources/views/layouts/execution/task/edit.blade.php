@extends('layouts.master')
@section('content')
  <section class="content">
    <!-- ckeditor -->
   <!-- <script src="{{asset('ckeditor/ckeditor.js')}}"></script> -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">
                <b>EJECUTAR TAREA:{{   $task->operation->action->goal->code }}.{{
                                        $task->operation->action->code }}.{{
                                        $task->operation->code }}.{{
                                        $task->code }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $task->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => false,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $task->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'edit',
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
              <!-- /.card-header -->
              <!-- form start -->
              {!! Form::model($task, [
                  'method' => 'PATCH',
                  'url' => [$data["url1"], Hashids::encode($task->id)],
                  'class' => 'form-horizontal', 'files' => true,
                   'onsubmit' => 'return zero()'])
              !!}
              {{ Form::hidden('id', Hashids::encode($task->id)) }}

              {{ Form::hidden('year', activeyear()) }}
              @include ('layouts.execution.task.form', ['submitButtonText' => 'ACTUALIZAR'])
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
