@extends('layouts.app')
@section('content')

    <div class="container">
        @php
            $bannerPath =
                $evento->is_multilingual && Session::get('idiomaAtual') === 'en' && $evento->fotoEvento_en
                    ? $evento->fotoEvento_en
                    : $evento->fotoEvento;
        @endphp
        <div class="row my-5">
            <div class="col-md-7">
                @if (isset($evento->fotoEvento))
                    <img src="{{ asset('storage/' . $bannerPath) }}" class="rounded" width="100%" alt="">
                @else
                    <img src="{{ asset('img/colorscheme.png') }}" class="rounded" width="100%" alt="">
                @endif
            </div>
            <div class="col-md-5 pt-3">
                @if ($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                    <h2 class="text-my-primary">{{ $evento->nome_en }}</h2>
                @elseif ($evento->is_multilingual && Session::get('idiomaAtual') === 'es')
                    <h2 class="text-my-primary">{{ $evento->nome_es }}</h2>
                @else
                    <h2 class="text-my-primary">{{ $evento->nome }}</h2>
                @endif
                <span class="d-flex align-items-center gap-1 text-my-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-calendar-event" viewBox="0 0 16 16">
                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                    </svg>
                    {{ \Carbon\Carbon::parse($evento->dataFim)->locale(Session::get('idiomaAtual', 'pt'))->translatedFormat('l, d F') }}
                </span>
                <span class="text-my-primary my-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                    </svg> {{$evento->endereco->rua}}, {{$evento->endereco->numero}}, {{$evento->endereco->cidade}}
                </span>
                <div class="text-secondary py-4">
                    <p class="m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                        {{ __('Organizado por:') }}
                    </p>
                    <p class="m-0 ps-4">
                        {{$evento->coordenador->name}}
                    </p>
                </div>

                @if ($etiquetas->modinscricao == true)
                    <button id="btn-inscrevase" class="btn btn-my-success w-50 rounded btn-lg" data-bs-toggle="modal" data-bs-target="#modalInscrever"
                        @if ($isInscrito || $encerrada) disabled @endif>
                        @if ($isInscrito)
                            {{ __('Já inscrito') }}
                        @elseif($encerrada)
                            {{ __('Encerradas') }}
                        @else
                            {{ __('Inscreva-se') }}
                        @endif
                    </button>
                    @isset($inscricao)
                        @isset($inscricao->pagamento)
                            <a href="{{ route('checkout.statusPagamento', $evento->id) }}"
                                class="text-center mt-2 w-100">{{ __('Visualizar status do pagamento') }}</a>
                        @endisset
                    @endisset
                @endif

            </div>
        </div>
        <div class="row">
            <h4 class="text-my-primary">{{ __('Descrição do evento') }}</h4>
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
                <a href="#submissao_trabalho" class="btn card d-flex justify-content-center align-items-center text-my-primary shadow p-3" style="width: 180px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                        class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg>
                    <h6 class="mt-2">{{ __('Submissão de trabalhos') }}</h6>
                </a>
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
                <a href="#palestrantes" class="btn card d-flex justify-content-center align-items-center text-my-primary shadow p-3" style="width: 180px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                        class="bi bi-people" viewBox="0 0 16 16">
                        <path
                            d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                    </svg>
                    <h6 class="mt-2">{{ __('Nossos palestrantes') }}</h6>
                </a>
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

        <div id="submissao_trabalho" class="row py-4">
            <h4 class="text-my-primary">
                {{ __('Submissão de trabalho') }}
            </h4>
            <div class="col-md-6">
                <div class="card rounded">
                    <div class="card-heading bg-my-primary rounded pt-3 pb-1 ps-3">
                        <h5 class="text-white">{{ __('Modalidades') }}</h5>
                    </div>
                    <div class="card-body">
                        @if ($etiquetas->modsubmissao == true)
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="accordion" id="accordion_modalidades"
                                        style="font-size: 10px;">
                                        @foreach ($modalidades as $modalidade)
                                            @if (Carbon\Carbon::parse($modalidade->ultima_data) >= $mytime)
                                                <div class="accordion-group">
                                                    <div class="accordion-heading">
                                                        <a class="accordion-button collapsed bg-transparent text-dark" data-bs-toggle="collapse"
                                                            data-bs-parent="#accordion_modalidades" href="#collapse_{{ $modalidade->id }}">
                                                                <span> <strong>{{ $modalidade->nome }}</strong></span>
                                                        </a>
                                                    </div>

                                                    <div id="collapse_{{ $modalidade->id }}" class="accordion-body in collapse" style="height: auto;">
                                                        <ul class="list-unstyled fs-6">
                                                            <li>
                                                                <img class="" src="{{ asset('img/icons/calendar-pink.png') }}" alt="" style="width:20px;">
                                                                {{ __('Envio') }}: {{ date('d/m/Y H:i', strtotime($modalidade->inicioSubmissao)) }} -
                                                                {{ date('d/m/Y H:i', strtotime($modalidade->fimSubmissao)) }}
                                                            </li>
                                                            <li>
                                                                <img class="" src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;">
                                                                {{ __('Avaliação') }}: {{ date('d/m/Y H:i', strtotime($modalidade->inicioRevisao)) }} -
                                                                {{ date('d/m/Y H:i', strtotime($modalidade->fimRevisao)) }}
                                                            </li>
                                                            @if ($modalidade->inicioCorrecao && $modalidade->fimCorrecao)
                                                                <li>
                                                                    <img class="" src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;">
                                                                    {{ __('Correção') }}: {{ date('d/m/Y H:i', strtotime($modalidade->inicioCorrecao)) }} -
                                                                    {{ date('d/m/Y H:i', strtotime($modalidade->fimCorrecao)) }}
                                                                </li>
                                                            @endif
                                                            @if ($modalidade->inicioValidacao && $modalidade->fimValidacao)
                                                                <li>
                                                                    <img class="" src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;">
                                                                    {{ __('Validação') }}: {{ date('d/m/Y H:i', strtotime($modalidade->inicioValidacao)) }} -
                                                                    {{ date('d/m/Y H:i', strtotime($modalidade->fimValidacao)) }}
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <img class="" src="{{ asset('img/icons/calendar-green.png') }}" alt="" style="width:20px;">
                                                                {{ __('Resultado') }}: {{ date('d/m/Y  H:i', strtotime($modalidade->inicioResultado)) }}
                                                            </li>
                                                            @foreach ($modalidade->datasExtras as $data)
                                                                <li>
                                                                    <img class="" src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;">
                                                                    {{ $data->nome }}: {{ date('d/m/Y H:i', strtotime($data->inicio)) }} -
                                                                    {{ date('d/m/Y H:i', strtotime($data->fim)) }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                @if ($modalidade->arquivo)
                                                                    @if (isset($modalidade->regra))
                                                                        <div
                                                                            style="margin-top: 20px; margin-bottom: 10px;">
                                                                            <a href="{{ route('modalidade.regras.download', ['id' => $modalidade->id]) }}"
                                                                                target="_new"
                                                                                style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                <img class=""
                                                                                    src="{{ asset('img/icons/file-download-solid.svg') }}"
                                                                                    style="width:20px;">&nbsp;{{ $evento->formEvento->etiquetabaixarregra }}
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                    @if (isset($modalidade->modelo_apresentacao))
                                                                        <div
                                                                            style="margin-top: 20px; margin-bottom: 10px;">
                                                                            <a href="{{ route('modalidade.modelos.download', ['id' => $modalidade->id]) }}"
                                                                                target="_new"
                                                                                style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                <img class=""
                                                                                    src="{{ asset('img/icons/file-download-solid.svg') }}"
                                                                                    style="width:20px;">&nbsp;{{ $evento->formEvento->etiquetabaixarapresentacao }}
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                    @if (isset($modalidade->template))
                                                                        <div
                                                                            style="margin-top: 20px; margin-bottom: 10px;">
                                                                            <a href="{{ route('modalidade.template.download', ['id' => $modalidade->id]) }}"
                                                                                target="_new"
                                                                                style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                <img class=""
                                                                                    src="{{ asset('img/icons/file-download-solid.svg') }}"
                                                                                    style="width:20px;">&nbsp;{{ $evento->formEvento->etiquetabaixartemplate }}
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    @if (isset($modalidade->modelo_apresentacao))
                                                                        <div
                                                                            style="margin-top: 20px; margin-bottom: 10px;">
                                                                            <a href="{{ route('modalidade.modelos.download', ['id' => $modalidade->id]) }}"
                                                                                target="_new"
                                                                                style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                <img class=""
                                                                                    src="{{ asset('img/icons/file-download-solid.svg') }}"
                                                                                    style="width:20px;">&nbsp;{{ $evento->formEvento->etiquetabaixarapresentacao }}
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                    @if (isset($modalidade->regra))
                                                                        <div
                                                                            style="margin-top: 20px; margin-bottom: 10px;">
                                                                            <a href="{{ route('modalidade.regras.download', ['id' => $modalidade->id]) }}"
                                                                                target="_new"
                                                                                style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                <img class=""
                                                                                    src="{{ asset('img/icons/file-download-solid.svg') }}"
                                                                                    style="width:20px;">&nbsp;{{ $evento->formEvento->etiquetabaixarregra }}
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                                @auth
                                                                    @if ($modalidade->estaEmPeriodoDeSubmissao() && $inscricao?->podeSubmeterTrabalho())
                                                                        <a class="btn btn-my-success w-100 my-4"
                                                                            href="{{ route('trabalho.index', ['id' => $evento->id, 'idModalidade' => $modalidade->id]) }}"
                                                                            >{{ __('SUBMETER TRABALHO') }}</a>
                                                                    @else
                                                                        @can('isCoordenadorOrCoordenadorDasComissoes',
                                                                            $evento)
                                                                            <a class="btn btn-my-success w-100 my-4"
                                                                                href="{{ route('trabalho.index', ['id' => $evento->id, 'idModalidade' => $modalidade->id]) }}"
                                                                                >{{ __('SUBMETER TRABALHO') }}</a>
                                                                        @endcan
                                                                    @endif
                                                                @endauth
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-heading bg-my-primary rounded pt-3 pb-1 ps-3">
                        <h5 class="text-white">{{ __('Áreas temáticas') }}</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($areas) && count($areas) > 0)
                            <ul>
                                @foreach($areas->take(5) as $area)
                                    <li>{{ $area->nome }}</li>
                                @endforeach
                            </ul>
                            @if($areas->count() > 5)
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-my-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#modalTodasAreas">
                                        {{ __('Ver todos') }}
                                    </button>
                                </div>
                                @include('evento.modal-areas')
                            @endif
                        @else
                            <p>{{ __('Nenhuma área cadastrada.') }}</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        @if($temPdfProg || $temPdfArquivo || $temInfosExtras)
        <hr class="border-dark my-4">
            <div id="info_adicionais" class="row py-4">{{-- Informações adicionais --}}
                <div class="col-md-6">
                        <h4 class="text-my-primary">
                            {{ __('Informações adicionais') }}
                        </h4>


                        @if ($evento->exibir_pdf && $etiquetas->modprogramacao == true && $evento->pdf_programacao != null)
                            <div
                                class="form-row justify-content-center @if ($evento->exibir_pdf && $etiquetas->modprogramacao && $evento->pdf_programacao) mb-3 @endif">
                                <div class="col-sm-3 form-group "
                                    style="position: relative; text-align: center;">
                                    <div class="div-icon-programacao">
                                        <img class="icon-programacao"
                                            src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}"
                                            alt="PDF com a programação">
                                    </div>
                                </div>
                                <div class="col-sm-8 form-inline">
                                    <span class="titulo">
                                        <a href="{{ asset('storage/' . $evento->pdf_programacao) }}"
                                            target="_black">{{ $etiquetas->etiquetamoduloprogramacao }}</a>
                                    </span>
                                </div>
                            </div>
                        @endif
                        @if ($evento->pdf_arquivo != null && $evento->modarquivo)
                            <div class="form-row justify-content-center">
                                <div class="col-sm-3 form-group "
                                    style="position: relative; text-align: center;">
                                    <div class="div-icon-programacao">
                                        <img class="icon-programacao"
                                            src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}"
                                            alt="PDF com a programação">
                                    </div>
                                </div>
                                <div class="col-sm-8 form-inline">
                                    <span class="titulo">
                                        <a href="{{ asset('storage/' . $evento->pdf_arquivo) }}"
                                            target="_black">{{ $etiquetas->etiquetaarquivo }}</a>
                                    </span>
                                </div>
                            </div>
                        @endif
                        @foreach ($evento->arquivoInfos as $arquivo)
                            <div
                                class="form-row justify-content-center @if (!$loop->last) mb-3 @endif">
                                <div class="col-sm-3 form-group  d-flex align-items-center"
                                    style="position: relative; text-align: center;">
                                    <div class="div-icon-programacao">
                                        <img class="icon-programacao"
                                            src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}"
                                            alt="">
                                    </div>
                                </div>
                                <div class="col-sm-8 form-inline">
                                    <span class="titulo">
                                        <a href="{{ asset('storage/' . $arquivo->path) }}"
                                            target="_black">{{ $arquivo->nome }}</a>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                </div>
            @endif
            @if($evento->memorias->isNotEmpty())
                <div class="col-md-6">
                    <h4 class="text-my-primary">{{ __('Memórias') }}</h4>
                    @if ($evento->memorias->count())
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-sm-12">
                                <div class="card sombra-card" style="width: 100%;">
                                    <div class="card-body">

                                        @foreach ($evento->memorias as $memoria)
                                            <div class="form-row justify-content-center">
                                                <div class="col-sm-3 form-group ">
                                                    <div class="div-icon-programacao d-flex justify-content-center">
                                                        @if ($memoria->arquivo)
                                                            <img class="icon-programacao"
                                                                src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}"
                                                                alt="">
                                                        @elseif($memoria->link)
                                                            <img class="icon-programacao"
                                                                src="{{ asset('img/icons/link-solid.svg') }}"
                                                                alt="">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 form-inline">
                                                    <span class="titulo">
                                                        @if ($memoria->arquivo)
                                                            <a href="/storage/{{ $memoria->arquivo }}"
                                                                target="_blank">{{ $memoria->titulo }}</a>
                                                        @elseif($memoria->link)
                                                            <a href="{{ $memoria->link }}"
                                                                target="_blank">{{ $memoria->titulo }}</a>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        <hr class="border-dark">

        <div id="programacao" class="row py-4">{{-- Programação --}}
            <h4 class="text-my-primary">{{ __('Programação') }}</h4>

            @if ($etiquetas->modprogramacao == true && $evento->exibir_calendario_programacao)
                <div id="mensagem-aviso" class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ __('Para participar das atividades do evento, é preciso primeiro se inscrever no evento e, em seguida, realizar a inscrição na atividade desejada, disponível na seção de Programação.') }}
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Fechar">
                    </button>
                </div>



                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sm-12">
                        <div class="card sombra-card" style="width: 100%;">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-sm-12 form-group">
                                        <h4 style="font-weight: bold; border-bottom: solid 3px #114048ff;">
                                            {{ $etiquetas->etiquetamoduloprogramacao }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-sm-12 form-group">
                                        <div id="wrap">
                                            <div id='calendar-wrap' style="width: 100%;">
                                                <div id='calendar'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

        </>
        <hr class="border-dark">

        <div id="palestrantes" class="row py-4">{{-- Nossos palestrantes --}}
            <h4 class="text-my-primary">{{ __('Nossos palestrantes') }}</h4>
            @include('evento.carrossel-palestrantes', ['id' => 'palestrantes', 'palestrantes' => $evento->palestrantes])
        </div>
        <hr class="border-dark">

        <div id="contato" class="row py-4 d-flex">{{-- Contatos --}}
            <h4 class="text-my-primary mb-4">{{ __('Evento organizado por:') }}</h4>
            <!-- <div class="col-md-2">
                <img src="" class="" alt="">
            </div> -->
             <div class=" d-flex flex-column align-items-center">
                <h4 class="mb-3">{{ $evento->coordenador->name }}</h4>
                
                <a href="mailto:@if($evento->email){{ $evento->email }}@else{{ $evento->coordenador->email }}@endif" class="btn btn-my-secondary rounded-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope me-1" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                    </svg>
                    {{ __('Entre em contato') }}
                </a>        
            </div>
        </div>
        
        <hr class="border-dark">

        <div id="local" class="row py-4">{{-- Local --}}
            <h4 class="text-my-primary">{{ __('Local') }}</h4>
            <div id="mapaGoogle" class="shadow rounded w-100" style="height: 400px;">

            </div>
        </div>
        @include('evento.modal-inscricao')
        @include('evento.modal-submeter-trabalho')

    </div>


    @foreach ($atividades as $atv)
        <div class="modal fade bd-example modal-show-atividade" id="modalAtividadeShow{{ $atv->id }}"
            tabindex="-1" role="dialog" aria-labelledby="modalLabelAtividadeShow{{ $atv->id }}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                        <h5 class="modal-title" id="modalLabelAtividadeShow{{ $atv->id }}">{{ $atv->titulo }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 for="tipo">{{ __('Tipo') }}</h4>
                                    <p>
                                        {{ $atv->tipoAtividade->descricao }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label for="descricao">{{ __('Descrição') }}</label>
                                    <p>
                                        {!! $atv->descricao !!}
                                    </p>
                                </div>

                            </div>
                            @if (count($atv->convidados) > 0)
                                <hr>
                                <h4>{{ __('Convidados') }}</h4>
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
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 for="local">{{ __('Local') }}</h4>
                                    <p class="local">
                                        {{ $atv->local }}
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
                    </div>
                    <div class="modal-footer">
                        @if ($isInscrito)
                            @if (!$atv->atividadeInscricoesEncerradas())
                                @if (($atv->vagas > 0 || $atv->vagas == null) && Auth::user()->atividades()->find($atv->id) == null)
                                    <form method="POST"
                                        action="{{ route('atividades.inscricao', ['id' => $atv->id]) }}">
                                        @csrf
                                        <button type="submit" class="button-prevent-multiple-submits btn btn-primary">
                                            {{ __('Inscrever-se') }}</button>
                                    </form>
                                @elseif(Auth::user()->atividades()->find($atv->id) != null)
                                    @if (!$atv->terminou())
                                        <form method="POST"
                                            action="{{ route('atividades.cancelarInscricao', ['id' => $atv->id, 'user' => Auth::id()]) }}">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-primary button-prevent-multiple-submits">
                                                {{ __('Cancelar inscrição') }}</button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-primary"
                                            disabled>{{ __('Inscrito') }}</button>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-danger"
                                        style="pointer-events: none">{{ __('Sem Vagas') }}</button>
                                @endif
                            @else
                                @if (Auth::user()->atividades()->find($atv->id) != null)
                                    <button type="button" class="btn btn-primary" disabled>{{ __('Inscrito') }}</button>
                                @else
                                    <button type="button" class="btn btn-danger"
                                        disabled>{{ __('Inscrições encerradas') }}</button>
                                @endif
                            @endif
                        @endif
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Fechar') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('javascript')
    <script>
        function initMap() {
                const local = { lat: -23.55052, lng: -46.633308 };
                const mapa = new google.maps.Map(document.getElementById("mapaGoogle"), {
                    zoom: 13,
                    center: local,
                });
                new google.maps.Marker({
                    position: local,
                    map: mapa,
                });
            }

        window.onload = initMap;

        $('#carouselCategorias').carousel({
            interval: 10000
        })

        $('.carousel-categorias .carousel .carousel-item').each(function() {
            var minPerSlide = 3;
            var next = $(this).next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }
            next.children(':first-child').clone().appendTo($(this));

            for (var i = 0; i < minPerSlide; i++) {
                next = next.next();
                if (!next.length) {
                    next = $(this).siblings(':first');
                }

                next.children(':first-child').clone().appendTo($(this));
            }
        });
    </script>
    @if (session('abrirmodalinscricao'))
        <script>
            $('#modalInscrever').modal('show')
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $(".accordion-button").click(function() {
                var img = this.children[0].children[1].children[0];
                if (this.classList.contains("collapsed")) {
                    img.src = "{{ asset('img/icons/Icon ionic-ios-arrow-up.svg') }}";
                } else {
                    img.src = "{{ asset('img/icons/Icon ionic-ios-arrow-down.svg') }}";
                }
            });
        });
        var botoes = document.getElementsByClassName('cor-aleatoria');
        for (var i = 0; i < botoes.length; i++) {
            botoes[i].style.backgroundColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
        }

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
    <script>
        // Fecha a caixa de aviso quando o botão de fechar é clicado
        $(document).ready(function() {
            $('.alert-dismissible').on('closed.bs.alert', function() {
                // Envia uma requisição para o servidor para marcar a mensagem como lida, se necessário
                // Exemplo: usar AJAX para enviar um POST request para marcar a mensagem como lida no banco de dados
            });
        });
    </script>
@endsection
