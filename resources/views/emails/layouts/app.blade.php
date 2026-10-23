<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Participa')</title>

    <style>
        @media only screen and (max-width: 620px) {
            .email-container {
                width: 100% !important;
            }

            .email-content {
                padding: 24px 20px !important;
            }

            .email-header {
                padding: 24px 20px !important;
            }

            .email-title {
                font-size: 24px !important;
            }

            .email-custom-header {
                padding: 24px 20px 0 !important;
            }
        }
    </style>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    font-family: Arial, Helvetica, sans-serif;
    color: #172b35;
">

    {{-- Pré-visualização da mensagem --}}
    <div style="
        display: none;
        font-size: 1px;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        color: transparent;
    ">
        @yield('preview')
    </div>

    <table
        role="presentation"
        width="100%"
        cellpadding="0"
        cellspacing="0"
        border="0"
        style="background-color: #f4f4f4;"
    >
        <tr>
            <td align="center" style="padding: 32px 16px;">

                <table
                    role="presentation"
                    class="email-container"
                    width="600"
                    cellpadding="0"
                    cellspacing="0"
                    border="0"
                    style="
                        width: 100%;
                        max-width: 600px;
                        background-color: #ffffff;
                        border: 1px solid #e2e8ec;
                        border-radius: 8px;
                    "
                >

                    {{-- Cabeçalho institucional --}}
                    <tr>
                        <td
                            class="email-header"
                            style="
                                padding: 24px 32px;
                                background-color: #00454d;
                                border-radius: 8px 8px 0 0;
                            "
                        >
                            <table
                                role="presentation"
                                width="100%"
                                cellpadding="0"
                                cellspacing="0"
                                border="0"
                            >
                                <tr>
                                    <td style="
                                        font-size: 25px;
                                        font-weight: bold;
                                        color: #ffffff;
                                    ">
                                        Participa
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Detalhe superior --}}
                    <tr>
                        <td
                            height="4"
                            style="
                                height: 4px;
                                background-color: #007e85;
                                font-size: 0;
                                line-height: 0;
                            "
                        >
                            &nbsp;
                        </td>
                    </tr>

                    {{-- Cabeçalho personalizado --}}
                    @hasSection('header')
                        <tr>
                            <td
                                class="email-custom-header"
                                align="center"
                                style="padding: 28px 32px 0;"
                            >
                                @yield('header')
                            </td>
                        </tr>
                    @endif

                    {{-- Conteúdo variável --}}
                    <tr>
                        <td
                            class="email-content"
                            style="
                                padding: 36px 32px;
                                font-size: 15px;
                                line-height: 1.7;
                                color: #344c5a;
                            "
                        >
                            @yield('content')
                        </td>
                    </tr>

                    {{-- Rodapé --}}
                    <tr>
                        <td
                            style="
                                padding: 24px 32px;
                                background-color: #f8fafb;
                                border-top: 1px solid #e2e8ec;
                                border-radius: 0 0 8px 8px;
                                font-family: Arial, Helvetica, sans-serif;
                                text-align: center;
                            "
                        >
                            @section('footer')
                                <p style="
                                    margin: 0 0 8px;
                                    font-size: 13px;
                                    color: #526675;
                                ">
                                    Este é um e-mail automático da plataforma Participa.
                                </p>

                                <p style="
                                    margin: 0;
                                    font-size: 12px;
                                    color: #6b7d89;
                                ">
                                    &copy; {{ now()->year }} Participa — UFAPE
                                </p>
                            @show
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
