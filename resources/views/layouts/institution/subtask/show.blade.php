@extends('layouts.master')
@section('content')
<section>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">SUBTAREA:
                                <b>{{   $subtask->task->operation->action->goal->code }}.{{
                                        $subtask->task->operation->action->code }}.{{
                                        $subtask->task->operation->code }}.{{
                                        $subtask->task->code }}.{{
                                        $subtask->code }}</b></h3>

                                @component('layouts.partials.hcontrol',[
                                    'id' => $subtask->id,
                                    'url1' => $data["url1"],
                                    'url2' => $data["url2"],
                                    'type' => 'show',
                                    'add' => false,
                                    'modal' => false,
                                    'status' => $subtask->status
                                ])

                                @endcomponent
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-condensed">
                                    <tr>
                                                  <th class="align-top text-left" style="width: 100px">
                                                    DEPARTAMENTO
                                                  </th>
                                                  <td class="align-top text-justify">
                                                    <div class="col-sm-12">
                                                        {{ $subtask->task->operation->action->department->name }}
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                    <th class="align-top text-left">
                                                      RESPONSABLE
                                                    </th>
                                                    <td class="align-top text-justify">
                                                      <div class="col-sm-12">
                                                          {{ $subtask->task->user->name }}
                                                      </div>
                                                    </td>
                                                  </tr>
                                                <tr>
                                                  <th class="align-top text-left">
                                                    CÓDIGO
                                                  </th>
                                                  <td class="align-top text-justify">
                                                    <div class="col-sm-3">{{ $subtask->task->operation->action->goal->code }}.{{
                                                            $subtask->task->operation->action->code }}.{{
                                                            $subtask->task->operation->code }}.{{
                                                            $subtask->task->code }}.{{
                                                            $subtask->code }}
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <th class="align-top text-left">
                                                    ESTADO
                                                  </th>
                                                  <td class="align-top text-justify">
                                                    <div class="col-sm-3">
                                                            @if(!$subtask->status)
                                                                <span class="badge badge-primary">VIGENTE</span>
                                                            @else
                                                                <span class="badge badge-success">FINALIZADA</span>
                                                            @endif
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                    <th class="align-top text-left">
                                                      OPERACIÓN
                                                    </th>
                                                    <td class="align-top text-justify">
                                                      <div class="col-sm-12">
                                                        {!! $subtask->task->operation->definitions->first()->description !!}
                                                      </div>
                                                    </td>
                                                  </tr>
                                                <tr>
                                                  <th class="align-top text-left">
                                                    TAREA
                                                  </th>
                                                  <td class="align-top text-justify">
                                                    <div class="col-sm-12">
                                                      {!! $subtask->task->definitions->first()->description !!}
                                                    </div>
                                                  </td>
                                                </tr>
<!--                                                <tr>
                                                  <th class="align-top text-left">
                                                    U. MEDIDA
                                                  </th>
                                                  <td class="align-top text-justify">
                                                    <div class="col-sm-4">
                                                      {{ $subtask->task->definitions->first()->measure }}
                                                    </div>
                                                  </td>
                                                </tr> -->
                                                <tr>
                                                  <th class="align-top text-left">
                                                    DESCRIPCIÓN
                                                  </th>
                                                  <td class="align-top text-justify">
                                                    <div class="col-sm-12">
                                                      {{ $subtask->description }}
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <th class="align-top text-left">
                                                    RESULTADO
                                                  </th>
                                                  <td class="align-top text-justify">
                                                    <div class="col-sm-4">
                                                      {{ $subtask->validation }}
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <th class="align-top text-left">
                                                    INICIO
                                                  </th>
                                                  <td class="align-top text-justify">
                                                    <div class="col-sm-4">
                                                      {{ $subtask->start }}
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <th class="align-top text-left">
                                                    FINALIZACIÓN
                                                  </th>
                                                  <td class="align-top text-justify">
                                                    <div class="col-sm-12">
                                                      {{ $subtask->finish }}
                                                    </div>
                                                  </td>
                                                </tr>

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
</section>
@endsection
