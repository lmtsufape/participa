<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Error page</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js')}}"></script>
        <script src="{{ asset('js/jquery-mask-plugin.js')}}"></script>
        <script src="{{ asset('js/alpine.js')}}"></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </head>
    <body style="">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 conteudoCentralizado font-25">
                    {{ config('app.name') }}
                </div>
            </div>
            <div class="row" style="position: relative; top:10px;">
                <div class="col-sm-12 conteudoCentralizado font-25">
                    <div class="divError">
                        @yield('content')
                    </div>
                </div>
                <div class="col-sm-12 conteudoCentralizado links" style="top:170px">
                    <a href="#" onclick="window.history.back()">Voltar</a>
                </div>
            </div>
        </div>
    </body>
</html>
