@if (\Jenssegers\Agent\Facades\Agent::isMobile())
  @include('layouts.mobile')

@else

  <!DOCTYPE html>
  <html lang="{{ config('app.locale') }}">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>INACHA 1.2 | Ingreso</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <!-- Toastr -->
    <link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  </head>

  <body class="hold-transition login-page">
  <div class="limiter">
    <div class="container-login100" style="background-image: url('{{ asset('img/plazamurillo.jpg') }}');">
      <div class="login-box">
        <div class="login-logo">

          <img src="{{asset('img/ofep_rojo.png')}}" class="img-fluid" alt="Responsive image">
        </div>
        <!-- /.login-logo -->
        <div class="card">
          <div class="card-body login-card-body">
            <p class="login-box-msg"><strong>SISTEMA DE PLANIFICACIÓN<br> OPERATIVA ANUAL</strong></p>

            {!! Form::open([
            'route' => 'login',
            'method' => 'POST',
            ]) !!}
            <div class="input-group mb-3">
              {!! Form::text('username',null,
              array(
              'id' => 'username',
              'value' => ' old("username") ',
              'required data-msg' => 'Ingrese el nombre de usuario',
              'class' => 'form-control',
              'placeholder'=> 'Nombre de usuario',)
              ) !!}
              <div class="input-group-append">
                <span class="fa fa-user input-group-text"></span>
              </div>
            </div>
            <div>
              {!! $errors->first('username', '<p class="text-danger small">:message</p>') !!}
            </div>
            <div class="input-group mb-3">
              {!! Form::password('password',
              array(
              'id' => 'password',
              'required data-msg' => 'Ingrese la contraseña',
              'class' => 'form-control',
              'placeholder'=> 'Contraseña',)
              ) !!}
              <div class="input-group-append">
                <span class="fa fa-lock input-group-text"></span>
              </div>
            </div>
            <div>
              {!! $errors->first('password', '<p class="text-danger small">:message</p>') !!}
            </div>
            <div class="row">
              <div class="col-8">
                <div>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-danger btn-block btn-flat">Ingresar</button>
              </div>
              <!-- /.col -->
            </div>
            {!! Form::close() !!}
          </div>
          <!-- /.login-card-body -->
        </div>
      </div>
      <!-- /.login-box -->
    </div>
  </div>
  <!-- jQuery -->
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- Toastr -->
  <script src="{{asset('js/toastr.min.js')}}"></script>
  {!! Toastr::render() !!}
  </body>

  </html>

@endif
