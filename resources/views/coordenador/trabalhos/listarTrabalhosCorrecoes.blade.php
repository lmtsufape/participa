@extends('coordenador.detalhesEvento')

@section('menu')
    <!-- Trabalhos -->
    <div id="divListarTrabalhos" style="display: block">

        <div class="row ">
            <div class="col-sm-6">
                <h1 class="">Trabalhos Corrigidos</h1>
            </div>
        </div>

        @if(session('message'))
        <div class="row">
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-success">
                    <p>{{session('message')}}</p>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success" role="alert" align="center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            {{session('success')}}
        </div>
        @endif

    {{-- Tabela Trabalhos --}}
    <form action="{{route('coord.evento.avisoCorrecao', $evento->id)}}" method="POST" id="avisoCorrecao">
    @csrf
    @foreach ($trabalhosPorModalidade as $trabalhosPorArea)
        @foreach ($trabalhosPorArea as $trabalhos)

        @if(!is_null($trabalhos->first()))
            <div class="row justify-content-center" style="width: 100%;">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="justify-content-between d-flex">
                                <div>
                                    <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted" >{{$trabalhos->first()->modalidade->nome}}</span>
                                    @if ($trabalhos->first()->modalidade->inicioCorrecao && $trabalhos->first()->modalidade->fimCorrecao)
                                        <h5 class="card-title">Correção: <span class="card-subtitle mb-2 text-muted" >{{date("d/m/Y H:i", strtotime($trabalhos->first()->modalidade->inicioCorrecao))}} - {{date("d/m/Y H:i",strtotime($trabalhos->first()->modalidade->fimCorrecao))}}</span></h5>
                                    @else
                                        <h5 class="card-title">Correção: <span class="card-subtitle mb-2 text-muted" >não haverá</span></h5>
                                    @endif
                                    <h5 class="card-title">{{$trabalhos->first()->evento->formSubTrab->etiquetaareatrabalho}}: <span class="card-subtitle mb-2 text-muted" >{{$trabalhos->first()->area->nome}}</span></h5>
                                </div>
                                <div>
                                    <button class="btn btn-primary" type="submit" form="avisoCorrecao">Lembrete de envio de versão corrigida do texto</button>
                                </div>
                            </div>
                            <div class="row table-trabalhos">
                            <div class="col-sm-12">
                                @csrf

                                <input type="hidden" name="eventoId" value="{{$evento->id}}">
                                <br>
                                <table class="table table-hover table-responsive-lg table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onchange="alterarSelecionados(this)"></th>
                                            <th scope="col" >Trabalho inicial</th>
                                            <th scope="col" >Trabalho revisado</th>
                                            <th scope="col" >Autor</th>
                                            <th scope="col">
                                                Data
                                                <a href="{{route('coord.listarCorrecoes', ['eventoId' => $evento->id, 'data', 'asc'])}}">
                                                    <i class="fas fa-arrow-alt-circle-up"></i>
                                                </a>
                                                <a href="{{route('coord.listarCorrecoes',[ 'eventoId' => $evento->id, 'data', 'desc'])}}">
                                                    <i class="fas fa-arrow-alt-circle-down"></i>
                                                </a>
                                            </th>
                                            <th scope="col" >Parecer</th>
                                            <th scope="col" class="text-center">Lembrete de correção enviado</th>
                                            <th scope="col" class="text-center">Validação das correções</th>
                                            <th scope="col" style="text-align:center;">Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 0; @endphp
                                        @foreach($trabalhos as $trabalho)
                                            <tr>
                                                <td><input type="checkbox" name="trabalhosSelecionados[]" value="{{$trabalho->id}}"></td>
                                                <td>
                                                    @if ($trabalho->arquivo)
                                                        <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">
                                                            <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                                {{$trabalho->titulo}}
                                                            </span>
                                                        </a>
                                                    @else
                                                        <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                            {{$trabalho->titulo}}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($trabalho->arquivoCorrecao)
                                                        <a href="{{route('downloadCorrecao', ['id' => $trabalho->id])}}">
                                                            <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                                {{$trabalho->titulo}}
                                                            </span>
                                                        </a>
                                                    @else
                                                        <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                            {{$trabalho->titulo}}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{$trabalho->autor->name}}</td>

                                                <td>
                                                    @if ($trabalho->arquivoCorrecao)
                                                        {{ date("d/m/Y H:i", strtotime($trabalho->arquivoCorrecao->created_at) ) }}
                                                    @endif
                                                </td>

                                                <td style="text-align:center">
                                                    @foreach ($trabalho->atribuicoes as $revisor)
                                                        <a href="{{route('coord.visualizarRespostaFormulario', ['eventoId' => $evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id])}}">
                                                            <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                                                        </a>
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">{{$trabalho->lembrete_enviado ? 'Sim' : 'Não'}}</td>
                                                 <td class="text-center">
                                                    @switch($trabalho->avaliado)
                                                        @case('corrigido')
                                                            Finalizado: aprovado completamente
                                                            @break
                                                        @case('corrigido_parcialmente')
                                                            Finalizado: aprovado parcialmente
                                                            @break
                                                        @case('nao_corrigido')
                                                            Finalizado: reprovado
                                                            @break
                                                        @default
                                                        Em análise
                                                    @endswitch
                                                </td>

                                                <td style="text-align:center">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalCorrecaoTrabalho_{{$trabalho->id}}" style="color:#114048ff">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @endforeach
    @endforeach
    </form>

