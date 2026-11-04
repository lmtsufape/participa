@extends('emails.layouts.app')

@section('content')
    <h2 style="
        font-size: 22px;
        font-weight: 700;
        line-height: 1.35;
        color: #18313a;
        margin: 0 0 24px;
    ">
        Confirme seu endereço de e-mail
    </h2>

    <p style="margin: 0 0 16px;">
        Olá, {{ $usuario->name }}!
    </p>

    <p style="margin: 0 0 24px; line-height: 1.7;">
        Seu cadastro no <strong>Participa</strong> foi realizado
        com sucesso. Para começar a utilizar a plataforma,
        confirme seu endereço de e-mail.
    </p>

    {{-- Ação principal --}}
    <table role="presentation"
           cellpadding="0"
           cellspacing="0"
           border="0"
           align="center"
           style="margin: 0 auto 14px;">
        <tr>
            <td align="center"
                bgcolor="#16874e"
                style="border-radius: 6px;">
                <a href="{{ $url }}"
                   style="
                       display: inline-block;
                       padding: 14px 32px;
                       background-color: #034652;
                       border-radius: 6px;
                       color: #ffffff;
                       font-family: Arial, sans-serif;
                       font-size: 15px;
                       font-weight: 600;
                       text-decoration: none;
                   ">
                    Confirmar meu e-mail
                </a>
            </td>
        </tr>
    </table>

    <p style="
        text-align: center;
        color: #747d87;
        font-size: 12px;
        margin: 0 0 30px;
    ">
        Link válido por
        {{ config('auth.verification.expire', 60) }} minutos.
    </p>

    <p style="
        font-size: 14px;
        line-height: 1.7;
        margin: 0 0 24px;
    ">
        Se você não realizou este cadastro,
        pode desconsiderar esta mensagem.
    </p>

    {{-- Link alternativo --}}
    <div style="
        border-top: 1px solid #e9ecef;
        padding-top: 20px;
    ">
        <p style="
            font-size: 13px;
            color: #667085;
            margin: 0 0 8px;
        ">
            <strong>O botão não funcionou?</strong>
        </p>

        <p style="
            font-size: 12px;
            color: #667085;
            line-height: 1.6;
            margin: 0 0 8px;
        ">
            Copie o link abaixo e cole no seu navegador:
        </p>

        <p style="
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
            word-break: break-all;
            overflow-wrap: anywhere;
        ">
            <a href="{{ $url }}"
               style="color: #007e85;">
                {{ $url }}
            </a>
        </p>
    </div>
@endsection
