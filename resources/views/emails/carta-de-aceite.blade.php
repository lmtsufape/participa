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
        Carta de Aceite do 13º CBA — confirmação de aprovação do trabalho.
    </div>

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f5f7fb;">
        <tr>
            <td align="center" style="padding:24px;">
                <table role="presentation" class="container" cellpadding="0" cellspacing="0" border="0"
                    width="600"
                    style="width:600px;background:#ffffff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
                    <tr>
                        <td align="center" style="padding:28px 28px 10px 28px;">
                            @if (!empty($imgPath))
                                <img src="{{ $message->embed($imgPath) }}" alt="Logo do evento" width="160"
                                    style="display:block;width:160px;height:auto;border:0;outline:none;text-decoration:none;">
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="content"
                            style="padding:10px 32px 28px 32px;font-family:Arial,Helvetica,sans-serif;color:#111;line-height:1.6;">
                            <h1
                                style="margin:8px 0 16px 0;font-size:28px;line-height:1.25;text-align:center;color:#111;">
                                Carta de Aceite</h1>

                            <p style="margin:0 0 14px 0;font-size:16px; text-align:justify;">
                                Temos a satisfação de comunicar que, após análise da Comissão Científica, o trabalho
                                intitulado
                                <strong>{{ $trabalho->titulo }}</strong>,
                                de autoria de <strong>{{ $trabalho->autor->name}}</strong>,
                                foi <strong>aprovado</strong> na modalidade
                                <strong>{{$trabalho->modalidade->nome}}</strong> para apresentação no
                                evento
                                <strong>13º CONGRESSO BRASILEIRO DE AGROECOLOGIA (CBA)</strong> — Agroecologia,
                                Convivência com os Territórios Brasileiros e Justiça Climática.
                                A ser realizado de <strong>15 a 18 de outubro de 2025</strong>, na
                                <strong>Universidade Federal do Vale do São Francisco (UNIVASF), Juazeiro-BA</strong>.
                            </p>

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

                                    </div>
                                    </td>
                                </tr>
                                </table>

                            <p style="margin:22px 0 4px 0;font-size:16px;text-align:center;">
                                Comissão Saberes e Conhecimentos Técnico-Científicos do 13º CBA
                            </p>
                            <p style="margin:0 0 4px 0;font-size:14px;text-align:center;">
                                <a href="mailto:tapirisdesaberes.cba@gmail.com"
                                    style="color:#2563eb;text-decoration:underline;">
                                    tapirisdesaberes.cba@gmail.com
                                </a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center"
                            style="padding:14px 24px 24px 24px;font-family:Arial,Helvetica,sans-serif;color:#6b7280;font-size:12px;">
                            <div style="color:#6b7280;">
                                © {{ date('Y') }} 13º CBA • Este é um e-mail automático. Não responda.
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
