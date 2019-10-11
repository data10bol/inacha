<html>
<head>
  <title>{{ $title }}</title>
  <style>

    @page {
      margin-top: 6.5em;
      margin-left: 3em;
      margin-right: 3em;
      margin-bottom: 3em;
    }

    footer {
      position: fixed;
      bottom: -60px;
      left: 0px;
      right: 0px;
      height: 50px;
      text-align: right;
      font-size: 7;
      font-family: arial;
    }

    footer .page:after {
      content: counter(page);
    }

    header {
      position: fixed;
      bottom: 955px;
      left: 0px;
      right: 0px;
      height: 40px;
    }
  </style>
</head>
<body lang=ES-BO>
<header>
  <table width="100%"
         border="0"
         cellpadding="0"
         cellspacing="0">
    <tr>
      <td width="120 px" align="left">
        <img src="img/LogoOFEP.png" alt="LOGO" height="66" width="120">
      </td>
      <td align="center">
        <span lang=ES-BO style='font-size:14.0pt;font-family:"Arial",sans-serif'>
        <strong>{{ $title }}</strong>
        </span>
      </td>
      <td width="90 px" align="right">
        <img src="data:image/png;base64,
                        {!! base64_encode(QrCode::format('png')
                        ->size(90)
                        ->generate(''.Request::url().'')) !!} ">
      </td>
    </tr>
  </table>
</header>

<footer>
	<span lang=ES-BO style='font-size:7.0pt;font-family:"Arial",sans-serif'>
    OFICINA TÉCNICA PARA EL FORTALECIMIENTO DE LA EMPRESA PÚBLICA /
    @ {{ \Auth::user()->name }} /
    {{ date("Y-m-d H:i:s") }}
    <br>
	<span class="page">Página </span></span>
</footer>

<main>
  <p align=center style='text-align:center'>
  <table width="100%"
         border="1"
         cellpadding="2"
         cellspacing="2">

