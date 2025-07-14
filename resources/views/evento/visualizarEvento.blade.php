@extends('layouts.app')
@section('content')
    @if(session('message'))
        <div class="alert alert-{{ session('class', 'info') }} alert-dismissible fade show text-center" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="container">
        @php
            $bannerPath =
                $evento->is_multilingual && Session::get('idiomaAtual') === 'en' && $evento->fotoEvento_en
                    ? $evento->fotoEvento_en
                    : $evento->icone;
        @endphp
        <div class="row my-5">
            <div class="col-md-7">
                @if (isset($evento->icone))
                    <img src="{{ asset('storage/' . $bannerPath) }}" class="rounded" width="98%" alt="">
                @else
                    <img src="{{ asset('img/colorscheme.png') }}" class="rounded" width="98%" alt="">
                @endif
            </div>
            <div class="col-md-5">
                @if ($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                    <h2 class="text-my-primary">{{ $evento->nome_en }}</h2>
                @elseif ($evento->is_multilingual && Session::get('idiomaAtual') === 'es')
                    <h2 class="text-my-primary">{{ $evento->nome_es }}</h2>
                @else
                    <h2 class="text-my-primary">{{ $evento->nome }}</h2>
                @endif
                <span class="d-flex align-items-center gap-1 text-my-secondary mb-4 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-calendar-event" viewBox="0 0 16 16">
                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                    </svg>

                    @if ($dataInicio->isSameMonth($dataFim))
                        @if(Session::get('idiomaAtual') === 'en')
                            From
                            {{ $dataInicio->translatedFormat('d F') }}
                            to
                            {{ $dataFim->translatedFormat('d  F  Y') }}
                        @elseif(Session::get('idiomaAtual') === 'es')
                            Desde
                            {{ $dataInicio->translatedFormat('d \d\e F') }}
                            hasta
                            {{ $dataFim->translatedFormat('d \d\e F \d\e Y') }}
                        @else
                            De
                            {{ $dataInicio->translatedFormat('d') }}
                            a
                            {{ $dataFim->translatedFormat('d \d\e F \d\e Y') }}
                        @endif
                    @else
                        @if(Session::get('idiomaAtual') === 'en')
                            From
                            {{ $dataInicio->translatedFormat('d F') }}
                            to
                            {{ $dataFim->translatedFormat('d F Y') }}
                        @elseif(Session::get('idiomaAtual') === 'es')
                            Desde
                            {{ $dataInicio->translatedFormat('d \d\e F') }}
                            hasta
                            {{ $dataFim->translatedFormat('d \d\e F \d\e Y') }}
                        @else
                            De
                            {{ $dataInicio->translatedFormat('d \d\e F') }}
                            a
                            {{ $dataFim->translatedFormat('d \d\e F \d\e Y') }}
                        @endif
                    @endif

                </span>
                <span class="text-my-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                    </svg> {{$evento->endereco->rua}}, {{$evento->endereco->numero}}, {{$evento->endereco->cidade}}-{{$evento->endereco->uf}}
                </span>
                <div class="text-secondary py-3">
                    <p class="m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                        {{ __('Organizado por:') }}
                    </p>
                    <p class="m-0" style="padding-left: 1.3rem !important;">
                        {{$evento->coordenador->name}}
                    </p>
                </div>

                @if ($etiquetas->modinscricao)
                    <div class="d-flex flex-wrap gap-2">
                        <button id="btn-inscrevase" class="btn btn-my-success w-60 rounded btn-lg" data-bs-toggle="modal" data-bs-target="#modalInscrever"
                            @if (($isInscrito && !$InscritoSemCategoria)  || $encerrada) disabled @endif>
                            @if ($isInscrito)
                                @if($inscricao->finalizada)
                                    {{ __('Já inscrito') }}
                                @elseif($InscritoSemCategoria)
                                    {{ __('Realize sua inscrição aqui!') }}
                                @else
                                    {{ __('Inscrição com pendência de pagamento!') }}
                                @endif
                            @elseif($encerrada)
                                {{ __('Encerradas!') }}
                            @else
                                {{ __('Realize sua inscrição aqui!') }}
                            @endif
                        </button>
                    </div>
                    <br>

                    @if(isset($inscricao) && $inscricao)
                        @if(!$inscricao->finalizada)
                            <span class="text mt-2" style="font-style: semi-bold;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16" style="color: red">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                </svg>

                                {{__('Inscrição sujeita à confirmação do pagamento.')}}</span>
                        @endif
                    @else
                        <span class="text mt-2" style="font-style: semi-bold;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16" style="color: red">
                        <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                        </svg>

                        {{__('Inscrição sujeita à confirmação do pagamento.')}}</span>
                    @endif

                    <br>

                    @isset($inscricao)
                        @if(isset($inscricao->pagamento))
                            <a href="{{ route('checkout.statusPagamento', $evento->id) }}"
                                class="text-center mt-2 w-100">{{ __('Visualizar status do pagamento') }}</a>
                        @elseif(!$InscritoSemCategoria)
                            <a href="{{ route('checkout.telaPagamento', $evento) }}"
                                    class="text-center mt-2 w-100">{{ __('Realizar pagamento') }}</a>

                            <a href="#" class="ms-3" data-bs-toggle="modal" data-bs-target="#modalAlterarCategoria">
                                {{ __('Alterar categoria') }}
                            </a>
                        @endif
                    @endisset
                @endif

            </div>
        </div>
        <div class="row">
            <h4 class="text-my-primary">{{ __('Sobre o evento') }}</h4>
            <div class="col-md-12 overflow-auto text-break" style="word-wrap: break-word; white-space: normal;">
                <div style="text-align: justify;">
                    @if ($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                        {!! $evento->descricao_en !!}
                    @elseif ($evento->is_multilingual && Session::get('idiomaAtual') === 'es')
                        {!! $evento->descricao_es !!}
                    @else
                        {!! $evento->descricao !!}
                    @endif
                </div>
            </div>


        </div>
        <hr class="border-dark">

        <div class="row py-4">
            <h4 class="text-my-primary">{{ __('Ver sobre o evento') }}</h4>
            <div class="d-flex flex-wrap flex-md-nowrap gap-2">
                @php
                    $modalidadesAtivas = collect($modalidades)->filter(function($m) use($mytime) {
                        return \Carbon\Carbon::parse($m->ultima_data) >= $mytime;
                    });

                    // flags de exibição
                    $temSubmissao = $etiquetas->modsubmissao && $modalidadesAtivas->isNotEmpty();
                    $temAreas     = isset($areas) && count($areas) > 0;
                @endphp
                @if($temSubmissao)
                    <a href="#submissao_trabalho"
                    class="btn card d-flex justify-content-center align-items-center text-my-primary shadow p-3"
                    style="width: 180px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                            class="bi bi-list" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                        </svg>
                        <h6 class="mt-2">{{ __('Submissão de trabalhos') }}</h6>
                    </a>
                @endif
                @php
                    $temPdfProg = $evento->exibir_pdf
                    && $etiquetas->modprogramacao
                    && $evento->pdf_programacao;
                    $temPdfArquivo = $evento->pdf_arquivo
                    && $evento->modarquivo;
                    $temInfosExtras = $evento->arquivoInfos->isNotEmpty();
                @endphp

                @if($temPdfProg || $temPdfArquivo || $temInfosExtras)
                    <a href="#info_adicionais" class="btn card d-flex justify-content-center align-items-center text-my-primary shadow p-3" style="width: 180px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                            class="bi bi-paperclip" viewBox="0 0 16 16" style="transform: rotate(45deg)">
                            <path
                                d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z" />
                        </svg>
                        <h6 class="mt-2">{{ __('Informações adicionais') }}</h6>
                    </a>
                @endif
                @if($evento->memorias->isNotEmpty())
                    <a href="#memorias" class="btn card d-flex justify-content-center align-items-center text-my-primary shadow p-3" style="width: 180px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                            class="bi bi-bookmark" viewBox="0 0 16 16">
                            <path
                                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
                        </svg>
                        <h6 class="mt-2">{{ __('Memórias') }}</h6>
                    </a>
                @endif
                <a href="#programacao" class="btn card d-flex justify-content-center align-items-center text-my-primary shadow p-3" style="width: 180px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                        class="bi bi-calendar-event" viewBox="0 0 16 16">
                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                    </svg>
                    <h6 class="mt-2">{{ __('Programação') }}</h6>
                </a>
                @if($evento->palestrantes->isNotEmpty())
                    <a href="#palestrantes" class="btn card d-flex justify-content-center align-items-center text-my-primary shadow p-3" style="width: 180px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                            class="bi bi-people" viewBox="0 0 16 16">
                            <path
                                d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                        </svg>
                        <h6 class="mt-2">{{ __('Nossos palestrantes') }}</h6>
                    </a>
                @endif
                <a href="#contato" class="btn card d-flex justify-content-center align-items-center text-my-primary shadow p-3" style="width: 180px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                        class="bi bi-envelope" viewBox="0 0 16 16">
                        <path
                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                    </svg>
                    <h6 class="mt-2">{{ __('Contato da organização') }}</h6>
                </a>
                 <a href="#local" class="btn card d-flex justify-content-center align-items-center text-my-primary shadow p-3" style="width: 180px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                        class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                    </svg>
                    <h6 class="mt-2">{{ __('Local') }}</h6>
                </a>
            </div>
        </div>
        <hr class="border-dark">


        @if($temSubmissao || $temAreas)
            <div id="submissao_trabalho" class="row py-4">
                <h4 class="text-my-primary">{{ __('Submeta seu Trabalho') }}</h4>

                {{-- coluna de Modalidades --}}
                @if($temSubmissao)
                    <div class="col-md-6">
                        <div class="card rounded">
                            <div class="card-heading bg-my-primary rounded pt-3 pb-1 ps-3">
                                <h5 class="text-white">{{ __('Escolha a modalidade e envie o seu trabalho') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="accordion_modalidades" style="font-size: 10px;">
                                    @foreach ($modalidadesAtivas as $modalidade)
                                        <div class="accordion-group">
                                            <div class="accordion-heading">
                                                <a class="accordion-button collapsed bg-transparent text-dark"
                                                data-bs-toggle="collapse"
                                                data-bs-parent="#accordion_modalidades"
                                                href="#collapse_{{ $modalidade->id }}">
                                                    <strong>
                                                        @if (isset($evento) && $evento->is_multilingual && Session::get('idiomaAtual') === 'en' && !empty($modalidade->nome_en))
                                                            {{ $modalidade->nome_en }}
                                                        @elseif (isset($evento) && $evento->is_multilingual && Session::get('idiomaAtual') === 'es' && !empty($modalidade->nome_es))
                                                            {{ $modalidade->nome_es }}
                                                        @else
                                                            {{ $modalidade->nome }}
                                                        @endif
                                                    </strong>
                                                </a>
                                            </div>
                                            <div id="collapse_{{ $modalidade->id }}"
                                                class="accordion-body collapse"
                                                style="height: auto;">
                                                <ul class="list-unstyled fs-6">
                                                    <li>
                                                        <img src="{{ asset('img/icons/calendar-pink.png') }}" alt="" style="width:20px;">
                                                        <strong>{{ __('Envio') }}:</strong>
                                                        {{ date('d/m/Y H:i', strtotime($modalidade->inicioSubmissao)) }}
                                                        –
                                                        {{ date('d/m/Y H:i', strtotime($modalidade->fimSubmissao)) }}
                                                    </li>
                                                    <li>
                                                        <img src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;">
                                                        <strong>{{ __('Avaliação') }}:</strong>
                                                        {{ date('d/m/Y H:i', strtotime($modalidade->inicioRevisao)) }}
                                                        –
                                                        {{ date('d/m/Y H:i', strtotime($modalidade->fimRevisao)) }}
                                                    </li>
                                                    @if($modalidade->inicioCorrecao && $modalidade->fimCorrecao)
                                                        <li>
                                                            <img src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;">
                                                            <strong>{{ __('Correção') }}:</strong>
                                                            {{ date('d/m/Y H:i', strtotime($modalidade->inicioCorrecao)) }}
                                                            –
                                                            {{ date('d/m/Y H:i', strtotime($modalidade->fimCorrecao)) }}
                                                        </li>
                                                    @endif
                                                    @if($modalidade->inicioValidacao && $modalidade->fimValidacao)
                                                        <li>
                                                            <img src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;">
                                                            <strong>{{ __('Validação') }}:</strong>
                                                            {{ date('d/m/Y H:i', strtotime($modalidade->inicioValidacao)) }}
                                                            –
                                                            {{ date('d/m/Y H:i', strtotime($modalidade->fimValidacao)) }}
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <img src="{{ asset('img/icons/calendar-green.png') }}" alt="" style="width:20px;">
                                                        <strong>{{ __('Resultado') }}:</strong>
                                                        {{ date('d/m/Y H:i', strtotime($modalidade->inicioResultado)) }}
                                                    </li>
                                                    @foreach($modalidade->datasExtras as $data)
                                                        <li>
                                                            <img src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;">
                                                            {{ $data->nome }}:
                                                            {{ date('d/m/Y H:i', strtotime($data->inicio)) }}
                                                            –
                                                            {{ date('d/m/Y H:i', strtotime($data->fim)) }}
                                                        </li>
                                                    @endforeach

                                                </ul>

                                                {{-- links de download e botão de submissão --}}
                                                <div class="row">
                                                    <div class="col-12">
                                                        @if($modalidade->arquivo && isset($modalidade->regra))
                                                            <div class="mb-2">
                                                                <a href="{{ route('modalidade.regras.download', $modalidade->id) }}"
                                                                target="_blank"
                                                                class="d-inline-block text-decoration-none">
                                                                    <img src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">
                                                                    <span class="text-decoration-underline fs-6">
                                                                        {{ $evento->formEvento->etiquetabaixarregra }}
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if(isset($modalidade->modelo_apresentacao))
                                                            <div class="mb-2">
                                                                <a href="{{ route('modalidade.modelos.download', $modalidade->id) }}"
                                                                target="_blank"
                                                                class="d-inline-block">
                                                                    <img src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">
                                                                    <span class="text-decoration-underline fs-6">
                                                                        {{ $evento->formEvento->etiquetabaixarapresentacao }}
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if(isset($modalidade->template))
                                                            <div class="mb-2">
                                                                <a href="{{ route('modalidade.template.download', $modalidade->id) }}"
                                                                target="_blank"
                                                                class="d-inline-block text-decoration-none">
                                                                    <img src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">
                                                                    <span class="text-decoration-underline fs-6">
                                                                        {{ $evento->formEvento->etiquetabaixartemplate }}
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        @endif

                                                            {{-- Caso 1: O usuario n está logado -> O botão de submeter redireciona para a pagina de login --}}
                                                            @if (!Auth::user())
                                                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalLoginPrompt" data-acao="{{ __('submeter um trabalho') }}"
                                                            class="btn btn-my-success w-100 mt-3">
                                                            {{ __('SUBMETER TRABALHO') }}
                                                            </button>
                                                        @endif
                                                        @auth
                                                            @php
                                                                $pode = $modalidade->estaEmPeriodoDeSubmissao();
                                                            @endphp
                                                            {{-- Caso 2: O usuario está logado e pode submeter o trabalho --}}
                                                            @if($pode)
                                                                <a href="{{ route('trabalho.index', ['id'=>$evento->id,'idModalidade'=>$modalidade->id]) }}"
                                                                class="btn btn-my-success w-100 mt-3">
                                                                    {{ __('SUBMETER TRABALHO') }}
                                                                </a>
                                                            @endif
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($etiquetas->modavaliacao)
                                            @if(Auth::user())
                                                @if (!$jaCandidatou)
                                                    <button class="btn btn-my-success w-50 rounded btn-lg mt-3"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalInscreverAvaliador">
                                                        {{ __('Quero ser avaliador') }}
                                                    </button>
                                                @else
                                                    <button class="btn btn-my-success w-100 rounded btn-lg mt-3" disabled>
                                                        {{ __('Você já se candidatou a avaliador neste evento') }}
                                                    </button>
                                                @endif
                                            @else
                                                <button class="btn btn-my-success w-50 rounded btn-lg mt-3"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalLoginPrompt"
                                                        data-acao="{{ __('se candidatar a avaliador') }}">
                                                    {{ __('Quero ser avaliador') }}
                                                </button>
                                            @endif
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- coluna de Áreas Temáticas --}}
                @if($temAreas)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-heading bg-my-primary rounded pt-3 pb-1 ps-3">
                                <h5 class="text-white">{{ __('Eixos temáticos') }}</h5>
                            </div>
                            <div class="card-body">
                                <ul>
                                    @foreach($areas->take(5) as $area)
                                        @php
                                        if ($evento->is_multilingual && $area->nome_en && Session::get('idiomaAtual') === 'en') {
                                            $nomeArea = $area->nome_en;
                                        } elseif ($evento->is_multilingual &&  $area->nome_es && Session::get('idiomaAtual') === 'es') {
                                            $nomeArea = $area->nome_es;
                                        } else {
                                            $nomeArea = $area->nome;
                                        }

                                        if (preg_match('/^(Eixo\s*\d+:\s*)/i', $nomeArea, $matches)
                                            || preg_match('/^(Axis\s*\d+:\s*)/i', $nomeArea, $matches)
                                            || preg_match('/^(Eje\s*\d+:\s*)/i', $nomeArea, $matches)) {
                                            $prefixo = $matches[1];
                                            $resto   = mb_substr($nomeArea, mb_strlen($prefixo));
                                        } else {
                                            $prefixo = '';
                                            $resto   = $nomeArea;
                                        }
                                    @endphp

                                    <li>
                                        @if($prefixo)
                                            <strong>{{ $prefixo }}</strong>{{ $resto }}
                                        @else
                                            {{ $resto }}
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                                @if($areas->count() > 5)
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-my-outline-primary rounded-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalTodasAreas">
                                            {{ __('Ver todos') }}
                                        </button>
                                    </div>
                                    @include('evento.modal-areas')
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <hr class="border-dark my-4">
        @endif
            <div class="modal fade" id="modalLoginPrompt" tabindex="-1" aria-labelledby="modalLoginPromptLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h5 class="modal-title text-center" id="modalLoginPromptLabel">{{ __('Atenção') }}!</h5>
                            <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="{{ __('Fechar') }}"></button>
                        </div>
                        <div class="modal-body">
                            {{__('Você precisa entrar na sua conta para poder')}} <span id="modalActionText"></span>.
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('login') }}" class="btn btn-primary">{{ __('Fazer Login') }}</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        </div>
                    </div>
                </div>
            </div>

        @if(
            ($evento->exibir_pdf && $etiquetas->modprogramacao && $evento->pdf_programacao)
            || ($evento->pdf_arquivo && $evento->modarquivo)
            || $evento->arquivoInfos->isNotEmpty()
            || $evento->memorias->isNotEmpty()
        )
            <div id="info_adicionais" class="row py-4">

                @if(
                    ($evento->exibir_pdf && $etiquetas->modprogramacao && $evento->pdf_programacao)
                    || ($evento->pdf_arquivo && $evento->modarquivo)
                    || $evento->arquivoInfos->isNotEmpty()
                )
                    <div class="col-md-6">
                        <h4 class="text-my-primary">{{ __('Informações adicionais') }}</h4>

                        @if ($evento->exibir_pdf && $etiquetas->modprogramacao && $evento->pdf_programacao)
                            <div class="form-row mb-3 justify-content-center">
                                <div class="col-sm-3 text-center">
                                    <img class="icon-programacao"
                                        src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}"
                                        alt="PDF programação">
                                </div>
                                <div class="col-sm-8 d-flex align-items-center">
                                    <a href="{{ asset('storage/' . $evento->pdf_programacao) }}"
                                    target="_blank"
                                    class="titulo">
                                        {{ $etiquetas->etiquetamoduloprogramacao }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($evento->pdf_arquivo && $evento->modarquivo)
                            <div class="form-row mb-3 justify-content-center">
                                <div class="col-sm-3 text-center">
                                    <img class="icon-programacao"
                                        src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}"
                                        alt="PDF arquivo">
                                </div>
                                <div class="col-sm-8 d-flex align-items-center">
                                    <a href="{{ asset('storage/' . $evento->pdf_arquivo) }}"
                                    target="_blank"
                                    class="titulo">
                                        {{ $etiquetas->etiquetaarquivo }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @foreach ($evento->arquivoInfos as $arquivo)
                            <div class="form-row mb-3 justify-content-center">
                                <div class="col-sm-3 text-center">
                                    <img class="icon-programacao"
                                        src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}"
                                        alt="Info extra">
                                </div>
                                <div class="col-sm-8 d-flex align-items-center">
                                    <a href="{{ asset('storage/' . $arquivo->path) }}"
                                    target="_blank"
                                    class="titulo">
                                        {{ $arquivo->nome }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($evento->memorias->isNotEmpty())
                    <div class="col-md-6">
                        <h4 class="text-my-primary">{{ __('Memórias') }}</h4>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card sombra-card w-100">
                                    <div class="card-body">
                                        @foreach ($evento->memorias as $memoria)
                                            <div class="form-row mb-3 justify-content-center">
                                                <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                                    <img class="icon-programacao"
                                                        src="{{ asset('img/icons/' . ($memoria->arquivo ? 'Icon awesome-file-pdf.svg' : 'link-solid.svg')) }}"
                                                        alt="">
                                                </div>
                                                <div class="col-sm-8 d-flex align-items-center">
                                                    @if ($memoria->arquivo)
                                                        <a href="{{ asset('storage/' . $memoria->arquivo) }}"
                                                        target="_blank"
                                                        class="titulo">
                                                            {{ $memoria->titulo }}
                                                        </a>
                                                    @else
                                                        <a href="{{ $memoria->link }}"
                                                        target="_blank"
                                                        class="titulo">
                                                            {{ $memoria->titulo }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif

            </div>

        <hr class="border-dark">
        @endif

        <div id="programacao" class="row py-4">{{-- Programação --}}
            <h4 class="text-my-primary">{{ __('Programação') }}</h4>

            @if ($etiquetas->modprogramacao && $evento->exibir_calendario_programacao)
                @if($atividades->whereNotNull('vagas')->isEmpty())
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ __('Para participar do evento, é preciso se cadastrar na plataforma e se inscrever.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @else
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ __('Para participar das atividades do evento, é preciso primeiro se inscrever no evento e, em seguida, realizar a inscrição na atividade desejada, disponível na seção de Programação.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="row">

                    <div class="d-flex align-items-center bg-my-primary p-1 rounded">

                        {{-- Botão Anterior --}}
                        <button id="prevDates" class="btn btn-link text-white text-decoration-none fs-4 flex-shrink-0" disabled>&lt;</button>

                        {{-- Contêiner para os botões de data --}}
                        <div class="d-flex flex-grow-1 overflow-hidden">
                            @foreach($datas as $indice => $data)
                                <button class="btn btn-outline-primary text-white border-0 carregar-cards
                                    flex-shrink-0 w-25" data-data="{{ $data->data }}" id="btn-{{ $data->data }}">
                                    {{ \Carbon\Carbon::parse($data->data)->translatedFormat('D, d/m') }}
                                </button>
                            @endforeach
                        </div>


                        <button id="nextDates" class="btn btn-link text-white text-decoration-none fs-4 flex-shrink-0">&gt;</button>
                    </div>


                    <div id="cards-atividade" class="row row-cols-1 row-cols-md-3 g-4">
                        {{-- cards das atividades --}}
                    </div>
                </div>
            @else
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ __('A programação deste evento será disponibilizada em breve.') }}
                </div>
            @endif
            @if ($subeventos->count() > 0)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card sombra-card" style="width: 100%;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 style="font-weight: bold; border-bottom: solid 3px #114048ff;">
                                            {{ __('Subeventos') }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach ($subeventos as $subevento)
                                        <div
                                            class="col-sm-12 col-md-6 col-xl-4 d-flex align-items-stretch justify-content-center">
                                            <div class="card" style="width: 15rem;">
                                                @if (isset($subevento->fotoEvento))
                                                    <img class="img-card"
                                                        src="{{ asset('storage/' . $subevento->fotoEvento) }}"
                                                        class="card-img-top" alt="...">
                                                @else
                                                    <img class="img-card" src="{{ asset('img/colorscheme.png') }}"
                                                        class="card-img-top" alt="...">
                                                @endif
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h5 class="card-title">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <a href="{{ route('evento.visualizar', ['id' => $subevento->id]) }}"
                                                                            style="text-decoration: inherit;">
                                                                            @if ($subevento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                                                {{ $subevento->nome_en }}
                                                                            @elseif ($subevento->is_multilingual && Session::get('idiomaAtual') === 'es')
                                                                                {{ $subevento->nome_es }}
                                                                            @else
                                                                                {{ $subevento->nome }}
                                                                            @endif
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="card-text">
                                                            <img src="{{ asset('/img/icons/calendar.png') }}"
                                                                alt="" width="20px;"
                                                                style="position: relative; top: -2px;">
                                                            {{ date('d/m/Y', strtotime($subevento->dataInicio)) }} -
                                                            {{ date('d/m/Y', strtotime($subevento->dataFim)) }}<br>
                                                        </p>
                                                        <p>
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <img src="{{ asset('/img/icons/location_pointer.png') }}"
                                                                    alt="" width="18px" height="auto">
                                                                {{ $subevento->endereco->rua }},
                                                                {{ $subevento->endereco->numero }} -
                                                                {{ $subevento->endereco->cidade }} /
                                                                {{ $subevento->endereco->uf }}.
                                                            </div>
                                                        </div>
                                                        </p>
                                                        <div>
                                                            <div>
                                                                <a
                                                                    href="{{ route('evento.visualizar', ['id' => $subevento->id]) }}">
                                                                    <i class="far fa-eye"
                                                                        style="color: black"></i>&nbsp;&nbsp;{{ __('Visualizar evento') }}
                                                                </a>
                                                            </div>
                                                            @can('isCoordenadorOrCoordenadorDasComissoes', $subevento)
                                                                <div>
                                                                    <a
                                                                        href="{{ route('coord.detalhesEvento', ['eventoId' => $subevento->id]) }}">
                                                                        <i class="fas fa-cog"
                                                                            style="color: black"></i>&nbsp;&nbsp;{{ __('Configurar evento') }}
                                                                    </a>
                                                                </div>
                                                                @can('isCoordenador', $subevento)
                                                                    <div>
                                                                        <a href="#" data-bs-toggle="modal"
                                                                            data-bs-target="#modalExcluirEvento{{ $subevento->id }}">
                                                                            <i class="far fa-trash-alt"
                                                                                style="color: black"></i>&nbsp;&nbsp;{{ __('Deletar') }}
                                                                        </a>
                                                                    </div>
                                                                @endcan
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal de exclusão do evento -->
                                        <x-modal-excluir-evento :evento="$evento" />
                                        <!-- fim do modal -->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <hr class="border-dark">

        @if ($evento->palestrantes->isNotEmpty())

            <div id="palestrantes" class="row py-4">{{-- Nossos palestrantes --}}
                <h4 class="text-my-primary">{{ __('Nossos palestrantes') }}</h4>
                @include('evento.carrossel-palestrantes', ['id' => 'palestrantes', 'palestrantes' => $evento->palestrantes])
            </div>
            <hr class="border-dark">
        @endif

        <div id="contato" class="row py-4 d-flex">{{-- Contatos --}}
            <h4 class="text-my-primary mb-4">{{ __('Evento organizado por:') }}</h4>
            <!-- <div class="col-md-2">
                <img src="" class="" alt="">
            </div> -->
            <div class=" d-flex flex-column align-items-center">
                <h4 class="mb-3">{{ $evento->coordenador->name }}</h4>
            </div>
             <div class="col-12 d-flex justify-content-center align-items-center gap-2">
                <a href="mailto:@if($evento->email){{ $evento->email }}@else{{ $evento->coordenador->email }}@endif" class="btn btn-my-secondary rounded-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope me-1" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                    </svg>
                    {{ __('Entre em contato') }}
                </a>
                @if(!empty($evento->instagram))
                 <a href="https://instagram.com/{{$evento->instagram}}" class="btn btn-my-secondary rounded-3" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" target="_blank" class="bi bi-instagram" viewBox="0 0 16 16">
                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                        </svg> Instagram
                    </a>
                @endif
                @if (!empty($evento->contato_suporte))
                    <a href="https://chat.whatsapp.com/FerfgYKkDsLHnm23bD9gyT" target="_blank" rel="noopener" class="btn btn-my-secondary rounded-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                        </svg>
                        {{ __('Canal de suporte') }}
                    </a>
                @endif

            </div>
        </div>


            <hr class="border-dark">


            <div id="local" class="row py-4">
                <h4 class="text-my-primary">{{ __('Local') }}</h4>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3936.088668407275!2d-40.51881532409689!3d-9.413620090663885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7737101a3531eb9%3A0x36e94720fbc52c53!2sAv.%20Ant%C3%B4nio%20C.%20Magalh%C3%A3es%2C%20510%20-%20Country%20Club%2C%20Juazeiro%20-%20BA%2C%2048902-300!5e0!3m2!1spt-BR!2sbr!4v1749258428058!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            </div>
        </div>

        @include('evento.modal-inscricao')
        @include('evento.modal-alterar-categoria')
        @include('evento.modal-submeter-trabalho')
        @include('evento.modal-confirm-inscricao')
        @include('evento.modal-inscricao-avaliador')

    </div>


    @foreach ($atividades as $atv)
        @php
            $datas = $atv->datasAtividade
                          ->sortBy('data')
                          ->values();

            $primeira = $datas->first();
            $ultima   = $datas->last();
        @endphp
        <div class="modal fade bd-example modal-show-atividade" id="modalAtividadeShow{{ $atv->id }}"
            tabindex="-1" role="dialog" aria-labelledby="modalLabelAtividadeShow{{ $atv->id }}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-my-shadow">
                        <h5 class="modal-title text-my-primary" id="modalLabelAtividadeShow{{ $atv->id }}">{{ $atv->titulo }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 d-flex flex-column gap-3">
                                    <p class="text-my-secondary mb-0">
                                        <strong>{{ __('Tipo') }}: {{ $atv->tipoAtividade->descricao }}</strong>
                                    </p>
                                    @if($primeira)
                                        <div class="d-flex align-items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                 class="bi bi-calendar-event" viewBox="0 0 16 16">
                                                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                                                <path
                                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                                            </svg>
                                            <span>
                                                 {{ \Carbon\Carbon::parse($primeira->data)->translatedFormat('l, d F') }}
                                            </span>
                                        </div>

                                        @if($datas->count() > 1)
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                     class="bi bi-calendar-event" viewBox="0 0 16 16">
                                                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                                                    <path
                                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                                                </svg>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($ultima->data)->translatedFormat('l, d F') }}
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                                        </svg>
                                        <span>{{ $atv->local }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                    @if ($isInscrito && $atv->vagas != null)
                                        @if (!$atv->atividadeInscricoesEncerradas())
                                            @if (($atv->vagas > 0) && Auth::user()->atividades()->find($atv->id) == null)
                                                <form method="POST"
                                                      action="{{ route('atividades.inscricao', ['id' => $atv->id]) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-my-success">
                                                        {{ __('Inscrever-se') }}
                                                    </button>
                                                </form>
                                            @elseif(Auth::user()->atividades()->find($atv->id) != null)
                                                @if (!$atv->terminou())
                                                    <form method="POST"
                                                          action="{{ route('atividades.cancelarInscricao', ['id' => $atv->id, 'user' => Auth::id()]) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary">
                                                            {{ __('Cancelar inscrição') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-primary" disabled>
                                                        {{ __('Inscrito') }}
                                                    </button>
                                                @endif
                                            @else
                                                <button type="button" class="btn btn-danger" disabled style="pointer-events: none">
                                                    {{ __('Sem Vagas') }}
                                                </button>
                                            @endif
                                        @else
                                            @if (Auth::user()->atividades()->find($atv->id) != null)
                                                <button type="button" class="btn btn-primary" disabled>
                                                    {{ __('Inscrito') }}
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-danger" disabled>
                                                    {{ __('Inscrições encerradas') }}
                                                </button>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <hr class="border-dark">
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label for="descricao">{{ __('Descrição') }}</label>
                                    <p>
                                        {!! $atv->descricao !!}
                                    </p>
                                </div>

                            </div>


                            @if ($atv->vagas != null || $atv->valor != null)
                                <hr>
                                <div class="row">
                                    @if ($atv->vagas != null)
                                        <div class="col-sm-6">
                                            <label for="vagas">{{ __('Vagas') }}:</label>
                                            <h4 class="vagas">{{ $atv->vagas }}</h4>
                                        </div>
                                    @endif
                                    @if ($atv->valor != null)
                                        <div class="col-sm-6">
                                            <label for="valor">{{ __('Valor') }}:</label>
                                            <h4 class="valor">R$ {{ $atv->valor }}</h4>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if ($atv->carga_horaria != null)
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="carga_horaria">{{ __('Carga horária para estudantes') }}:</label>
                                        <h4 class="carga_horaria">{{ $atv->carga_horaria }}</h4>
                                    </div>
                                </div>
                            @endif
                            @if ($atv->valor != null)
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="comprovante" class="">{{ __('Comprovante de pagamento da taxa de inscrição') }}</label><br>
                                        <input type="file" id="comprovante" class="form-control-file"
                                            name="comprovante">
                                        <br>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if (count($atv->convidados) > 0)
                            <hr class="border-dark">
                            <h7>{{ __('Convidados') }}</h7>
                            <div class="convidadosDeUmaAtividade">
                                <div class="row">
                                    @foreach ($atv->convidados as $convidado)
                                        <div class="col-sm-3 imagemConvidado">
                                            <img src="{{ asset('img/icons/user.png') }}"
                                                alt="Foto de {{ $convidado->nome }}" width="50px" height="auto">
                                            <h5 class="convidadoNome">{{ $convidado->nome }}</h5>
                                            <small class="convidadoFuncao">{{ $convidado->funcao }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal" aria-label="Close">{{ __('Fechar') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const prevBtn  = document.getElementById('prevDates');
            const nextBtn  = document.getElementById('nextDates');

            const datesContainer = prevBtn.nextElementSibling;
            const dateBtns       = Array.from(datesContainer.querySelectorAll('button.carregar-cards'));

            function scrollAmount() {
                const btn = dateBtns[0];
                const style = window.getComputedStyle(btn);
                const marginRight = parseFloat(style.marginRight);
                return btn.getBoundingClientRect().width + marginRight;
            }


            function updateArrows() {
                prevBtn.disabled = datesContainer.scrollLeft <= 0;
                nextBtn.disabled = datesContainer.scrollLeft + datesContainer.clientWidth >= datesContainer.scrollWidth - 1;
            }


            prevBtn.addEventListener('click', () => {
                datesContainer.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
            });


            nextBtn.addEventListener('click', () => {
                datesContainer.scrollBy({ left:  scrollAmount(), behavior: 'smooth' });
            });


            datesContainer.addEventListener('scroll', updateArrows);


            updateArrows();


            dateBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // 1) Highlight
                    dateBtns.forEach(x => {
                        x.classList.remove('btn-my-secondary','pill');
                        x.classList.add   ('btn-transparent','rounded-0');
                    });
                    btn.classList.add   ('btn-my-secondary','pill');
                    btn.classList.remove('btn-transparent','rounded-0');

                    // 2) Carrega as cards via AJAX/Fetch
                    const data = btn.getAttribute('data-data');

                });
            });
        });
        window.initMap = function() {
            // monta o endereço completo;
            const address = "{{ $evento->endereco->rua }}, {{ $evento->endereco->numero }}, {{ $evento->endereco->cidade }}, {{$evento->endereco->uf}}, Brasil";

            const geocoder = new google.maps.Geocoder();

            geocoder.geocode({ address }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    const local = results[0].geometry.location;
                    const mapa = new google.maps.Map(
                        document.getElementById("mapaGoogle"),
                        { center: local, zoom: 15 }
                    );
                    new google.maps.Marker({ position: local, map: mapa });
                } else {
                    console.error('Geocode falhou: ' + status);
                    // fallback: exiba o mapa em São Paulo centro, por exemplo
                    const fallback = { lat: -8.906580454895977, lng: -36.49428189189237 };
                    const mapa = new google.maps.Map(
                        document.getElementById("mapaGoogle"),
                        { center: fallback, zoom: 12 }
                    );
                }
            });
        };
    </script>
    <script>
        var loginModal = document.getElementById('modalLoginPrompt');
        loginModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var acao = button.getAttribute('data-acao');
            loginModal.querySelector('#modalActionText').textContent = acao;
        });
    </script>

    <script>
        $(document).ready(function(){
            // dados vindos do controller
            let atividades = @json($atividadesAgrupadas);

            function formatHora(hora) {
                return hora.slice(0,5);

            }



            function gerarCard(ativ, dia) {
                const dataAtividade = ativ.datas_atividade.find(d => d.data === dia);

                const inicio = formatHora(dataAtividade.hora_inicio);
                const fim    = formatHora(dataAtividade.hora_fim);


                let botVagas = '';
                if (ativ.vagas != null) {
                    if (ativ.vagas > 0) {
                        botVagas = `
                         <div class="col-auto">
                            <button type="button" class="btn btn-success btn-sm px-3 py-1 rounded-pill"> Vagas disponíveis </button>
                         </div>`;
                    } else {
                        botVagas = `
                        <div class="col-auto">
                        <button type="button" class="btn btn-danger btn-sm px-3 py-1 rounded-pill"> Vagas encerradas </button>
                        </div>`;
                    }
                }

                return `
                <div class="card shadow w-100 d-flex flex-column">
                    <div class="card-body d-flex flex-column justify-content-between align-items-start">
                <div>
                    <strong>${inicio} - ${fim}</strong>
                    <p class="mb-0">${ativ.titulo}</p>
                </div>
                <div class="row mt-3 gx-2">
                    <div class="col-auto">
                        <button
                        class="btn btn-my-outline-primary btn-sm px-3 py-1 rounded-pill"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#modalAtividadeShow${ativ.id}"
                        >
                        Saiba mais
                        </button>
                    </div>
                     ${botVagas}
                </div>
            </div>
            </div>`;
            }


            function equalizeHeights() {
                const cards = $('#cards-atividade .card');
                cards.css('height', 'auto');
                let max = 0;
                cards.each(function(){
                    max = Math.max(max, $(this).outerHeight());
                });
                cards.height(max);
            }

            $('.carregar-cards').on('click', function(){
                const dia = $(this).data('data');
                const agrup = atividades[dia] || [];

                $('#cards-atividade').empty();

                agrup.forEach(item => {
                    $('#cards-atividade').append(`
        <div class="col d-flex align-items-stretch">
          ${gerarCard(item, dia)}
        </div>
      `);
                });
                equalizeHeights();
            });

            $(window).on('resize', equalizeHeights);
        });



        $('#carouselCategorias').carousel({
            interval: 10000
        })

    </script>
    @if (session('abrirmodalinscricao'))
        <script>
            $('#modalInscrever').modal('show')
        </script>
    @endif
    <script>



        function changeTrabalho(x) {
            document.getElementById('trabalhoNovaVersaoId').value = x;
        }
    </script>
    @if ($dataInicial != '' && $evento->exibir_calendario_programacao)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                /* initialize the external events
                -----------------------------------------------------------------*/
                // var containerEl = document.getElementById('external-events-list');
                // new FullCalendar.Draggable(containerEl, {
                //   itemSelector: '.fc-event',
                //   eventData: function(eventEl) {
                //     return {
                //       title: eventEl.innerText.trim()
                //     }
                //   }
                // });
                //// the individual way to do it
                // var containerEl = document.getElementById('external-events-list');
                // var eventEls = Array.prototype.slice.call(
                //   containerEl.querySelectorAll('.fc-event')
                // );
                // eventEls.forEach(function(eventEl) {
                //   new FullCalendar.Draggable(eventEl, {
                //     eventData: {
                //       title: eventEl.innerText.trim(),
                //     }
                //   });
                // });
                /* initialize the calendar
                -----------------------------------------------------------------*/
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialDate: "{{ $dataInicial->data }}",
                    headerToolbar: {
                        left: 'dayGridMonth,timeGridWeek,timeGridDay,listYear',
                        center: 'title',
                        right: 'prev,next today'
                    },
                    initialView: 'listYear',
                    locale: 'pt-br',
                    editable: false,
                    eventClick: function(info) {
                        var idModal = "#modalAtividadeShow" + info.event.id;
                        $(idModal).modal('show');
                    },
                    events: "{{ route('atividades.json', ['id' => $evento->id]) }}",
                });
                calendar.render();
            });
        </script>
    @endif

@endsection
