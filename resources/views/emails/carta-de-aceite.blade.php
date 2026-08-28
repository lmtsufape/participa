<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Carta de Aceite</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @media (max-width: 620px) {
            .container {
                width: 100% !important;
            }

            .content {
                padding: 20px !important;
            }

            h1 {
                font-size: 24px !important;
            }

            p {
                font-size: 15px !important;
            }
        }
    </style>
</head>

<body style="margin:0;padding:0;background:#f5f7fb;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
        Carta de Aceite do XV CBEE — confirmação de aprovação do trabalho.
    </div>

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f5f7fb;">
        <tr>
            <td align="center" style="padding:24px;">
                <table role="presentation" class="container" cellpadding="0" cellspacing="0" border="0"
                    width="600"
                    style="width:600px;background:#ffffff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
                    <tr>
                        <td align="center" style="padding:28px 28px 10px 28px;">
                            <img src="{{ $message->embed(public_path('img/banner-site-cbee.jpg')) }}" 
                                alt="Header CBEE" 
                                width="544"
                                style="width: 100%; max-width: 544px; height: auto; display: block; border: 0; border-radius: 6px;">
                        </td>
                    </tr>

                    <tr>
                        <td class="content"
                            style="padding:10px 32px 28px 32px;font-family:Arial,Helvetica,sans-serif;color:#111;line-height:1.6;">
                            <h1
                                style="margin:8px 0 16px 0;font-size:28px;line-height:1.25;text-align:center;color:#111;">
                                Carta de Aceite</h1>

                            <p style="margin:0 0 14px 0;font-size:16px; text-align:justify;">
                                Temos a satisfação de comunicar que, após análise da Comissão Científica, o trabalho intitulado
                                "<strong>{{ $trabalho->titulo }}</strong>",
                                de autoria de <strong>{{ $trabalho->autor->name}}</strong>@if ($trabalho->coautors->count()), com os coautores/as <strong>{{ $trabalho->coautors->pluck('user.name')->join(', ', ' e ') }}</strong>@endif,
                                foi <strong>aprovado</strong> na modalidade
                                <strong>{{$trabalho->modalidade->nome}}</strong> para apresentação no
                                evento
                                <strong>XV CONGRESSO BRASILEIRO DE ETNOBIOLOGIA E ETNOECOLOGIA (CBEE)</strong>,
                                Convivência com os Territórios Brasileiros e Justiça Climática. O evento ocorrerá entre os dias 16 a 19 de novembro de 2026 na Universidade Federal de Minas Gerais (UFMG), cidade de Belo Horizonte, estado de Minas Gerais, Brasil.
                            </p>
                            <div class="receipt-date">
                                Belo Horizonte, {{ \Carbon\Carbon::parse($trabalho->aprovacao_emitida_em ?? now())->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY') }}.
                            </div>
                            <div class="signature-section" style="text-align: center; margin-top: 30px;">
                                <img src="{{ $message->embed(public_path('img/assinatura_presidente_cbee.jpeg')) }}" alt="Assinatura Presidente" style="width: 200px; height: auto; margin-bottom: 10px; display: block; margin-left: auto; margin-right: auto;">
                                <div class="signature-line" style="width: 200px; height: 1px; background-color: #333; margin: 10px auto; display: block;"></div>
                                <div class="signature-name" style="font-weight: bold;">Emmanuel Duarte Almada</div>
                                <div class="signature-title" style="font-size: 14px; color: #555;">Presidente da comissão organizadora</div>
                            </div>


                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                    style="margin:18px 0;padding:0;">
                                <tr>
                                    <td align="center">
                                    <div style="display:inline-block;text-align:center;background:#f8fafc;border:1px solid #e5e7eb;
                                                border-radius:10px;padding:16px 18px;max-width:520px;">
                                        <div style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#374151;margin-bottom:8px;">
                                        Código oficial de aprovação
                                        </div>

                                        <div style="font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,'Liberation Mono','Courier New',monospace;
                                                    font-size:22px;line-height:1.2;letter-spacing:2px;color:#111;margin:0;">
                                        {{ $codigo }}
                                        </div>

                                        <div style="margin-top:8px;font-size:12px;color:#6b7280;font-family:Arial,Helvetica,sans-serif;">
                                            Acesse o link:
                                            <a href="{{ route('validarCertificado') }}"
                                               style="color:#2563eb;text-decoration:underline;">
                                              {{ route('validarCertificado') }}
                                            </a>
                                        </div>
                                        <br>
                                        <div class="footer" style="text-align: center; margin-top: 10px;">
                                            <img src="{{ $message->embed(public_path('img/logo-sbee.png')) }}" alt="Footer SBEE" style="width: 150px; height: auto; display: inline-block;">
                                        </div>

                                    </div>
                                    </td>
                                </tr>
                                </table>

    
                        </td>
                    </tr>

                    <tr>
                        <td align="center"
                            style="padding:14px 24px 24px 24px;font-family:Arial,Helvetica,sans-serif;color:#6b7280;font-size:12px;">
                            <div style="color:#6b7280;">
                                © {{ date('Y') }} XV CBEE • Este é um e-mail automático. Não responda.
                            </div>
                        </td>
                    </tr>
                </table>

                <div style="height:24px;line-height:24px;">&nbsp;</div>
            </td>
        </tr>
    </table>
</body>

</html>
