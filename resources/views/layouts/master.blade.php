@if (\Jenssegers\Agent\Facades\Agent::isMobile())
  @include('layouts.mobile')
@else
@php
@endphp
  <!DOCTYPE html>
  <html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>INACHA 1.2 | {{ title() }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.cs')}}s">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('ionicons/css/ionicons.min.css')}}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker-bs3.css')}}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.css')}}">

    <link rel="stylesheet" href="{{asset('css/carga.css')}}">
    <!-- Laravel Toastr -->
    <link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="{{ asset('datatables/css/jquery.dataTables.min.css')}}">
    @yield('css')
    
  </head>
  <!--
  BODY TAG OPTIONS:
  =================
  Apply one or more of the following classes to to the body tag
  to get the desired effect
  |---------------------------------------------------------|
  |LAYOUT OPTIONS | sidebar-collapse                        |
  |               | sidebar-mini                            |
  |---------------------------------------------------------|
  -->
  <body class="hold-transition sidebar-mini {{ sidebar()?'sidebar-collapse':null }}">
  <div class="wrapper">
    <!-- Navbar -->
  @include('layouts.partials.header')
  <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
    <!--
      <a href="index3.html" class="brand-link">
        <img src="{{ Avatar::create('Inacha')->setDimension(100)->setBackground('#007bff') }}" alt="Inacha Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Inacha 1.0</span>
      </a>
  -->
      <!-- Sidebar -->
    @include('layouts.partials.sidebar')
    <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
      </div>
      <!-- /.content-header -->
      <!-- Main content -->

@if (isset($refor))
  @if ($refor)
  <div id="contenedor_carga2">
      <section class="content">
        <h1>&nbsp;&nbsp;&nbsp;Atención modo Reformulación</h1>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Para salir de este modo por favor configurar en "<i class="fa fa-pencil"></i> Definición" </p> 
      </section>
  </div>
    <div  style=" filter: blur(7px);">
        @yield('content')
    </div>
  @else
        @yield('content')
  @endif
@else
  @yield('content')
@endif


    

    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-block-down">
        Anything you want
      </div>
      <!-- Default to the left -->
      <strong>Unidad de Gestión de Información &copy; 2018 <a href="http://www.ofep.gob.bo">OFEP</a>-</strong> Oficina
      Técnica para el Fortalecimiento de la Empresa Pública.
    </footer>
  </div>
  <!-- ./wrapper -->
  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- AdminLTE -->
  <script src="{{asset('dist/js/adminlte.js')}}"></script>
  <!-- Select2 -->
  <script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>
  <!-- InputMask -->
  <script src="{{asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
  <script src="{{asset('/plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
  <script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
  <!-- iCheck 1.0.1 -->
  <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
  <!-- Toastr -->
  <script src="{{asset('js/toastr.min.js')}}"></script>
  {!! Toastr::render() !!}
  <script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()

      //iCheck for checkbox and radio inputs
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      })

      //Red color scheme for iCheck
      $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
      })
      //Flat red color scheme for iCheck
      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      })
    })
  </script>
  <script>
    $('a[rel=modal]').on('click', function (evt) {
      evt.preventDefault();
      var modal = $('#modal').modal();
      modal
        .find('.modal-body')
        .load($(this).attr('href'), function (responseText, textStatus) {
          if (textStatus === 'success' ||
            textStatus === 'notmodified') {
              modal.show();
            }
        });
    });
  </script>
  <script>
    function sidebar(){
      $.ajax({
        type : 'get',
        url  :'/sidebar',
        data :{},
        success:function(datas){
        }
      });
    }
  </script>
    <script src="{{asset('datatables/js/jquery.dataTables.min.js')}}"></script>
  @yield('scripts')
  </body>
  </html>

@endif
