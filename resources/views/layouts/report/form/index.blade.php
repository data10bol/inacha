@extends('layouts.master')
@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
          <h3 class="card-title"><b>FORMULARIOS</b></h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <table class="table table-condensed">
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Formulario</th>
                    <th>Descripción</th>
                    <th style="width: 40px">Exportar</th>
                </tr>
                @forelse ($forms as $item)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $item["name"] }}
                        </td>
                        <td>
                            {{ $item["description"] }}
                        </td>
                        <td class="align-top text-center" style="width: 170px">
                            {!! Form::button('<i class="fa fa-eye"></i>',
                                array('title' => 'Ver',
                                        'type' => 'button',
                                        'class' => 'btn btn-default btn-sm',
                                        'onclick'=>'window.location.href="'. $data["url1"] .'/'. $item["link"].'"', ))
                            !!}
                            {!! Form::button('<i class="fa fa-file-pdf-o"></i>',
                                array('title' => 'PDF',
                                        'type' => 'button',
                                        'class' => 'btn btn-danger btn-sm',
                                        'onclick'=>'window.location.href="'. $data["url2"] .'/'. $item["link"].'"', ))
                            !!}
                            {!! Form::button('<i class="fa fa-file-excel-o"></i>',
                                array('title' => 'Excel',
                                        'type' => 'button',
                                        'class' => 'btn btn-success btn-sm',
                                        'onclick'=>'window.location.href="'. $data["url3"] .'/'. $item["link"].'"', ))
                            !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <font color="red">SIN REGISTROS</font>
                        </td>
                    </tr>
                @endforelse
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</section>
@endsection
