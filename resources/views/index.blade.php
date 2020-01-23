@extends('layouts.app')

@section('content')

<div class="content">
    <div id="rowCarrousel"class="row justify-content-center">
        <div class="col-sm-12" style="background-color:blueviolet">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                  <div class="carousel-item active">
                      <img class="d-block w-100" src="{{asset('img/colorscheme.png')}}" alt="First slide">
                      <div class="carousel-caption d-none d-md-block">
                        {{-- Jumbotron Explicando resumidamente o Módulo --}}
                        <div class="jumbotron" style="background:none">
                            <h1 class="display-4">Primeiro Módulo</h1>
                            <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                            <p class="lead">
                              <a class="btn btn-outline-light btn-lg" href="#" role="button">Saber mais</a>
                            </p>
                        </div>{{-- End Jumbotron--}}
                      </div>
                  </div>
                  <div class="carousel-item">
                    <img class="d-block w-100" src="{{asset('img/colorscheme.png')}}" alt="Second slide">
                    <div class="carousel-caption d-none d-md-block">
                        {{-- Jumbotron Explicando resumidamente o Módulo --}}
                        <div class="jumbotron" style="background:none">
                            <h1 class="display-4">Segundo Módulo</h1>
                            <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                            <p class="lead">
                              <a class="btn btn-outline-light btn-lg" href="#" role="button">Saber mais</a>
                            </p>
                        </div>{{-- End Jumbotron--}}
                      </div>
                  </div>
                  <div class="carousel-item">
                    <img class="d-block w-100" src="{{asset('img/colorscheme.png')}}" alt="Third slide">
                    <div class="carousel-caption d-none d-md-block">
                        {{-- Jumbotron Explicando resumidamente o Módulo --}}
                        <div class="jumbotron" style="background:none">
                            <h1 class="display-4">Terceiro Módulo</h1>
                            <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                            <p class="lead">
                              <a class="btn btn-outline-light btn-lg" href="#" role="button">Saber mais</a>
                            </p>
                        </div>{{-- End Jumbotron--}}
                      </div>
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-12" style="height:80vh;background-color:yellow">

        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-12" style="height:80vh;background-color:green">

        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-12" style="height:80vh;background-color:brown">

        </div>
    </div>
        
</div>

@endsection