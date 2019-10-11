@extends('layouts.master')
@section('content')
<section>
    <!-- ckeditor -->
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">CREAR TAREA</h3>
                                @component('layouts.partials.hcontrol',[
                                    'id' => '0',
                                    'url1' => $data["url1"],
                                    'url2' => $data["url2"],
                                    'type' => 'create',
                                    'add' => false
                                ])
                                @endcomponent
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        {!! Form::open(['url' => $data["url1"],
                        'class' => 'form-horizontal',
                        'files' => true])
                        !!}
                        {{ Form::hidden('year', activeyear()) }}
                            @include ('layouts.institution.subtask.form')
                        {!! Form::close() !!}
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
</section>
@endsection
