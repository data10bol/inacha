@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        @component('layouts.partials.icontrol',[
            'title' => 'REPORTE MENSUAL',
            'search' => false,
            'url1' => $data["url1"],
            'addtop' => false,
            'word' => true,
            'urlword' => ''.Request::url().'?month='.Request::get('month').'&type=word',
            'pdf' => true,
            'urlpdf' => ''.Request::url().'?month='.Request::get('month').'&type=pdf',
        ])
        @endcomponent
      </div>
      {{--Cabezera de meses para reportes--}}
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-title align-top text-center">
              @for ($i=1;$i<=sizeof($months);$i++)
                {!! Form::button(ucfirst($months[$i]),
                            array('title' => ucfirst($months[$i]),
                                    'type' => 'button',
                                    'class' => ($currentmonth==$i)?'btn btn-danger btn-sm':'btn btn-default btn-sm',
                                    'onclick'=>'window.location.href="reportpoa?month='.$i.'"'))
                !!}
              @endfor
            </div>
          </div>
        </div>
      </div>
      
      @include ('layouts.partials.brief')
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              {{--Tabla principal de reporte--}}
              <table width="100%" class="table table-hover dataTable"
                      role="grid"
                      aria-describedby="main table">
                <thead bgcolor="#dc3545">
                @foreach($header as $item)
                  <th class="text-{{ strtolower($item['align']) }}">
                    <span style="color:whitesmoke; font-weight: bold">
                    {{ strtoupper($item['text']) }}
                    </span>
                  </th>
                @endforeach
                </thead>
                <tbody>
                @forelse($actions as $item)
                  @php
                    $month = 'm'.$currentmonth;
                    ///$item->poas()->Where('state',false)->Where('month',false)->where('in','<=',$currentmonth)->where('out','>=',$currentmonth)->first()->$month;
                    ///$item->poas()->Where('state',true)->Where('month',false)->where('in','<=',$currentmonth)->where('out','>=',$currentmonth)->first()->$month;
                    $prog= $item->poas()->
                                  Where('state',false)->
                                  Where('month',false)->
                                  Where('in','<=',$currentmonth)->
                                  Where('out','>=',$currentmonth)->
                                  First()->$month;
                    $exec= $item->poas()->
                                  Where('state',true)->
                                  Where('month',false)->
                                  Where('in','<=',$currentmonth)->
                                  Where('out','>=',$currentmonth)-> 
                                  First()->$month;

                  $accump = accum($item->id,'Action',false,$currentmonth);
                  $accume = accum($item->id,'Action',true,$currentmonth);
                  @endphp
                  <tr>
                    <td class="align-top text-center" width="100">
                      {{ $item->goal->code }}.{{
                          $item->code }}<br>
                      @if(!$item->status)
                        <span class="badge badge-danger">REPROG</span>
                      @endif
                    </td>
                    <td class="align-top text-justify">
                      {!! substr($item->definitions->first()->description, 0, 200) !!}
                      {!! strlen($item->definitions->first()->description) > 200 ? '...' : '' !!}
                    </td>
                    <td class="align-top text-center">
                      {{ $item->definitions->first()->ponderation }}
                    </td>
                    <td class="align-top text-center">
                      {{ $item->definitions->first()->aim }}
                    </td>
                    <td class="align-top text-center">
                      {{ $item->definitions->first()->pointer }}
                    </td>
                    <td class="align-top text-center">
                      {{ number_format((float)$accump, 2, '.', '') }}
                    </td>
                    <td class="align-top text-center">
                      {{ number_format((float)$accume, 2, '.', '') }}
                    </td>
                    <td class="align-top text-center">
                      {!! arrows(number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '')) !!}
                    </td>
                    <td class="align-top text-center">
                      {{ number_format((float)($accump>0)?$accume*100/$accump:0, 2, '.', '') }}%
                    </td>
                    <td class="align-top text-center">
                      {{ $prog }}
                    </td>
                    <td class="align-top text-center">
                      {{ $exec  }}
                    </td>
                    <td class="align-top text-center">
                      {!! arrows(number_format((float)($exec>$prog)?100:(
                      ($prog>0)?$exec*100/$prog:0), 2, '.', '')) !!}
                    </td>
                    <td class="align-top text-center">
                      {{ number_format((float)($exec>$prog)?100:(
                      ($prog>0)?$exec*100/$prog:0), 2, '.', '') }}%
                    </td>
                    <td class="align-top text-center">
                      {{ $item->department->name }}
                    </td>
                  </tr>
                @empty
                  {!! emptyrecord(count($header)) !!}
                @endforelse
                </tbody>
                <tfoot bgcolor="#ebecec">
                @foreach($header as $item)
                  <th class="text-{{ strtolower($item['align']) }}">
                    <span style="color:black; font-weight: bold">
                    {{ strtoupper($item['text']) }}
                    </span>
                  </th>
                @endforeach
              </table>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
    </div>
  </section>
@endsection
