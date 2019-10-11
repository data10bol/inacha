@php

  if(!isset($currentmonth))
    $currentmonth = activemonth();

  $action = new \App\Action;

  $departments = $action->Select('department_id')->
  GroupBy('department_id')->
  pluck('department_id')->
  toArray();

@endphp
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-title align-top text-center">
        <h3 class="card-title">Acumulado a
          {{ ucfirst(\App\Month::Where('id', activemonth())->first()->name) }} / {{ activeyear() }}</h3>
      </div>
      <div class="card-body">
        <div class="row align-content-lg-center">
          <div class="col-4">
            <div class="row align-content-lg-center">
              <div class="col-12">
                <div class="small-box bg-gray">
                  <div class="inner">
                    <h3>{{ accum(0, 'Total', true, $currentmonth) }}%</h3>
                    <p><strong><h5>OFEP</h5></strong></p>
                  </div>
                  <div class="d-flex">
                    <div id="brief"
                         style="min-width: 450px; height: 135px; margin: 0 auto"></div>
                    {!! graphadvance(
                    'brief',
                    accum(0, 'Total', false, $currentmonth),
                    accum(0, 'Total', true, $currentmonth),
                    450,
                    135
                    ) !!}
                  </div>
                  <div class="icon">
                    <i class="ion ion-home"></i>
                  </div>
                  <div>
                    <p></p>
                  </div>
                  <a class="small-box-footer" href="{{ route('showreportpoa') }}">
                    Más información
                  </a>
                </div>
              </div>
            </div>
            <div class="row align-content-lg-center">
              <div class="card-body">
              </div>
            </div>
          </div>
          <div class="col-8">
            <div class="row align-content-lg-center">
              @forelse($departments as $item)
                @php
                  $department = \App\Department::FindorFail($item);
                @endphp
                <div class="col-6">
                  <div class="small-box bg-{{ $department->color }}">
                    <div class="inner">
                      <h3>{{ accum($department->id, 'Department', true, $currentmonth) }}
                       % / {{ accum($department->id, 'Department', false, $currentmonth) }} %
                      </h3>
                      <p>{{ $department->name }}</p>
                    </div>
                    <div class="icon">
                      <i class="ion {{ $department->icon }}"></i>
                    </div>
                    <a class="small-box-footer" href="{{ route('showreportpoa') }}?month={{
                        $currentmonth }}&id={{
                        Hashids::encode( $department->id )}}">
                      Más información
                    </a>
                  </div>
                </div>
                @if(!($loop->iteration % 2))
            </div>
            <div class="row align-content-lg-center">
              @endif
              @if($loop->last)
            </div>
            @endif
            @empty
              <div>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
