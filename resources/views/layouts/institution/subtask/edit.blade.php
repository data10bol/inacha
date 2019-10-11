@extends('layouts.master')
@section('content')
<section>
    <!-- ckeditor -->
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">EDITAR SUBTAREA:
                                <b>{{   $subtask->task->operation->action->code }}.{{
                                        $subtask->task->operation->code }}.{{
                                        $subtask->task->code }}.{{
                                        $subtask->code }}</b></h3>

                                @component('layouts.partials.hcontrol',[
                                    'id' => $subtask->id,
                                    'url1' => $data["url1"],
                                    'url2' => $data["url2"],
                                    'type' => 'edit',
                                    'add' => false,
                                    'modal' => true,
                                    'status' => $subtask->status
                                ])
                                @endcomponent
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        {!! Form::model($subtask, [
                            'method' => 'PATCH',
                            'url' => [$data["url1"], Hashids::encode($subtask->id)],
                            'class' => 'form-horizontal', 'files' => true ])
                        !!}
                        {{ Form::hidden('id', Hashids::encode($subtask->id)) }}

                        {{ Form::hidden('year', activeyear()) }}
                            @include ('layouts.institution.subtask.form', ['submitButtonText' => 'ACTUALIZAR'])
                        {!! Form::close() !!}
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">ELIMINAR SUBTAREA</h4>
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
</section>
@endsection
