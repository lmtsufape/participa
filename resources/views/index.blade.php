@extends('layouts.app')

@section('content')

    <div id="showcase" style="margin-top:-1rem;">
        <div style="margin-top: 5rem; background-image: url('./img/fundo-vagalumes.png');
  background-repeat: no-repeat; background-position-x: center;  background-size: 100% 650px;">
            <div class="container-xl">
                <div class="row justify-content-center">
                    <!-- TITULO: DESTAQUE -->
                    <div class="col-md-12" style="font-size: 20px; margin-top:20px; margin-bottom:20px; text-align:center; color:white"></div>
                    <!-- SLIDESHOW -->
                    <div id="carouselExampleIndicators" class="col-md-11 carousel slide" data-ride="carousel" style="padding-top: 4rem;">
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <ol class="carousel-indicators" style="display: none;">
                                    @if (count($proximosEventos) > 0)
                                        @foreach ($proximosEventos as $i => $evento)
                                            @if ($i == 0)
                                                <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="active" style="background-color:#ccbcac"></li>
                                            @else
                                                <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" style="background-color:#ccbcac"></li>
                                            @endif
                                        @endforeach
                                    @else
                                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active" style="background-color:#ccbcac"></li>
                                    @endif
                                </ol>
                                <div class="carousel-inner" style="box-shadow: -1px 0 11px 2px rgba(0,0,0,0.39);
          -webkit-box-shadow: -1px 0 11px 2px rgba(0,0,0,0.39);
          -moz-box-shadow: -1px 0 11px 2px rgba(0,0,0,0.39);">

                                    @forelse ($proximosEventos as $i => $evento)
                                        <div class="carousel-item @if ($i == 0)active @endif" style="background-color:white; height: 400px;">
                                            <div class="row">
                                                <div class="col-lg-6 evento-image sizeImg">
                                                    @if ($evento->fotoEvento != null)
                                                        <img class="img-carousel" src="{{ asset('storage/'.$evento->fotoEvento) }}" alt="..." style="
                        width: auto;
                        height: auto;background: no-repeat center;
                        background-size: cover;">
                                                    @else
                                                        <img class="img-carousel" src="{{ asset('img/colorscheme.png') }}" alt="..." style="max-width:300px;
                        max-height:150px;
                        width: auto;
                        height: auto;background: no-repeat center;background-size: cover;">
                                                    @endif
                                                </div>
                                                <div class="col-lg-1 linha-divisora">
                                                </div>
                                                <div class="col-lg-5">
                                                    <div class="row container" style="margin-left: 0; margin-top:15px;">
                                                        <div class="col-md-12" style="text-align:center; margin-top:20px;margin-bottom:10px;">
                                                            <a href="{{route('evento.visualizar',['id'=>$evento->id])}}"
                                                               style="font-size:25px;line-height: 1.2; color:#12583C; font-weight:600">{{mb_strimwidth($evento->nome, 0, 54, "...")}}</a>
                                                        </div>
                                                        <div class="col-md-12" style="text-align: justify;line-height: 1.3;color:#12583C; margin-bottom:15px; height: 300px;">
                                                            <div>
                                                                @if (strlen($evento->descricao) > 621)
                                                                    {{ mb_strimwidth(strip_tags(html_entity_decode($evento->descricao, ENT_QUOTES)), 0, 621, "...") }}
                                                                    <br>
                                                                    <a href="#" onclick="event.preventDefault();" data-toggle="modal" data-target="#lerMais{{$evento->id}}">Saiba mais</a>
                                                                @else
                                                                    {!! $evento->descricao !!}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="carousel-item active " style="background-color:white; height: 400px;">
                                            <div class="row">
                                                <div class="col-lg-6 evento-image sizeImg">
                                                    <img class="img-carousel" src="{{ asset('img/colorscheme.png') }}" alt="..." style="max-width:300px;
                        max-height:150px;
                        width: auto;
                        height: auto;background: no-repeat center;background-size: cover;">
                                                </div>
                                                <div class="col-lg-1 linha-divisora">
                                                </div>
                                                <div class="col-lg-5">
                                                    <div class="row container" style="margin-left: 0; margin-top:15px;">
                                                        <div class="col-md-12" style="text-align:center; margin-top:20px;margin-bottom:10px;">
                                                            <b>Nenhum Evento Cadastrado.</b>
                                                        </div>
                                                        <div class="col-md-12" style="text-align: center;line-height: 1.3;color:#12583C; margin-bottom:15px; height: 300px;">
                                                            <div>
                                                                Ainda não possuimos eventos cadastrados.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                @if(count($proximosEventos) > 1)
                                    <a class="carousel-control-prev w-auto mr-2" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                        <img src="{{asset('/img/icons/previous.svg')}}" alt="anterior">
                                    </a>
                                    <a class="carousel-control-next w-auto" href="#carouselExampleIndicators" role="button" data-slide="next">
                                        <img src="{{asset('/img/icons/next.svg')}}" alt="próximo">
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- TITULO: PROXIMOS EVENTOS -->
                    @if (count($proximosEventos) > 0)
                        <div class="container col-md-9 my-3">
                            <div class="col-md-10" style="font-size: 32px; margin-top: 10rem; margin-bottom:20px; color: #154D59; font-weight: 600; font-family: Arial, Helvetica, sans-serif;">Próximos
                                Eventos
                            </div>
                            <div class="row text-center">
                                <div id="recipeCarousel" class="carousel carousel-evento slide w-100" @if(count($proximosEventos) > 3) data-ride="carousel" @endif>
                                    <div class="carousel-inner w-100" role="listbox" style="height: 400px">
                                        @foreach($proximosEventos as $i => $evento)
                                            <div class="carousel-item @if($i==0) active @endif">
                                                <div class="col-md-4">
                                                    <div class="card h-100 shadow" style="width: 16rem; margin:8px; border: 0 solid #1492E6; border-radius: 20px;">
                                                        <div style="width: 100%; text-align: center; padding-top: 10px;">
                                                            @if ($evento->icone != null)
                                                                <img class="card-img-top img-flex" src="{{ asset('storage/'.$evento->icone) }}" alt="Card image cap" style="height: 200px;
                              width: 80%; border:2px solid rgb(175, 175, 175); border-radius: 50%;">
                                                            @elseif ($evento->fotoEvento != null)
                                                                <img class="card-img-top img-flex" src="{{ asset('storage/'.$evento->fotoEvento) }}" alt="Card image cap" style="height: 200px;
                            width: 80%; border:2px solid rgb(175, 175, 175); border-radius: 50%;">
                                                            @else
                                                                <img class="card-img-top img-flex" src="{{ asset('img/colorscheme.png') }}" alt="Card image cap" style="height: 200px;
                              width: 80%; border:2px solid rgb(175, 175, 175); border-radius: 50%;">
                                                            @endif
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12" style="height:80px">
                                                                    <a href="{{route('evento.visualizar',['id'=>$evento->id])}}" style="color: black;">
                                                                        <h5 class="card-title">{{mb_strimwidth($evento->nome, 0, 54, "...")}}</h5>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($proximosEventos) > 3)
                                        <a class="carousel-control-prev w-auto" href="#recipeCarousel" role="button" data-slide="prev">
                                            <img src="{{asset('/img/icons/previous.svg')}}" alt="anterior">
                                        </a>
                                        <a class="carousel-control-next w-auto" href="#recipeCarousel" role="button" data-slide="next">
                                            <img src="{{asset('/img/icons/next.svg')}}" alt="próximo">
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (count($eventosPassados) > 0)
                        <div class="container col-md-9 my-3">
                            <div class="col-md-10" style="font-size: 32px; margin-bottom:20px; color: #154D59; font-weight: 600; font-family: Arial, Helvetica, sans-serif;">Eventos Passados</div>
                            <div class="row text-center">
                                <div id="recipeCarouselPassados" class="carousel carousel-evento slide w-100" @if(count($eventosPassados) > 2) data-ride="carousel" @endif>
                                    <div class="carousel-inner w-100" role="listbox" style="height: 400px">
                                        @foreach($eventosPassados as $i => $evento)
                                            <div class="carousel-item @if($i==0) active @endif">
                                                <div class="col-md-4">
                                                    <div class="card h-100 shadow" style="width: 16rem; margin:8px; border: 0 solid #1492E6; border-radius: 20px;">
                                                        <div style="width: 100%; text-align: center; padding-top: 10px;">
                                                            @if ($evento->icone != null)
                                                                <img class="card-img-top img-flex" src="{{ asset('storage/'.$evento->icone) }}" alt="Card image cap" style="height: 200px;
                              width: 80%; border:2px solid rgb(175, 175, 175); border-radius: 50%;">
                                                            @elseif ($evento->fotoEvento != null)
                                                                <img class="card-img-top img-flex" src="{{ asset('storage/'.$evento->fotoEvento) }}" alt="Card image cap" style="height: 200px;
                            width: 80%; border:2px solid rgb(175, 175, 175); border-radius: 50%;">
                                                            @else
                                                                <img class="card-img-top img-flex" src="{{ asset('img/colorscheme.png') }}" alt="Card image cap" style="height: 200px;
                              width: 80%; border:2px solid rgb(175, 175, 175); border-radius: 50%;">
                                                            @endif
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12" style="height:80px">
                                                                    <a href="{{route('evento.visualizar',['id'=>$evento->id])}}" style="color: black;">
                                                                        <h5 class="card-title">{{mb_strimwidth($evento->nome, 0, 54, "...")}}</h5>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="carousel-item">
                                            <div class="col-md-4">
                                                <a class="card btn btn-primary btn-padding border mb-2"
                                                   style="width: 16rem; margin:8px; border-radius: 20px; text-decoration: none; background-color: #E5B300; height: 330px; "
                                                   title="Clique aqui para ver todos os eventos" onclick="window.location='{{route('busca.eventos')}}'">
                                                    <div style="width: 100%; text-align: center; padding-top: 50px;">
                                                        <img id="icone-add-coautor" class="mt-2" src="{{ asset('img/icons/mais.svg') }}" alt="ícone de todos os eventos" width="100px">
                                                    </div>
                                                    <div style=" font-weight: 600; font-size: 24px;">
                                                        Todos os<br>
                                                        Eventos
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @if(count($eventosPassados) > 2)
                                        <a class="carousel-control-prev w-auto" href="#recipeCarouselPassados" role="button" data-slide="prev">
                                            <img src="{{asset('/img/icons/previous.svg')}}" alt="anterior">
                                        </a>
                                        <a class="carousel-control-next w-auto" href="#recipeCarouselPassados" role="button" data-slide="next">
                                            <img src="{{asset('/img/icons/next.svg')}}" alt="próximo">
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- MAIS EVENTOS-->
                </div>
            </div>

        </div>


        @if(count($proximosEventos)>0)
            @foreach ($proximosEventos as $i => $evento)
                <div class="modal fade" id="lerMais{{$evento->id}}" tabindex="-1" role="dialog" aria-labelledby="labelLerMais" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #114048ff; color: white;">
                                <h5 class="modal-title" id="labelLerMais">{{$evento->nome}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-text">
                                    <div class="container">
                                        {!! $evento->descricao !!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function () {
            $('#carouselExampleIndicators').carousel({
                interval: 10000
            });


            // $('#modalInfo').modal('show');

            var botoes = document.getElementsByClassName('cor-aleatoria');
            for (var i = 0; i < botoes.length; i++) {
                botoes[i].style.backgroundColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
            }

            var barras = document.getElementsByClassName('wrapper-barra-horizontal');
            for (var i = 0; i < barras.length; i++) {
                if (window.innerWidth < barras[i].scrollWidth) {
                    barras[i].parentElement.parentElement.children[2].style.display = "block"
                } else {
                    barras[i].parentElement.parentElement.children[2].style.display = "none"
                }
            }

            $('.scroll-horizontal-next-icon').click(function () {
                var move = this.parentElement.parentElement.children[1].children[0].scrollLeft += 200;
                $(this).closest('.barra-horizontal')
                    .children('.conteudo-inferior')
                    .children('.wrapper-barra-horizontal').animate({scrollLeft: move}, 500);
            })

            $('.scroll-horizontal-prev-icon').click(function () {
                var move = this.parentElement.parentElement.children[1].children[0].scrollLeft -= 200;
                $(this).closest('.barra-horizontal')
                    .children('.conteudo-inferior')
                    .children('.wrapper-barra-horizontal').animate({scrollLeft: move}, 500);
            })


            if ({!! json_encode($proximosEventos->count(), JSON_HEX_TAG) !!} > 3) {
                $('#recipeCarousel').carousel({
                    interval: 10000
                })
            } else {
                $('#recipeCarousel').carousel({
                    interval: false
                })
            }

            if ({!! json_encode($eventosPassados->count(), JSON_HEX_TAG) !!} > 3) {
                $('#recipeCarouselPassados').carousel({
                    interval: 10000
                })
            } else {
                $('#recipeCarouselPassados').carousel({
                    interval: false
                })
            }

            $('#recipeCarousel .carousel-item').each(function () {
                var minPerSlide = 3;
                var next = $(this).next();
                if (!next.length) {
                    next = $(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo($(this));

                if ({!! json_encode($proximosEventos->count(), JSON_HEX_TAG) !!} > 2) {
                    for (var i = 0; i < minPerSlide; i++) {
                        next = next.next();
                        if (!next.length) {
                            next = $(this).siblings(':first');
                        }

                        next.children(':first-child').clone().appendTo($(this));
                    }
                }

            });

            $('#recipeCarouselPassados .carousel-item').each(function () {
                var minPerSlide = 3;
                var next = $(this).next();
                if (!next.length) {
                    next = $(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo($(this));

                if ({!! json_encode($eventosPassados->count(), JSON_HEX_TAG) !!} >= 2) {
                    for (var i = 0; i < minPerSlide; i++) {
                        next = next.next();
                        if (!next.length) {
                            next = $(this).siblings(':first');
                        }

                        next.children(':first-child').clone().appendTo($(this));
                    }
                }

            });
        });

    </script>
@endsection
