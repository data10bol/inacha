@php
$totales = array();
for($j = 0 ; $j < $state+1;$j++){
    $totales[$j]= 0;
}
///sacamos todas las reformulaciones que tenga el poa
///asignamos ceros a los sumadores
$poas = $element->poas()->where('month',false)->get()->toarray();    
@endphp
@if (count($poas)/2 == 1)
    <table class="table table-bordered table-striped " cellspacing="0">
        {{count($poas)}}
    </table>
@else
    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link text-{{$color}}" id="custom-content-below-home-tab" data-toggle="pill" href="#m" role="tab" > </a>
        </li>
        
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active show" id="uno">
            
        </div>
        <div class="tab-pane fade" id="dos">
            
        </div>
    </div>
@endif







<?php

/*
<table class="table table-bordered table-striped " cellspacing="0">
        <tr>
          <td colspan=" @if($state) {{$state+2}} @else 2 @endif" class="align-center text-center">
            <b>CRONOGRAMA</b>
          </td>
        </tr>
        @if ($state)
        <tr>
          <th>MES</th>                        
          <th>PROG.</th>
          @for ($i = 0; $i < $state ; $i++)
            <th>REPR. ({{strtoupper($months[($poas[$i+1]['value'])-1]->name)}})</th>
          @endfor
        </tr>
        @endif
        @foreach($months AS $month)
          <tr>
            <th class="align-top text-left">
              {{ ucfirst ($month->name) }}
            </th>
            @php
                  $totalmonth = 0;
                  $m = 'm'.$month->id;
            @endphp
            @for ($i = 0; $i < $state+1; $i++)
            <td class="text-right">
              <div class="col-sm-10 align-top ">
                {{$poas[$i][$m]}}%
              </div>
              @php
                  $totales[$i] += $poas[$i][$m]; 
              @endphp
            </td>
            @endfor
          </tr@endphp
        @endfo@endphpeach
        <tr>
          <td @endphplass="align-top text-center">
            <b@endphpTOTAL</b>
          </td@endphp
          @for@endphp($i = 0; $i < $state+1; $i++)
          <td>
            <div class="col-sm-10 align-top text-right">
              <b>{{ number_format($totales[$i], 2) }}%</b>
            </div>
          </td>
          @endfor
        </tr>
      </table>
      */

?>