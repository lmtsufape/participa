<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    @page {
      margin: 1cm;
      size: A4 portrait;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      font-size: 12pt;
      line-height: 1.4;
      color: #333;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .header img {
      width: 100%;
      max-width: 600px;
      height: auto;
    }

    .content {
      padding: 0 20px;
      margin-bottom: 40px;
    }

    .receipt-title {
      text-align: center;
      font-size: 18pt;
      font-weight: bold;
      text-transform: uppercase;
      margin: 30px 0;
    }

    .receipt-text {
      font-size: 12pt;
      line-height: 1.6;
      margin-bottom: 30px;
      text-align: justify;
    }

    .receipt-date {
      text-align: right;
      font-size: 12pt;
      margin-bottom: 40px;
    }

    .signature-section {
      text-align: center;
      margin-top: 60px;
    }

    .signature-line {
      width: 200px;
      height: 1px;
      background-color: #333;
      margin: 10px 0;
      display: inline-block;
    }

    .signature-name {
      font-size: 12pt;
      font-weight: bold;
      margin: 5px 0;
    }

    .signature-title {
      font-size: 10pt;
      color: #666;
    }

    .footer {
      position: fixed;
      bottom: 20px;
      left: 20px;
      right: 20px;
      padding-top: 15px;
    }

    .footer img {
      width: 100%;
      max-width: 500px;
      height: auto;
    }

    .strong {
      font-weight: bold;
    }
  </style>
</head>
<body>

  {{-- Header --}}
  <div class="header">
    <img src="img/banner-site-cbee.jpg" alt="Header CBEE">
  </div>

  {{-- Content --}}
      <div class="content">
      <h2 class="receipt-title">RECIBO</h2>

      <div class="receipt-text">
        A <span class="strong">Associação Brasileira de Etnobiologia e Etnoecologia</span> declara, para os devidos fins,
        que recebeu de <span class="strong">{{ $nome }}</span> o valor de
        <span class="strong">R$ {{ number_format($valor, 2, ',', '.') }}</span>
        referente à taxa de inscrição no 15º Congresso Brasileiro de Etnobiologia e Etnoecologia. 
      </div>

      <div class="receipt-date">
        Belo Horizonte, {{ \Carbon\Carbon::parse($data)->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY') }}.
      </div>

      <div class="signature-section">
        <img src="img/assinatura_presidente_cbee.jpeg" alt="Assinatura Presidente" style="width: 200px; height: auto; margin-bottom: 10px; display: block; margin-left: auto; margin-right: auto;">
        <div class="signature-line" style="width: 200px; height: 1px; background-color: #333; margin: 10px auto; display: block;"></div>
        <div class="signature-name">Emmanuel Duarte Almada</div>
        <div class="signature-title">Presidente da comissão organizadora</div>
      </div>

      {{-- Código de Validação --}}
      <div style="margin-top: 40px; text-align: center; border: 1px solid #333; padding: 15px; max-width: 400px; margin-left: auto; margin-right: auto;">
        <div style="font-size: 10pt; margin-bottom: 5px;">Código de verificação:</div>
        <div style="font-size: 14pt; font-weight: bold; font-family: monospace; margin-bottom: 10px;">{{ $codigo_validacao }}</div>
        <div style="font-size: 8pt; text-align: center;">
          Para verificar a autenticidade deste documento acesse:<br>
          <strong>{{ url('/validar/recibo/' . $codigo_validacao) }}</strong>
        </div>
      </div>
    </div>

  {{-- Footer --}}
  <div class="footer" style="text-align: center;">
    <img src="img/logo-sbee.png" alt="Footer SBEE" style="width: 150px; height: auto;">
  </div>

</body>
</html>
