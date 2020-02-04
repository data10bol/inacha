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
                  @if(isset($actions))
                  {{ $actions->count()}}
                  @role('Administrador|Supervisor')
                    @php
                      $apl = 0;
                      $m = 'm'.activemonth();
                      $aej = 0;
                      foreach ($actions as $item) {
                        if ((float)$item->poas->where('state',false)->pluck($m)[0]>0){
                          $apl++;
                          $ar = quantity_exe($item->id,'action',activemonth());
                          if ($ar[0]==$ar[1]){
                            $aej++;
                          }
                        }
                      }
                    @endphp
                    ({{substr(strtoupper(App\Month::Where('id',activemonth())->first()->name),0,3)}}: Plan. {{$apl}}/ Ejec.{{$aej}})
                  @endrole
                  @else
                  0
                  @endif
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
                  @if (isset($operations))
                  {{ $operations->count()}}
                  @role('Administrador|Supervisor')
                    @php
                      $opl = 0;
                      $m = 'm'.activemonth();
                      $oej = 0;
                      foreach ($operations as $item){
                        if ((float)$item->poas->where('state',false)->pluck($m)[0]>0){
                          $opl++;
                          $ar = quantity_exe($item->id,'operation',activemonth());
                          if ($ar[0]==$ar[1]){
                            $month = 'm' . activemonth();
                            if(!empty($item->poas()->where('month', activemonth())->first()->$month)){
                              $oej++;
                            }
                          }
                        }
                      }
                    @endphp
                    ({{substr(strtoupper(App\Month::Where('id',activemonth())->first()->name),0,3)}}: Plan. {{$opl}}/ Ejec.{{$oej}})
                  @endrole
                  @else
                    0
                  @endif
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
                  @if (isset($tasks))
                    {{ $tasks->count()}}
                    @role('Administrador|Supervisor')
                    @php
                      $tpl = 0;
                      $m = 'm'.activemonth();
                      $tej = 0;
                      foreach ($tasks as $item){
                        if ((float)$item->poas->where('state',false)->pluck($m)[0]>0){
                          $tpl++;
                          $month = 'm' . activemonth();
                          if(!empty($item->poas()->where('month', activemonth())->first()->$month)){
                            $tej++;
                          }
                        }
                      }
                    @endphp
                    ({{substr(strtoupper(App\Month::Where('id',activemonth())->first()->name),0,3)}}: Plan. {{$tpl}}/ Ejec.{{$tej}})
                  @endrole
                  @else
                    0
                  @endif
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
