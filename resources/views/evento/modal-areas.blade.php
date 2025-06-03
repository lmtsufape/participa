<!-- Modal Todas as Áreas -->
<div class="modal fade" id="modalTodasAreas" tabindex="-1" aria-labelledby="modalTodasAreasLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" >
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modalTodasAreasLabel">{{ __('Todos os eixos temáticos') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Fechar') }}"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                    <div class="col-6 ">
                        <ul class="list-unstyled">
                            @foreach($areas->slice(0, ceil($areas->count()/2)) as $area)
                                <li class="d-flex align-items-center mb-2">
                                    @if ($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                        <span class="flex-grow-1">{{ $area->nome_en }}</span>
                                    @elseif ($evento->is_multilingual && Session::get('idiomaAtual') === 'es')
                                        <span class="flex-grow-1">{{ $area->nome_es }}</span>
                                    @else
                                        <span class="flex-grow-1">{{ $area->nome }}</span>

                                    @endif
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm p-1 d-flex align-items-center justify-content-center"
                                        style="width:1.5rem; height:1.5rem; border-color: transparent"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#resumo{{ $area->id }}"
                                        aria-controls="resumo{{ $area->id }}"
                                        >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0
                                                    .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064
                                                    -.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1
                                                    1 0-2 1 1 0 0 1 0 2"/>
                                        </svg>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-6 ">
                        <ul class="list-unstyled">
                           @foreach($areas->slice(ceil($areas->count()/2)) as $area)
                                <li class="d-flex align-items-center mb-2">
                                    @if ($evento->is_multilingual)
                                        @if (Session::get('idiomaAtual') === 'en')
                                            <span class="flex-grow-1">{{ $area->nome_en }}</span>
                                        @elseif (Session::get('idiomaAtual') === 'es')
                                            <span class="flex-grow-1">{{ $area->nome_es }}</span>
                                        @else
                                            <span class="flex-grow-1">{{ $area->nome }}</span>
                                        @endif
                                    @else
                                        <span class="flex-grow-1">{{ $area->nome }}</span>
                                    @endif
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm p-1 d-flex align-items-center justify-content-center"
                                        style="width:1.5rem; height:1.5rem; border-color: transparent"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#resumo{{ $area->id }}"
                                        aria-controls="resumo{{ $area->id }}"
                                        >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0
                                                    .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064
                                                    -.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1
                                                    1 0-2 1 1 0 0 1 0 2"/>
                                        </svg>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div id="accordionAreas">
                    @foreach($areas as $area)
                        <div class="row">
                        <div class="col-12">
                            <div
                            class="collapse mt-2"
                            id="resumo{{ $area->id }}"
                            data-bs-parent="#accordionAreas"
                            >
                            <div class="card card-body p-2" style="text-align:justify;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">
                                    {{ __('Apresentação do eixo') }}:
                                    @if ($evento->is_multilingual)
                                        @if (Session::get('idiomaAtual') === 'en')
                                            {{ $area->nome_en }}
                                        @elseif (Session::get('idiomaAtual') === 'es')
                                            {{ $area->nome_es }}
                                        @else
                                            {{ $area->nome }}
                                        @endif

                                    @endif
                                </h5>
                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#resumo{{ $area->id }}"
                                ></button>
                                </div>
                                @if ($evento->is_multilingual && $area->resumo)
                                    @if (Session::get('idiomaAtual') === 'en' && $area->resumo_en)
                                        {{ $area->resumo_en }}
                                    @elseif (Session::get('idiomaAtual') === 'es' && $area->resumo_es)
                                        {{ $area->resumo_es }}
                                    @else
                                        {{ $area->resumo }}
                                    @endif
                                @endif
                            </div>
                            </div>
                        </div>
                        </div>
                    @endforeach
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Fechar') }}</button>
            </div>
        </div>
    </div>
</div>