</div>
<!-- End Trabalhos -->

@foreach ($trabalhosPorModalidade as $trabalhosPorArea)
        @foreach ($trabalhosPorArea as $trabalhos)
        @foreach ($trabalhos as $trabalho)
            <!-- Modal  correcao trabalho -->
            <div class="modal fade" id="modalCorrecaoTrabalho_{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalCorrecaoTrabalho_{{$trabalho->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="modalCorrecaoTrabalho_{{$trabalho->id}}Label">Correção do trabalho {{$trabalho->titulo}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCorrecaoTrabalho{{$trabalho->id}}" action="{{route('trabalho.correcao', ['id' => $trabalho->id])}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @php
                        $formSubTraba = $trabalho->evento->formSubTrab;
                        $ordem = explode(",", $formSubTraba->ordemCampos);
                        $modalidade = $trabalho->modalidade;
                    @endphp
                    <input type="hidden" name="trabalhoCorrecaoId" value="{{$trabalho->id}}">
                    @error('numeroMax'.$trabalho->id)
                        <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                            {{ $message }}
                            </div>
                        </div>
                        </div>
                    @enderror
                    @foreach ($ordem as $indice)
                        @if ($indice == "etiquetatitulotrabalho")
                        <div class="row justify-content-center">
                            {{-- Nome Trabalho  --}}
                            <div class="col-sm-12">
                                <label for="nomeTrabalho_{{$trabalho->id}}" class="col-form-label">{{ $formSubTraba->etiquetatitulotrabalho }}</label>
                                <input id="nomeTrabalho_{{$trabalho->id}}" type="text" class="form-control @error('nomeTrabalho'.$trabalho->id) is-invalid @enderror" name="nomeTrabalho{{$trabalho->id}}" value="@if(old('nomeTrabalho'.$trabalho->id)!=null){{old('nomeTrabalho'.$trabalho->id)}}@else{{$trabalho->titulo}}@endif"  autocomplete="nomeTrabalho" autofocus disabled>

                                @error('nomeTrabalho'.$trabalho->id)
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            {{-- Autor Trabalho  --}}
                            <div class="col-sm-12">
                                <label for="autorTrabalho_{{$trabalho->autor->id}}" class="col-form-label">Autor</label>
                                <input id="autorTrabalho_{{$trabalho->autor->id}}" type="text" class="form-control @error('autorTrabalho'.$trabalho->autor->id) is-invalid @enderror" name="autorTrabalho{{$trabalho->autor->id}}" value="@if(old('autorTrabalho'.$trabalho->autor->id)!=null){{old('autorTrabalho'.$trabalho->autor->id)}}@else{{$trabalho->autor->name}}@endif"  autocomplete="autorTrabalho" autofocus disabled>
                            </div>
                        </div>
                        @endif
                        @if ($indice == "etiquetacoautortrabalho")
                        <div class="flexContainer" style="margin-top:20px">

                                <div id="coautores_{{$trabalho->id}}" class="flexContainer " >
                                    @if($trabalho->coautors->first() != null)
                                        <h4>Co-autores</h4>
                                        @foreach ($trabalho->coautors as $i => $coautor)
                                        <div class="item card">
                                            <div class="row card-body">
                                                <div class="col-sm-4">
                                                    <label>E-mail</label>
                                                    <input type="email" style="margin-bottom:10px" value="{{$coautor->user->email}}" oninput="buscarEmail(this)" class="form-control emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" placeholder="E-mail" disabled>
                                                </div>
                                                <div class="col-sm-5">
                                                    <label>Nome Completo</label>
                                                    <input type="text" style="margin-bottom:10px" value="{{$coautor->user->name}}" class="form-control emailCoautor" name="nomeCoautor_{{$trabalho->id}}[]" placeholder="Nome" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>
                        @endif
                        @if ($indice == "etiquetaareatrabalho")
                        <!-- Areas -->
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="area_{{$trabalho->id}}" class="col-form-label">{{$formSubTraba->etiquetaareatrabalho}}</label>
                                <select id="area_{{$trabalho->id}}" class="form-control @error('area'.$trabalho->id) is-invalid @enderror" name="area{{$trabalho->id}}" required>
                                    <option value="{{$trabalho->area->nome}}" selected disabled>{{$trabalho->area->nome}}</option>
                                </select>
                                @error('area'.$trabalho->id)
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if ($indice == "etiquetauploadtrabalho")
                        <div class="row justify-content-center">
                            {{-- Submeter trabalho corrigido --}}

                            @if ($modalidade->arquivo == true)
                            <div class="col-sm-12" style="margin-top: 20px;">
                                @if($trabalho->arquivoCorrecao()->first() != null)
                                    <label for="nomeTrabalho" class="col-form-label">Upload de Correção do Trabalho:</label>
                                        <a href="{{route('downloadCorrecao', ['id' => $trabalho->id])}}">Arquivo atual</a>
                                    <br>
                                    <small>Para trocar o arquivo envie um novo.</small>
                                @endif
                                <div class="custom-file">
                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoCorrecao" required>
                                </div>
                                <small>Arquivos aceitos nos formatos
                                @if($modalidade->pdf == true)<span> - pdf</span>@endif
                                @if($modalidade->jpg == true)<span> - jpg</span>@endif
                                @if($modalidade->jpeg == true)<span> - jpeg</span>@endif
                                @if($modalidade->png == true)<span> - png</span>@endif
                                @if($modalidade->docx == true)<span> - docx</span>@endif
                                @if($modalidade->odt == true)<span> - odt</span>@endif
                                @if($modalidade->zip == true)<span> - zip</span>@endif
                                @if($modalidade->svg == true)<span> - svg</span>@endif.
                                </small>
                                @error('arquivo'.$trabalho->id)
                                <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="formCorrecaoTrabalho{{$trabalho->id}}">Enviar correção</button>
                </div>
                </div>
            </div>
            </div>
            <!-- Fim Modal correcao trabalho -->
    @endforeach
    @endforeach
@endforeach

@endsection

@section('script')
<script>
    function alterarSelecionados(source) {
        let checkboxes = document.querySelectorAll('input[name="trabalhosSelecionados[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = source.checked);
    }
</script>
@endsection

