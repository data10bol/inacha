<html>
<head>
  <title>{{ $title }}</title>
  <style>

    @page {
      margin-top: 9.5em;
      margin-left: 2em;
      margin-right: 2em;
      margin-bottom: 2em;
    }

    footer {
      position: fixed;
      bottom: -50px;
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
      bottom: 700px;
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
                        ->generate(''.Request::url().' '.\Auth::user()->name.'')) !!} ">
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

  <table 
         border="1"
         cellpadding="2"
         cellspacing="2">
    <tr>
      @foreach($arg as $item)
      @if (strlen($item['text'])>0)
      <th align="{{ strtolower($item['align']) }}" >
        <span lang=ES-BO style='font-size:9.0pt;font-family:"Arial",sans-serif'>
          <strong>{{ strtoupper($item['text']) }}</strong>
        </span>
      </th>
      @endif
        
      @endforeach
    </tr>
