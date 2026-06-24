<!DOCTYPE html>
<html>

<head>
    <title></title>
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
    </style>
</head>

<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td bgcolor="#f4f4f4" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 200px 10px 20px 10px;"> </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#004d51" align="center" valign="top" style="padding: 10px 10px 5px 10px; border-radius: 4px 4px 0px 0px; color: #ffffff; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                            <img src="{{ $message->embed(public_path('img/LOGO-RODAPE.png')) }}" width="125" height="80" style="display: block; border: 0px;" />
                            <h5 class="small" style="font-weight: 400; margin: 2;">Certificado</h1>
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

                                Agradecemos a sua contribuição como {{ $cargo }} no 13º Congresso Brasileiro de Agroecologia.

                                <br><br>

                                Em anexo está o seu certificado de participação, de atividades em comissões ou apresentação de trabalho no 13º CBA.

                                <br><br>

                                Abraços,<br>
                                Plataforma de Inscrições e Submissões de Trabalhos - Participa (UFAPE/LMTS)<br>
                                Associação Brasileira de Agroecologia (ABA-Agroecologia) <br>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
