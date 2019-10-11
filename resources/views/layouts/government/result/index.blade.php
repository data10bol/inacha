@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      @component('layouts.partials.icontrol',[
          'title' => 'RESULTADOS',
          'url1' => $data["url1"],
          'addtop' => true,
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
                @php $tmp = 0; $tmp2 = 0; @endphp
                @forelse($result as $item)
                  <tr>
                    <td class="align-top text-center" style="width: 30px">
                      <h6>{{ $item->target->policy->code
                                                }}.{{ $item->target->code
                                                }}.{{ $item->id }}
                      </h6>
                    </td>
                    <td class="align-top text-left">
                      @if($item->target->policy->code != $tmp)
                        {!! substr($item->target->policy->descriptions->first()->description, 0, 200) !!}
                        {!! strlen($item->target->policy->descriptions->first()->description)>200?'...':''!!}
                      @endif
                    </td>
                    <td class="align-top text-justify">
                      @if($item->target->code != $tmp2)
                        {!! substr($item->target->descriptions->first()->description, 0, 200) !!}
                        {!! strlen($item->target->descriptions->first()->description)>200?'...':''!!}
                      @endif
                    </td>
                    <td class="align-top text-justify">
                      {!! substr($item->descriptions->first()->description, 0, 200) !!}
                      {!! strlen($item->descriptions->first()->description)>200?'...':''!!}
                    </td>
                    @component('layouts.partials.control',[
                        'id' => $item->id,
                        'url1' => $data["url1"],
                        'url2' => $data["url2"],
                        'add' => true,
                        'del' => true,
                    ])
                    @endcomponent
                  </tr>
                  @php
                    ($item->target->policy->code != $tmp)?
                  $tmp=$item->target->policy->code:
                  null;
                  ($item->target->code != $tmp2)?
                  $tmp2=$item->target->code:
                  null;
                  @endphp
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
      <div class="row">
        <div class="col-sm-12 col-md-5">
          <div class="dataTables_info" role="status" aria-live="polite">
          </div>
        </div>
        <div class="col-sm-12 col-md-7">
          <div class="pagination-wrapper">
            {!! $result->appends(['search' => Request::get('search')])->render() !!}
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
