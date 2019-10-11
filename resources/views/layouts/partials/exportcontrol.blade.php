@php
  $user = Auth::user();
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

  if(!isset($report))
      $report = false;

@endphp


<div class="col-sm-12 col-md-6">
  <div>
    {!! Form::button('<i class="fa fa-file-excel-o"></i>',
        array('title' => 'Excel',
                'type' => 'button',
                'class' => 'btn btn-success btn-sm',
                'disabled' => !$excel?'disabled':null,
                'onclick'=>!$report?
                'window.open("'. $urlexcel .'")':
                'window.location.href=("'. $urlexcel .'")',))
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
            'onclick'=>!$report?
            'window.open("'. $urlpdf .'")':
            'window.location.href=("'. $urlpdf .'")',))
    !!}
  </div>
</div>
