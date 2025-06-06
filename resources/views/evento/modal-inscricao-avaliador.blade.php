<div class="modal fade" id="modalInscreverAvaliador" tabindex="-1" role="dialog" aria-labelledby="modalInscreverAvaliadorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header position-relative" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title w-100 text-center m-0" id="modalInscreverAvaliadorLabel">
                    Formulário para Avaliadores
                </h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 top-50 translate-middle-y me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label for="lattes_link" class="form-label">Link para o currículo Lattes <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="lattes_link" name="lattes_link" placeholder="https://lattes.cnpq.br/..." required>
                    </div>

                    <div class="mb-3">
                        <label for="lattes_resumo" class="form-label">Resumo simplificado do Lattes <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="lattes_resumo" name="lattes_resumo" rows="4" placeholder="Descreva brevemente sua formação, área de atuação e principais publicações..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Em qual/quais eixo(s) temático(s) você gostaria de contribuir? <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Selecione até 03 eixos de sua preferência. O primeiro é obrigatório.</small>
                        
                        <select class="form-select mb-2" name="eixo_preferencia_1" required>
                            <option value="" disabled selected>-- 1ª Preferência (obrigatório) --</option>
                            {{-- Os eixos serão carregados dinamicamente aqui. Ex: --}}
                            @isset($areas)
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nome }}</option>
                                @endforeach
                            @else
                                <option value="1">Eixo Temático Exemplo A</option>
                                <option value="2">Eixo Temático Exemplo B</option>
                            @endisset
                        </select>
                        
                        <select class="form-select mb-2" name="eixo_preferencia_2">
                            <option value="" disabled selected>-- 2ª Preferência (opcional) --</option>
                            @isset($areas)
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nome }}</option>
                                @endforeach
                            @else
                                <option value="1">Eixo Temático Exemplo A</option>
                                <option value="2">Eixo Temático Exemplo B</option>
                            @endisset
                        </select>


                        <select class="form-select" name="eixo_preferencia_3">
                            <option value="" disabled selected>-- 3ª Preferência (opcional) --</option>
                            @isset($areas)
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nome }}</option>
                                @endforeach
                            @else
                                <option value="1">Eixo Temático Exemplo A</option>
                                <option value="2">Eixo Temático Exemplo B</option>
                            @endisset
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Já avaliou resumos do CBA em outros anos? <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="avaliou_antes" id="avaliou_sim" value="sim" required>
                                <label class="form-check-label" for="avaliou_sim">Sim</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="avaliou_antes" id="avaliou_nao" value="nao" required>
                                <label class="form-check-label" for="avaliou_nao">Não</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disponibilidade para avaliar em outros idiomas?</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="idioma_nenhum" name="idiomas[]" value="nao">
                                <label class="form-check-label" for="idioma_nenhum">Não</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="idioma_espanhol" name="idiomas[]" value="espanhol">
                                <label class="form-check-label" for="idioma_espanhol">Espanhol</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="idioma_ingles" name="idiomas[]" value="ingles">
                                <label class="form-check-label" for="idioma_ingles">Inglês</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #114048ff; border-color: #114048ff;">
                        Enviar Candidatura
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>