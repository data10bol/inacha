@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>TAREA:
                  {{   $task->operation->action->goal->code }}.{{
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
                      'pdf' => true,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $task->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => false,
                      'modal' => true,
                      'status' => $task->status,
                      'editenable' => ($task->status)?true:false
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
              <table  class="table table-bordered table-condensed dataTable"
                      role="grid"
                      aria-describedby="main table">
                <tr>
                  <td class="align-top text-justify">
                    <table class="table dataTable" width="100%">
                      <tr>
                        <th class="align-top text-left" style="width: 120px">
                          CÓDIGO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-3">
                            {{  $task->operation->action->goal->code }}.{{
                                  $task->operation->action->code }}.{{
                                  $task->operation->code }}.{{
                                  $task->code }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          GESTIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {{ $task->operation->action->year }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left" style="width: 100px">
                          DEPARTAMENTO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {{ $task->operation->action->department->name }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          RESPONSABLE(S)
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            <ul>
                              @forelse ($task->users as $taskuser)
                                <li>{{ $taskuser->name }}</li>
                              @empty
                                <li>SIN REGISTROS</li>
                              @endforelse
                            </ul>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          ESTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-3">
                            @if($task->status)
                              <span class="badge badge-success">VIGENTE</span> @else
                              <span class="badge badge-danger">ELIMINADA</span> @endif
                          </div>
                        </td>
                      </tr>
                      @if(!$task->status)
                        <tr>
                          <th class="align-top text-left">
                            RAZÓN
                          </th>
                          <td class="align-top text-justify">
                            <div class="col-sm-12">
                              {{ $task->reason }}
                            </div>
                          </td>
                        </tr>
                      @endif
                      <tr>
                        <th class="align-top text-left">
                          PILAR
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->doing->result->target->policy->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          META
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->doing->result->target->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          RESULTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->doing->result->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          ACCIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->doing->descriptions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          A. MEDIANO PLAZO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->goal->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          A. CORTO PLAZO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->action->definitions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          OPERACIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->operation->definitions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          DESCRIPCIÓN
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->definitions->first()->description !!}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="align-top text-left">
                          RESULTADO
                        </th>
                        <td class="align-top text-justify">
                          <div class="col-sm-12">
                            {!! $task->definitions->first()->validation !!}
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                  @php
                    $total = 0;
                  @endphp
                  <td class="align-top text-justify" style="width: 320px" bgcolor="#dc3545">
                    <table class="table table-bordered table-striped " cellspacing="0" width="100%">
                      <tr>
                        <th colspan="2" class="align-center text-center">
                          CRONOGRAMA
                        </th>
                      </tr>
                      @foreach($months AS $month)
                        <tr>
                          <th class="align-top text-left">
                            {{ ucfirst ($month->name) }}
                          </th>
                          <td class="text-right">
                            <div class="col-sm-10 align-top ">
                              {{ $task->poas->pluck('m'.$month->id)->first() }}
                              @php $total += $task->poas->pluck('m'.$month->id)->first() @endphp
                            </div>
                          </td>
                        </tr>
                      @endforeach
                      <tr>
                        <td class="align-top text-center">
                          <b>TOTAL</b>
                        </td>
                        <td>
                          <div class="col-sm-10 align-top text-right">
                            <b>{{ $total }}</b>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <table class="table table-sm table-bordered table-striped">
                      <tr>
                        <th>
                          #
                        </th>
                        <th width="50%">
                          ACTUALIZACIÓN
                        </th>
                        <th>
                          RESPONSABLE
                        </th>
                        <th>
                          FECHA
                        </th>
                      </tr>
                      @forelse($reasons AS $reason)
                        <tr>
                          <td>
                            {{ $loop->iteration }}
                          </td>
                          <td>
                            {!! $reason->description!!}
                          </td>
                          <td>
                            {!! App\User::Select('name')
                            ->Where('id',$reason->user_id)
                            ->pluck('name')
                            ->first() !!}
                          </td>
                          <td>
                            {!! $reason->created_at!!}
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="4" align="center">
                            <span class="text-danger">SIN ACTUALIZACIONES</span>
                          </td>
                        </tr>
                      @endforelse
                    </table>
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
