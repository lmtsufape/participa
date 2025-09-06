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
                        <td align="center" valign="top" style="padding: 40px 10px 40px 10px;"> </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#004d51" align="center" valign="top"
                            style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #ffffff; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                            <img src="{{ $message->embed(public_path('img/LOGO-RODAPE.png')) }}" width="125"
                                height="120" style="display: block; border: 0px;" />
                            <h5 class="small" style="font-weight: 400; margin: 2;">Convite para avaliação não aceito</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="left"
                            style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">
                                Olá {{ $coordenador->name }}!

                                <br> <br>

                                Informamos que o avaliador <strong>{{ $revisor->user->name }}</strong> recusou o convite
                                para avaliar um trabalho no evento <strong>{{ $evento->nome }}</strong>.

                                <br><br>

                                <strong>Detalhes do Trabalho:</strong><br>
                                <strong>Título:</strong> {{ $trabalho->titulo }}<br>
                                <strong>Modalidade:</strong> {{ $trabalho->modalidade->nome }}<br>
                                <strong>Área:</strong> {{ $trabalho->area->nome }}

                                <br><br>

                                <strong>Justificativa da Recusa:</strong><br>
                                <blockquote style="margin: 10px 0; padding: 10px; border-left: 4px solid #004d51; background-color: #f9f9f9;">
                                    {{ $justificativa }}
                                </blockquote>

                                <strong>Próximos Passos:</strong><br>
                                Recomendamos que você:<br>
                                1. Verifique se há outros avaliadores disponíveis para esta área<br>
                                2. Considere fazer uma nova atribuição manual<br>
                                3. Entre em contato com outros potenciais avaliadores se necessário

                                <br><br>

                                Abraços,<br>
                                Plataforma de inscrições e submissões de trabalhos<br>
                                Participa (UFAPE/LMTS)<br>
                                Associação Brasileira de Agroecologia (ABA)
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>

</table>
</body>

</html>
