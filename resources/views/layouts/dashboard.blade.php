@extends('layouts.master')
@section('title', 'Principal')
@section('content')
  <section>
    <div class="content">
      <div class="container-fluid">
        @include ('layouts.partials.resumen')
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-thumb-tack"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">ACCIONES</span>
                <span class="info-box-number">
                  {{ isset($actions)?$actions->count():'0' }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-dark elevation-1"><i class="fa fa-tags"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">OPERACIONES</span>
                <span class="info-box-number">
                  {{ isset($operations)?$operations->count():'0' }}
                </span>
              </div>

              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-secondary elevation-1"><i class="fa fa-tasks"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TAREAS</span>
                <span class="info-box-number">
                  {{ isset($tasks)?$tasks->count():'0' }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-light elevation-1">
                <i class="fa fa-clock-o"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">CIERRE</span>
                <span class="info-box-number">{!! daystolimit() !!}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        @role('Usuario')
        @include ('layouts.partials.cards', [
          'month' => activemonth(),
          'items' => $tasks,
          'type' => 'Task',
          ])
        @endrole
        @role('Responsable')
          @include ('layouts.partials.cards', [
          'month' => activemonth(),
          'items' => $operations,
          'type' => 'Operation',
          ])
        @endrole
        @role('Administrador|Supervisor')
          @include ('layouts.partials.brief')
        @endrole
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
  </section>
@endsection

<!-- OPTIONAL SCRIPTS -->
<!-- <script src="plugins/chart.js/Chart.min.js"></script> -->
<!-- <script src="dist/js/demo.js"></script> -->
<!-- <script src="dist/js/pages/dashboard3.js"></script> -->
