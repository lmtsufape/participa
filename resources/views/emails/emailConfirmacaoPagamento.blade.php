<!DOCTYPE html>
<html>

<head>
    <title>Confirmação de Pagamento</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        .info-box {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .info-label {
            color: #004d51;
            font-weight: bold;
        }
    </style>
</head>

<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td bgcolor="#f4f4f4" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 10px 40px 10px;"> </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#004d51" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #ffffff; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                            <img src="{{ $message->embed(public_path('img/logo-novo.png')) }}" width="125" height="120" style="display: block; border: 0px;" />
                            <h5 class="small" style="font-weight: 400; margin: 2;">Confirmação de pagamento</h5>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">
                                Olá {{ $user->name }}!

                                <br> <br>

                                Recebemos seu pagamento para participar do evento "{{ $evento->nome }}".

                                <br> <br>
                                Sua inscrição foi registrada com sucesso.

                                <div class="info-box">
                                    <p style="margin: 0;">
                                        <span class="info-label">Número da Inscrição:</span> {{ $inscricao->id }}<br>
                                        <span class="info-label">Categoria:</span> {{ $inscricao->categoria->nome }}<br>
                                        <span class="info-label">Valor Pago:</span> R$ {{ number_format($inscricao->categoria->valor_total, 2, ',') }}<br>
                                        <span class="info-label">Data do Pagamento:</span> {{ $inscricao->pagamento->created_at->format('d/m/Y H:i') }}<br>
                                        <span class="info-label">Status:</span> Confirmado
                                    </p>
                                </div>

                                <br>
                                Agradecemos pelo interesse em colaborar com a realização deste evento!

                                @component('mail::button', ['url' => route('home')])
                                Acessar o portal do evento
                                @endcomponent

                            Atenciosamente,<br>
                                Plataforma de inscrições e submissões de trabalhos <br>
                                Participa (UFAPE/LMTS) <br>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>