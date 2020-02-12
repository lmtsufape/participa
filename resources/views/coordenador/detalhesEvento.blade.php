@extends('layouts.app')
@section('sidebar')
<div class="wrapper">
    <div class="sidebar">
        <h2>Nome do Evento</h2>
        <ul>
            <li><a id="informacoes" onclick="habilitarPagina('informacoes')">
                <img src="{{asset('img/icons/info-circle-solid.svg')}}" alt=""> <h5> Informações</h5></a>
            </li>
            <li><a id="trabalhos" onclick="habilitarPagina('trabalhos')">
                <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt=""><h5>Trabalhos</h5></a>
            </li>
            <li><a id="revisores"onclick="habilitarPagina('revisores')">
                <img src="{{asset('img/icons/user-tie-solid.svg')}}" alt=""><h5>Revisores</h5></a>
            </li>
            <li><a id="comissao" onclick="habilitarPagina('comissao')">
                <img src="{{asset('img/icons/user-tie-solid.svg')}}" alt=""><h5>Comissão</h5></a>
            </li>
            <li><a id="candidatos" onclick="habilitarPagina('candidatos')">
                <img src="{{asset('img/icons/user-solid.svg')}}" alt=""><h5>Candidatos</h5></a>
            </li>
            <li><a id="colocacao" onclick="habilitarPagina('colocacao')">
                <img src="{{asset('img/icons/trophy-solid.svg')}}" alt=""><h5>Colocação</h5></a>
            </li>
            <li><a id="atividades" onclick="habilitarPagina('atividades')">
                <img src="{{asset('img/icons/calendar-alt-solid.svg')}}" alt=""><h5>Atividades</h5></a>
            </li>
        </ul>
    </div>

    
</div>
@endsection
@section('content')

<div class="main_content">
    {{-- {{ $evento->id ?? '' }} --}}
    <div id="divInformacoes" class="informacoes">
        <div class="row">
            <div class="col-sm-12">
                <h1>Informações</h1>
            </div>
        </div>
    </div>
    <div id="divTrabalhos" class="trabalhos">
        <h1>Trabalhos</h1>
    </div>
    <div id="divRevisores" class="revisores">
        <h1>Revisores</h1>
    </div>
    <div id="divComissao" class="comissao">
        <div class="row">
            <div class="col-sm-10">
                <h1>Comissão</h1>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalComissao">
                    Adicionar Membro
                  </button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalComissao" tabindex="-1" role="dialog" aria-labelledby="modalComissao" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novo Membro da Comissão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{route('cadastrar.comissao')}}" method="POST">
                <div class="modal-body">
                    
                        @csrf

                        {{-- Nome do Categoria --}}
                        <div class="form-group">
                            <input type="hidden" name="eventoId" value="{{ $evento->id ?? '' }}">
                            {{-- Div para validação --}}
                            <label for="emailMembroComissao" class="control-label">E-mail do novo membro</label>
                            <div class="input-group">
                                <input type="email" name="emailMembroComissao" class="form-control @error('emailMembroComissao') is-invalid @enderror" name="emailMembroComissao" value="{{ old('emailMembroComissao') }}" id="emailMembroComissao" placeholder="E-mail">                            
                                @error('emailMembroComissao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>

        {{-- tabela membros comissão --}}
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-responsive-lg table-hover">
                    <thead>
                        <th>
                            <th>Nome</th>
                            <th>Especialidade</th>
                            <th>Celular</th>
                            <th>E-mail</th>
                        </th>
                    </thead>
                    <tbody>
                        <th>
                            <td>asdhaiosd</td>
                            <td>asdasd</td>
                            <td>asdasd</td>
                            <td>asdasd</td>
                        </th>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="divCandidatos" class="candidatos">
        <h1>Candidatos</h1>
    </div>
    <div id="divColocacao" class="colocacao">
        <h1>Colocação</h1>
    </div>
    <div id="divAtividades" class="atividades">
        <h1>Atividades</h1>
    </div>
</div>
@endsection
@section('javascript')

<script type="text/javascript">

    
    function habilitarPagina(id){
        informacoes = document.getElementById('divInformacoes');
        trabalhos = document.getElementById('divTrabalhos');
        revisores = document.getElementById('divRevisores');
        comissao = document.getElementById('divComissao')
        candidatos = document.getElementById('divCandidatos');
        colocacao = document.getElementById('divColocacao');
        atividades = document.getElementById('divAtividades');
        // habilita divInformacoes
        if(id == 'informacoes'){
            // console.log('informacoes');
            informacoes.style.display = "block";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            
            
        }
        if(id == 'trabalhos'){
            // console.log('trabalhos');
            informacoes.style.display = "none";
            trabalhos.style.display = "block";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            
        }
        if(id == 'revisores'){
            // console.log('revisores');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "block";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
        }
        if(id == 'comissao'){
            // console.log('comissao');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "block";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
        }
        if(id == 'candidatos'){
            // console.log('candidatos');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "block";
            colocacao.style.display = "none";
            atividades.style.display = "none";
        }
        if(id == 'colocacao'){
            // console.log('colocacao');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "block";
            atividades.style.display = "none";
        }
        if(id == 'atividades'){
            // console.log('atividades');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "block";
        }
        
    }

</script>
@endsection