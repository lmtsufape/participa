<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        html, body, * {
            font-family: 'Inter', sans-serif !important;
        }
    </style>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">

    {{--  <link href="{{ asset('css/style.css') }}" rel="stylesheet"> --}}

    <link href="{{ asset('css/styleIndex.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">
    <link href='{{ asset('fullcalendar-5.3.2/lib/main.css') }}' rel='stylesheet' />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" > -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <!-- <link href="{{ asset('css/styleIndex.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet"> -->
    <style>
        /* Realça todas as inputs do sistema */
        .form-control {
            border: 1px solid rgba(108, 117, 125, 0.47) !important;   /* tom de cinza mais escuro */
        }
        .form-control:focus {
            border-color:rgb(3, 70, 82) !important;       /* padrão azul Bootstrap */
            box-shadow: 0 0 0 .2rem rgba(3, 70, 82, 0.25) !important;
        }
    </style>



    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


    <!--CSS DINAMICO-->
    @yield('css')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

</head>

<body class="d-flex flex-column min-vh-100" style="background-color: #f5f5f5;">
    @include('navbar')

    @if(View::hasSection('sidebar'))
        {{-- <div class="ml-auto mensagem"> apagar o css depois
        </div> --}}

        <main class="flex-grow-1">
            <div class="row">
                <div class="col-md-3">
                    @include('components.sidebar', ['evento' => $evento])

                </div>
                <div class="col-md-9 my-5">
                    @include('componentes.mensagens')

                    @yield('content')

                </div>
            </div>
        </main>

    @else
        @include('componentes.mensagens')

        <main class="flex-grow-1 @yield('main-classes', 'py-5') bg-my-shadow">

            @yield('content')
        </main>

    @endif

    @include('componentes.footer')

    <!-- Scripts -->

    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&callback=initMap"></script>

    <script src="{{ asset('js/jquery-mask-plugin.js')}}"></script>
    <script defer src="{{ asset('js/alpine.js') }}"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <script src="{{ asset('js/submit.js') }}"></script>
    <!-- CKEditor -->
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <!-- FullCalendar -->
    <script src='{{ asset('fullcalendar-5.3.2/lib/main.js') }}'></script>
    <script src='{{ asset('fullcalendar-5.3.2/lib/locales-all.js') }}'></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 1,
                },
                992: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 3,
                }
            }
        });
    </script>

    @stack('scripts')
    @hasSection('javascript')
        @yield('javascript')
    @endif
    @yield('javascript')

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}
        &callback=initMap"
        async
        defer>
    </script>
</body>

</html>
