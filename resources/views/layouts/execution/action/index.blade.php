@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'EJECUCIÓN DE ACCIONES',
          'url1' => $data["url1"],
          'add' => false,
          'addtop' => false,
          'pdf' => true,
          'urlpdf' => ''.Request::url().'?search='.Request::get('search').'&type=pdf',
      ])
      @endcomponent
      <!-- /.card-header -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-hover dataTable"
                    role="grid"
                    aria-describedby="main table">
                @component('layouts.partials.theader',[
                    'arg'=>$header
                    ])
                @endcomponent
                <tbody>
                @php
                  $tmp = 0;
                  $tmp2 = 0;
                  $m = 'm'.activemonth();
                @endphp
                @forelse($action as $item)
                @if ((float)$item->poas->where('state',false)->pluck($m)[0]>0)
                  <tr>
                    <td class="align-top text-center">
                      {{ $item->goal->code }}.{{
                                                $item->code }} <br>
                      @if(!$item->status)
                        <span class="badge badge-danger">ELIMINADA</span>
                      @endif
                    </td>
                    <td class="align-top text-center">
                      {{ $item->year }}
                    </td>
                    <td class="align-top text-left">
                      @if($item->department->id != $tmp)
                        {{ $item->department->name }}
                      @endif
                    </td>
                    <td class="align-top text-justify">
                      @if($item->goal->id != $tmp2)
                        {!! substr($item->goal->description, 0, 200) !!}
                        {!! strlen($item->goal->description) > 200 ? '...' : '' !!}
                      @endif
                    </td>
                    <td class="align-top text-justify">
                      {!! substr($item->definitions->first()->description, 0, 200) !!}
                      {!! strlen($item->definitions->first()->description) > 200 ? '...' : '' !!}
                    </td>
                    <td class="align-top text-center">
                      {{ $item->poas()->
                      Where('state',false)->
                      Where('month',false)->
                      orderby('id','desc')->
                      first()->$m }}
                    </td>
                    <td class="align-top text-center">
                      {{ $item->poas()->
                      Where('state',true)->
                      Where('month',false)->
                      orderby('id','desc')->
                      first()->$m }}
                      @php
                        $ar = quantity_exe($item->id,'action',activemonth());
                      @endphp
                      @if ($ar[0]==$ar[1])
                      <i class="fa fa-check-square text-success"></i>
                      @else
                        <span tabindex="0" role="button" class="badge badge-{{$ar[2]}}" data-toggle="popover" data-trigger="focus" title="Pendientes de Ejecución" data-content="{{$ar[4]}}" ><small>{{$ar[0]}}/{{$ar[1]}}</small></span>
                      @endif
                    </td>
                    <td class="align-top text-center">
                      {{ ucfirst($months[$item->definitions->pluck('start')->first()]) }}
                    </td>
                    <td class="align-top text-center">
                      {{ ucfirst($months[$item->definitions->pluck('finish')->first()]) }}
                    </td>
                    <td class="align-top text-center">
                      {{ App\Operation::Where('action_id',$item->id)->count() }}
                      
                    </td>

                    @component('layouts.partials.control',[
                                'id' => $item->id,
                                'url1' => $data["url1"],
                                'url2' => $data["url2"],
                                'add' => false,
                                'del' => true,
                                'editenable' => false,
                                'reconfigureenable' => false

                        ])
                    @endcomponent
                  </tr>
                  @php
                      ($item->department->id != $tmp)?
                  $tmp=$item->department->id:null;
                  ($item->goal->id != $tmp2)?
                  $tmp2=$item->goal->id:null;
                  @endphp
                @endif
                @empty
                  {!! emptyrecord(count($header)+1) !!}
                @endforelse
                </tbody>
                @component('layouts.partials.tfoot',[
                    'arg'=>$header
                    ])
                @endcomponent
              </table>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
      @unlessrole('Responsable|Usuario')
      <div class="row">
        <div class="col-sm-12 col-md-5">
          <div class="dataTables_info" role="status" aria-live="polite">
          </div>
        </div>
        <div class="col-sm-12 col-md-7">
          <div class="pagination-wrapper">
            {!! $action->appends(['search' => Request::get('search')])->render() !!}
          </div>
        </div>
      </div>
      @endunlessrole
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
      $('[data-trigger="focus"]').popover({ 
          html:true ,
          trigger: 'focus'
        });  
    });
</script>
@endsection