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
                            <img src="{{ $message->embed(public_path('img/logoatualizadabranca.png')) }}" width="125" height="120" style="display: block; border: 0px;" />
                            <h5 class="small" style="font-weight: 400; margin: 2;">Cadastro realizado!</h5>
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
                                Olá <strong>{{ $user->name }}</strong>!

                                <br> <br>

                                <p>Seu cadastro no sistema de inscrições e submissões de trabalhos do Participa foi concluído com sucesso!</p>

                                <p>Agora você pode se inscrever em qualquer um dos eventos que venham a ser realizados pelo sistema!</p>
                                <br>
                                <p>Para acessar a plataforma, clique no botão abaixo:</p>

                                @component('mail::button', ['url' => route('index')])
                                Acessar o portal do evento
                                @endcomponent

                                Se tiver qualquer dúvida, estamos à disposição.

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
