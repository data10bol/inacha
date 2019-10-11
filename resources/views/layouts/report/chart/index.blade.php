@php $month = ucfirst(\App\Month::Where('id', $currentmonth)->first()->name) @endphp
@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        @component('layouts.partials.icontrol',[
            'title' => 'REPORTE GRÁFICO',
            'search' => false,
            'url1' => $data["url1"],
            'add' => false,
            'addtop' => false,
            'pdf' => false,
            'urlpdf' => ''.Request::url().'?month='.Request::get('month').'&type=pdf',
        ])
        @endcomponent
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-title align-top text-center">
              @for ($i=1;$i<=sizeof($months);$i++)
                {!! Form::button(ucfirst($months[$i]),
                            array('title' => ucfirst($months[$i]),
                                    'type' => 'button',
                                    'class' => ($currentmonth==$i)?'btn btn-danger btn-sm':'btn btn-default btn-sm',
                                    'onclick'=>'window.location.href="chartpoa?month='.$i.'"'))
                !!}
              @endfor
            </div>
          </div>
        </div>
      </div>
      @include ('layouts.partials.resumen',[
              'name' => $name_ofep,
              'chartaccuma' => $chartaccuma_ofep,
              'chartaccumm' => $chartaccumm_ofep,
              'chartaccumma' => $chartaccumma_ofep,
              'month' => $month,
              ])
      @forelse ($departments as $item)
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-title align-top text-center">
                <h3 class="card-title">
                  <strong>
                    {{ \App\Department::Where('id',$item)->
                                       first()->
                                       name }}
                  </strong>
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="card">
                      <div class="card-header no-border">
                        <div>
                          <h3 class="card-title">
                            <strong>
                              <span>Avance anual</span>
                            </strong>
                          </h3>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="d-flex">
                          <div id="charta{!! $loop->iteration !!}"
                               style="min-width: 450px; height: 350px; margin: 0 auto"></div>
                          {!! $chart_accum[($loop->iteration)] !!}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card">
                      <div class="card-header no-border">
                        <div>
                          <h3 class="card-title">
                            <strong>
                              <strong>
                                <span>Avance de {{ $month }}</span>
                              </strong>
                            </strong>
                          </h3>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="d-flex">
                          <div id="chartm{!! $loop->iteration !!}"
                               style="min-width: 450px; height: 350px; margin: 0 auto"></div>
                          {!! $chart_month[($loop->iteration)] !!}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card">
                      <div class="card-header no-border">
                        <div>
                          <h3 class="card-title">
                            <strong>
                              <strong>
                                <span>Avance acumulado a {{ $month }}</span>
                              </strong>
                            </strong>
                          </h3>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="d-flex">
                          <div id="chartam{!! $loop->iteration !!}"
                               style="min-width: 450px; height: 350px; margin: 0 auto"></div>
                          {!! $chart_accum_month[($loop->iteration)] !!}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="row">
          <div class="col-12">
            SIN REGISTROS
          </div>
        </div>
      @endforelse
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection

