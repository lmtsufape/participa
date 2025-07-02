@if(isset($inscricao) && !$inscricao->finalizada && !$InscritoSemCategoria)
<div 
    class="modal fade" 
    id="modalAlterarCategoria" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="modalAlterarCategoriaLabel" 
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title w-100 text-center m-0" id="modalAlterarCategoriaLabel">
                    {{ __('Alterar Categoria de Inscrição') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form 
                action="{{ route('inscricao.alterarCategoria', ['inscricao' => $inscricao]) }}" 
                x-data="{ 
                    categoriaInicial: {{ $inscricao->categoria_participante_id }},
                    categoria: {{ $inscricao->categoria_participante_id }},
                    mostrarOpcoes: false
                }" 
                method="POST"
            >
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="categoria" :value="categoria">

                    <div x-show="!mostrarOpcoes">
                        <div class="form-group">
                            <label>{{ __('Categoria selecionada') }}</label>
                            
                            @foreach ($evento->categoriasPermitidasParaUsuario() as $cat)
                                <template x-if="categoria == {{ $cat->id }}">
                                    <input type="text" readonly class="form-control" value="{{ $cat->nome }}">
                                </template>
                            @endforeach

                            <button 
                                type="button" 
                                x-on:click="mostrarOpcoes = true"
                                class="btn btn-md btn-block mt-2 col-sm-12 col-md-6 col-lg-4" 
                                style="background-color: #114048ff; border-color: #114048ff; color:white;"
                            >
                                {{ __('Alterar categoria') }}
                            </button>
                        </div>
                    </div>

                    <div x-show="mostrarOpcoes">
                        <div class="row">
                            @foreach ($evento->categoriasPermitidasParaUsuario() as $cat)
                                <div class="col-md-6 mb-4 d-flex align-items-stretch">
                                    <div class="card shadow h-100 w-100" :class="{ 'border-success border-2': categoria == {{ $cat->id }} }">
                                        <div class="card-header text-white text-center" style="background-color: #114048ff;">
                                            <h4 class="my-0 font-weight-normal">{{ $cat->nome }}</h4>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <p class="flex-grow-1">
                                                <strong>{{ __('Valor:') }}</strong>
                                                @if($cat->valor_total > 0) R$ {{ number_format($cat->valor_total, 2, ',', '.') }} @else {{ __('Gratuita') }} @endif
                                            </p>
                                        </div>
                                        <div class="card-footer d-flex justify-content-center">
                                            <button 
                                                type="button" 
                                                class="btn btn-outline-primary btn-select-categoria" 
                                                x-on:click="categoria = {{ $cat->id }}; mostrarOpcoes = false"
                                            >
                                                {{ __('Selecionar') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button 
                        type="submit" 
                        class="btn btn-primary" 
                        style="background-color: #114048ff; border-color: #114048ff;" 
                        :disabled="categoria == categoriaInicial"
                    >
                        Confirmar Alteração
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
