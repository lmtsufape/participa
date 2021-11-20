<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Respostas dos formulários da modalidade selecionada</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Nunito);

        :root {
            --blue: #3490dc;
            --indigo: #6574cd;
            --purple: #9561e2;
            --pink: #f66d9b;
            --red: #e3342f;
            --orange: #f6993f;
            --yellow: #ffed4a;
            --green: #38c172;
            --teal: #4dc0b5;
            --cyan: #6cb2eb;
            --white: #fff;
            --gray: #6c757d;
            --gray-dark: #343a40;
            --primary: #3490dc;
            --secondary: #6c757d;
            --success: #38c172;
            --info: #6cb2eb;
            --warning: #ffed4a;
            --danger: #e3342f;
            --light: #f8f9fa;
            --dark: #343a40;
            --breakpoint-xs: 0;
            --breakpoint-sm: 576px;
            --breakpoint-md: 768px;
            --breakpoint-lg: 992px;
            --breakpoint-xl: 1200px;
            --font-family-sans-serif: "Nunito", sans-serif;
            --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            font-family: sans-serif;
            line-height: 1.15;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        body {
            margin: 0;
            font-family: "Nunito", sans-serif;
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.6;
            color: #212529;
            text-align: left;
            background-color: #f8fafc;
        }

        [tabindex="-1"]:focus:not(:focus-visible) {
            outline: 0 !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        dd {
            margin-bottom: 0.5rem;
            margin-left: 0;
        }

        strong {
            font-weight: bolder;
        }

        table {
            border-collapse: collapse;
        }

        th {
            text-align: inherit;
        }

        [type=button],
        [type=reset],
        [type=submit] {
            -webkit-appearance: button;
        }

        [type=button]:not(:disabled),
        [type=reset]:not(:disabled),
        [type=submit]:not(:disabled) {
            cursor: pointer;
        }

        [type=button]::-moz-focus-inner,
        [type=reset]::-moz-focus-inner,
        [type=submit]::-moz-focus-inner {
            padding: 0;
            border-style: none;
        }

        [type=number]::-webkit-inner-spin-button,
        [type=number]::-webkit-outer-spin-button {
            height: auto;
        }

        [type=search] {
            outline-offset: -2px;
            -webkit-appearance: none;
        }

        [type=search]::-webkit-search-decoration {
            -webkit-appearance: none;
        }

        ::-webkit-file-upload-button {
            font: inherit;
            -webkit-appearance: button;
        }

        [hidden] {
            display: none !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h1,
        .h1 {
            font-size: 2.25rem;
        }

        h2,
        .h2 {
            font-size: 1.8rem;
        }

        h3,
        .h3 {
            font-size: 1.575rem;
        }

        h4,
        .h4 {
            font-size: 1.35rem;
        }

        h5,
        .h5 {
            font-size: 1.125rem;
        }

        h6,
        .h6 {
            font-size: 0.9rem;
        }

        .row {
            display: -webkit-box;
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-sm,
        .col-sm-12,
        .col-sm-11,
        .col-sm-10,
        .col-sm-9,
        .col-sm-8,
        .col-sm-7,
        .col-sm-6,
        .col-sm-5,
        .col-sm-4,
        .col-sm-3,
        .col-sm-2,
        .col-sm-1,
        .col,
        .col-12,
        .col-11,
        .col-10,
        .col-9,
        .col-8,
        .col-7,
        .col-6,
        .col-5,
        .col-4,
        .col-3,
        .col-2,
        .col-1 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .col {
            flex-basis: 0;
            -webkit-box-flex: 1;
            flex-grow: 1;
            max-width: 100%;
        }

        .col-1 {
            -webkit-box-flex: 0;
            flex: 0 0 8.3333333333%;
            max-width: 8.3333333333%;
        }

        .col-2 {
            -webkit-box-flex: 0;
            flex: 0 0 16.6666666667%;
            max-width: 16.6666666667%;
        }

        .col-3 {
            -webkit-box-flex: 0;
            flex: 0 0 25%;
            max-width: 25%;
        }

        .col-4 {
            -webkit-box-flex: 0;
            flex: 0 0 33.3333333333%;
            max-width: 33.3333333333%;
        }

        .col-5 {
            -webkit-box-flex: 0;
            flex: 0 0 41.6666666667%;
            max-width: 41.6666666667%;
        }

        .col-6 {
            -webkit-box-flex: 0;
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-7 {
            -webkit-box-flex: 0;
            flex: 0 0 58.3333333333%;
            max-width: 58.3333333333%;
        }

        .col-8 {
            -webkit-box-flex: 0;
            flex: 0 0 66.6666666667%;
            max-width: 66.6666666667%;
        }

        .col-9 {
            -webkit-box-flex: 0;
            flex: 0 0 75%;
            max-width: 75%;
        }

        .col-10 {
            -webkit-box-flex: 0;
            flex: 0 0 83.3333333333%;
            max-width: 83.3333333333%;
        }

        .col-11 {
            -webkit-box-flex: 0;
            flex: 0 0 91.6666666667%;
            max-width: 91.6666666667%;
        }

        .col-12 {
            -webkit-box-flex: 0;
            flex: 0 0 100%;
            max-width: 100%;
        }

        @media (min-width: 576px) {
            .col-sm {
                flex-basis: 0;
                -webkit-box-flex: 1;
                flex-grow: 1;
                max-width: 100%;
            }

            .col-sm-1 {
                -webkit-box-flex: 0;
                flex: 0 0 8.3333333333%;
                max-width: 8.3333333333%;
            }

            .col-sm-2 {
                -webkit-box-flex: 0;
                flex: 0 0 16.6666666667%;
                max-width: 16.6666666667%;
            }

            .col-sm-3 {
                -webkit-box-flex: 0;
                flex: 0 0 25%;
                max-width: 25%;
            }

            .col-sm-4 {
                -webkit-box-flex: 0;
                flex: 0 0 33.3333333333%;
                max-width: 33.3333333333%;
            }

            .col-sm-5 {
                -webkit-box-flex: 0;
                flex: 0 0 41.6666666667%;
                max-width: 41.6666666667%;
            }

            .col-sm-6 {
                -webkit-box-flex: 0;
                flex: 0 0 50%;
                max-width: 50%;
            }

            .col-sm-7 {
                -webkit-box-flex: 0;
                flex: 0 0 58.3333333333%;
                max-width: 58.3333333333%;
            }

            .col-sm-8 {
                -webkit-box-flex: 0;
                flex: 0 0 66.6666666667%;
                max-width: 66.6666666667%;
            }

            .col-sm-9 {
                -webkit-box-flex: 0;
                flex: 0 0 75%;
                max-width: 75%;
            }

            .col-sm-10 {
                -webkit-box-flex: 0;
                flex: 0 0 83.3333333333%;
                max-width: 83.3333333333%;
            }

            .col-sm-11 {
                -webkit-box-flex: 0;
                flex: 0 0 91.6666666667%;
                max-width: 91.6666666667%;
            }

            .col-sm-12 {
                -webkit-box-flex: 0;
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-dark,
        .table-dark>th,
        .table-dark>td {
            background-color: #c6c8ca;
        }

        .table-dark th,
        .table-dark td,
        .table-dark thead th,
        .table-dark tbody+tbody {
            border-color: #95999c;
        }

        .table .thead-dark th {
            color: #fff;
            background-color: #343a40;
            border-color: #454d55;
        }

        .table-dark {
            color: #fff;
            background-color: #343a40;
        }

        .table-dark th,
        .table-dark td,
        .table-dark thead th {
            border-color: #454d55;
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .form-text {
            display: block;
            margin-top: 0.25rem;
        }

        .form-row {
            display: -webkit-box;
            display: flex;
            flex-wrap: wrap;
            margin-right: -5px;
            margin-left: -5px;
        }

        .form-row>.col,
        .form-row>[class*=col-] {
            padding-right: 5px;
            padding-left: 5px;
        }

        .card {
            position: relative;
            display: -webkit-box;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
        }

        .card-body {
            -webkit-box-flex: 1;
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1.25rem;
        }

        .card-title {
            margin-bottom: 0.75rem;
        }

        .card-text:last-child {
            margin-bottom: 0;
        }

        .card-link:hover {
            text-decoration: none;
        }

        .card-link+.card-link {
            margin-left: 1.25rem;
        }

        @-webkit-keyframes progress-bar-stripes {
            from {
                background-position: 1rem 0;
            }

            to {
                background-position: 0 0;
            }
        }

        @keyframes progress-bar-stripes {
            from {
                background-position: 1rem 0;
            }

            to {
                background-position: 0 0;
            }
        }

        @-webkit-keyframes spinner-border {
            to {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner-border {
            to {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spinner-grow {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            50% {
                opacity: 1;
            }
        }

        @keyframes spinner-grow {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            50% {
                opacity: 1;
            }
        }

        @supports ((position: -webkit-sticky) or (position: sticky)) {
            .sticky-top {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                z-index: 1020;
            }
        }

        .h-25 {
            height: 25% !important;
        }

        .h-50 {
            height: 50% !important;
        }

        .h-75 {
            height: 75% !important;
        }

        .h-100 {
            height: 100% !important;
        }

        .p-0 {
            padding: 0 !important;
        }

        .p-1 {
            padding: 0.25rem !important;
        }

        .p-2 {
            padding: 0.5rem !important;
        }

        .p-3 {
            padding: 1rem !important;
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        .p-5 {
            padding: 3rem !important;
        }

        @media (min-width: 576px) {
            .p-sm-0 {
                padding: 0 !important;
            }

            .p-sm-1 {
                padding: 0.25rem !important;
            }

            .p-sm-2 {
                padding: 0.5rem !important;
            }

            .p-sm-3 {
                padding: 1rem !important;
            }

            .p-sm-4 {
                padding: 1.5rem !important;
            }

            .p-sm-5 {
                padding: 3rem !important;
            }
        }

        .text-dark {
            color: #343a40 !important;
        }

        .text-body {
            color: #212529 !important;
        }

        @media print {

            *,
            *::before,
            *::after {
                text-shadow: none !important;
                box-shadow: none !important;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }

            p,
            h2,
            h3 {
                orphans: 3;
                widows: 3;
            }

            h2,
            h3 {
                page-break-after: avoid;
            }

            @page {
                size: a3;
            }

            body {
                min-width: 992px !important;
            }

            .table {
                border-collapse: collapse !important;
            }

            .table td,
            .table th {
                background-color: #fff !important;
            }

            .table-dark {
                color: inherit;
            }

            .table-dark th,
            .table-dark td,
            .table-dark thead th,
            .table-dark tbody+tbody {
                border-color: #dee2e6;
            }

            .table .thead-dark th {
                color: inherit;
                border-color: #dee2e6;
            }
        }

    </style>
</head>

<body>
    <div id="divListarCriterio">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="titulo-detalhes">Formulário(s) da modalidade: <strong> {{ $modalidade->nome }}</strong>
                </h3>
            </div>
        </div>
    </div>
    @foreach ($modalidade->forms as $form)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $form->titulo }}</h5>
                <p class="card-text">
                    @foreach ($form->perguntas as $pergunta)
                        <div class="card">
                            <div class="card-body">
                                <p>Pergunta: {{ $pergunta->pergunta }}</p>
                                @if ($pergunta->respostas->first()->opcoes->count())
                                    Resposta com Multipla escolha:
                                    <table class="table table-striped table-dark">
                                        <thead>
                                            <tr>
                                                <th scope="col">Revisor</th>
                                                <th scope="col">Trabalho</th>
                                                <th scope="col">Resposta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pergunta->respostas as $resposta)
                                                @if ($resposta->revisor != null || $resposta->trabalho != null)
                                                    <tr>
                                                        <td>{{ $resposta->revisor->user->name }}</td>
                                                        <td>{{ $resposta->trabalho->titulo }} </td>
                                                        <td>{{ $resposta->opcoes[0]->titulo }}</td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <p>Sem respostas</p>
                                            @endforelse
                                        </tbody>
                                    </table>
                                @elseif($pergunta->respostas->first()->paragrafo->count() )
                                    {{-- {{dd($pergunta->respostas->first())}} --}}
                                    <p>Resposta com parágrafo: </p>
                                    <table class="table table-striped table-dark">
                                        <thead>
                                            <tr>
                                                <th scope="col">Revisor</th>
                                                <th scope="col">Trabalho</th>
                                                <th scope="col">Resposta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pergunta->respostas as $resposta)
                                                @if ($resposta->revisor != null || $resposta->trabalho != null)
                                                    <tr>
                                                        <td>{{ $resposta->revisor->user->name }}</td>
                                                        <td>{{ $resposta->trabalho->titulo }} </td>
                                                        <td>{{ $resposta->paragrafo->resposta }}</td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <p>Sem respostas</p>
                                            @endforelse
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </p>
            </div>
        </div>
    @endforeach
</body>

</html>
