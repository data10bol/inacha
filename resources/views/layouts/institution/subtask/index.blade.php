@extends('layouts.master')
@section('content')
<section>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ADMINISTRADOR DE SUBTAREAS</h3>
                            @component('layouts.partials.icontrol',[
                                'url1' => $data["url1"],
                                'addtop' => false,
                            ])
                            @endcomponent
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead class=" text-primary">
                                    <th class="text-center"><strong>CÓDIGO</strong></th>
                                    <th class="text-center"><strong>ESTADO</strong></th>
                                    <th class="text-center"><strong>DEPARTAMENTO</strong></th>
                                    <th class="text-left"><strong>RESPONSABLE</strong></th>
                                    <th class="text-left"><strong>TAREA</strong></th>
                                    <th class="text-left"><strong>SUBTAREA</strong></th>
                                    <th class="text-center"><strong>INICIO</strong></th>
                                    <th class="text-center"><strong>FIN</strong></th>
                                    <th class="text-center"><strong>CONTROL</strong></th>
                                </thead>
                                <tbody>
                                    @forelse($subtask as $item)
                                    <tr>
                                        <td class="align-top text-center" style="width: 100px">
                                                {{  $item->task->operation->action->goal->code }}.{{
                                                    $item->task->operation->action->code }}.{{
                                                    $item->task->operation->code }}.{{
                                                    $item->task->code }}.{{
                                                    $item->code }}<br>
                                            </td>
                                            <td class="align-top text-center">
                                                @if(!$item->status)
                                                    <span class="badge badge-primary">VIGENTE</span>
                                                @else
                                                    <span class="badge badge-success">FINALIZADA</span>
                                                @endif
                                            </td>
                                            <td class="align-top text-justify">
                                                {{ $item->task->operation->action->department->name }}
                                            </td>
                                            <td class="align-top text-center">
                                                {{ $item->task->user->name }}
                                            </td>
                                            <td class="align-top text-justify">
                                                {!! substr($item->task->definitions->first()->description, 0, 200) !!}
                                                {!! strlen($item->task->definitions->first()->description) > 200 ? '...' : '' !!}
                                            </td>
                                            <td class="align-top text-justify">
                                                {!! substr($item->description, 0, 200) !!}
                                                {!! strlen($item->description) > 200 ? '...' : '' !!}
                                            </td>
                                            <td class="align-top text-center">
                                                {{ $item->start }}
                                            </td>
                                            <td class="align-top text-center">
                                                {{ $item->finish }}
                                            </td>
                                        @component('layouts.partials.control',[
                                            'id' => $item->id,
                                            'url1' => $data["url1"],
                                            'url2' => $data["url2"],
                                            'add' => false,
                                            'modal' => false,
                                            'status' => $item->status
                                        ])
                                        @endcomponent
                                    </tr>
                                    @empty
                                        {!! emptyrecord("10") !!}
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    @unlessrole('Responsable|Usuario')
                        <div class="pagination-wrapper"> {!! $subtask->appends(['search' => Request::get('search')])->render() !!} </div>
                    @endunlessrole
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
</section>
@endsection
