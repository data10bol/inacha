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
                <b>EDITAR TAREA: {{   $task->operation->action->code }}.{{
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
                      'modal' => true,
                      'status' => $task->status,
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
              <!-- /.card-header -->
              <!-- form start -->
              {!! Form::model($task, [
                  'method' => 'PATCH',
                  'url' => [$data["url1"], Hashids::encode($task->id)],
                  'class' => 'form-horizontal', 'files' => true ])
              !!}
              {{ Form::hidden('id', Hashids::encode($task->id)) }}
              {{ Form::hidden('year', activeyear()) }}
              @include ('layouts.institution.task.form', ['submitButtonText' => 'ACTUALIZAR'])
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modal">
        <div class="modal-dialog">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">ELIMINAR TAREA</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
