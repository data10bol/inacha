@php
  $path = explode("/",$url1);
  $user = Auth::user();

if(!isset($addtop))
  $addtop = true;

if(!isset($search))
  $search = true;

if(!isset($editenable))
  $editenable = App\Configuration::Select('edit')->Where('status',true)->pluck('edit')->first();

// EXPORT CONTROLS

if(!isset($excel))
  $excel = false;

if(!isset($word))
  $word = false;

if(!isset($pdf))
  $pdf = false;

if(!isset($urlexcel))
  $urlexcel = false;

if(!isset($urlword))
  $urlword = false;

if(!isset($urlpdf))
  $urlpdf = false;

@endphp

<div class="col-12">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><b>{{ $title }}</b></h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div>
        <div class="row">
          <div class="col-sm-12 col-md-5">
            <div>
              {!! Form::button('<i class="fa fa-plus"></i> <b>Nuevo</b>',
                              array('title' => 'Nuevo',
                                      'type' => 'button',
                                      'class' => 'btn btn-danger btn-round btn-sm',
                                      'disabled' => !($addtop)?null:
                                      ($user->hasPermissionTo('create.'.end($path))&&$editenable) ? null :
                                      'disabled',
                                      'onclick'=>'window.location.href="'. $url1 .'/create"', ))
              !!}

              {!! Form::button('<i class="fa fa-file-excel-o"></i>',
                  array('title' => 'Excel',
                          'type' => 'button',
                          'class' => 'btn btn-success btn-sm',
                          'disabled' => !$excel?'disabled':null,
                          'onclick'=>'window.open("'. $urlexcel .'")',))
              !!}
              {!! Form::button('<i class="fa fa-file-word-o" aria-hidden="true"></i>',
                  array('title' => 'Word',
                      'type' => 'button',
                      'class' => 'btn btn-info btn-sm',
                      'disabled' => !$word?'disabled':null,
                      'onclick'=>'window.open("'. $urlword .'")', ))
              !!}
              {!! Form::button('<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',
                  array('title' => 'PDF',
                      'type' => 'button',
                      'class' => 'btn btn-danger btn-sm',
                      'disabled' => !$pdf?'disabled':null,
                      'onclick'=>'window.open("'. $urlpdf .'")', ))
              !!}
            </div>
            
          </div>
          <div class="col-sm-12 col-md-3">
            @if(isset($element))
            {!! $element->appends(['search' => Request::get('search')])->render() !!}
            @endif
          </div>
          <div class="col-sm-12 col-md-4">
          @if($search)
            <div>
              {!! Form::open(['method' => 'GET',
                'url' => $url1,
                'class' => 'form-inline my-2 my-lg-0 float-right',
                'name' => 'search'])
              !!}
              <div class="input-group input-group-sm" style="width: 150px;">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control"
                       placeholder="Buscar...">
                <div class="input-group-append">
                  {!! Form::button('<i class="fa fa-search"></i>',
                          array('type' => 'submit',
                                  'class' => 'btn btn-default',
                                  'title' => 'Buscar' ))
                  !!}
                </div>
              </div>
              {!! Form::close() !!}
            </div>
          @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

