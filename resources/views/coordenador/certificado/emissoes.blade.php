@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarTrabalhos"
        style="display: block">
        <div class="row ">
            <div class="col-sm-12">
                <h2 class="">Emissões do certificado {{ $certificado->nome }}</h2>
            </div>
            {{-- <div class="col-sm-3"></div> --}}
            {{-- <div class="col-sm-3"> --}}
            {{-- <form method="GET"
                    action="{{ route('distribuicao') }}">
                    <input type="hidden"
                        name="eventoId"
                        value="{{ $evento->id }}">
                    <button onclick="event.preventDefault();"
                        data-toggle="modal"
                        data-target="#modalDistribuicaoAutomatica"
                        class="btn btn-primary"
                        style="width:100%">
                        {{ __('Distribuir trabalhos') }}
                    </button>
                </form> --}}
        </div>
    </div>
    <div class="row table-trabalhos">
        <div class="col-sm-12">
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
                <br>
                @if ($usuarios->count() == 0)
                    <h5>Nenhum certificado emitido</h5>
                @else
                    <table class="table table-hover table-responsive-lg table-sm table-striped">
                        <thead>
                            <tr>
                                <th scope="col">
                                    Nome
                                </th>
                                <th scope="col">
                                    E-mail
                                </th>
                                <th scope="col">
                                    @switch($certificado->tipo)
                                        @case($tipos['apresentador'])
                                            Trabalho
                                        @break

                                        @case($tipos['expositor'])
                                            Palestra
                                        @break

                                        @case($tipos['outras_comissoes'])
                                            Comissão
                                        @break

                                        @default
                                        @break
                                    @endswitch
                                </th>
                                <th scope="col">
                                    Data
                                </th>
                                <th scope="col"
                                    style="text-align:center">
                                    Invalidar
                                </th>
                                <th scope="col"
                                    style="text-align:center">
                                    Visualizar
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $i => $usuario)
                                <tr>
                                    <td>
                                        @if ($certificado->tipo == $tipos['expositor'])
                                            {{ $usuario->nome }}
                                        @else
                                            {{ $usuario->name }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $usuario->email }}
                                    </td>
                                    <td>
                                        @switch($certificado->tipo)
                                            @case($tipos['apresentador'])
                                                {{ $trabalhos->find($usuario->pivot->trabalho_id)->titulo }}
                                            @break

                                            @case($tipos['expositor'])
                                                {{ $palestras->find($usuario->pivot->palestra_id)->titulo }}
                                            @break

                                            @case($tipos['outras_comissoes'])
                                                {{ $comissao->nome }}
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                    <td>
                                        {{ date('d/m/Y H:i:s', strtotime($usuario->pivot->created_at)) }}
                                    </td>
                                    <td>
                                        <form class="d-none" id="formDeletarEmissao{{$usuario->pivot->id}}" action="{{route('coord.deletar.emissao')}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <input type="hidden" name="evento" value="{{$certificado->evento->id}}">
                                            <input type="hidden" name="certificado_user" value="{{$usuario->pivot->id}}">
                                        </form>
                                        <div class="modal fade" id="modalDeletarEmissao{{$usuario->pivot->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                <div class="modal-header" style="background-color: #114048ff; color: white;">
                                                  <h5 class="modal-title" id="#label">Confirmação</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                <div class="modal-body">
                                                  Tem certeza que deseja deletar a emissão deste certificado para o usuário {{$usuario->name}}?
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                  <button type="submit" class="btn btn-primary" form="formDeletarEmissao{{$usuario->pivot->id}}">Sim</button>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        <a href="#" data-toggle="modal" data-target="#modalDeletarEmissao{{$usuario->pivot->id}}"
                                            class="text-reset d-flex justify-content-center">
                                            <i class="fas fa-archive"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @switch($certificado->tipo)
                                            @case($tipos['apresentador'])
                                                <a class="text-reset d-flex justify-content-center" href="{{ route('coord.previewCertificado', [$certificado->id, $usuario->id, $usuario->pivot->trabalho_id]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            @break

                                            @case($tipos['expositor'])
                                                <a class="text-reset d-flex justify-content-center" href="{{ route('coord.previewCertificado', [$certificado->id, $usuario->id, $usuario->pivot->palestra_id]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            @break

                                            @case($tipos['outras_comissoes'])
                                                <a class="text-reset d-flex justify-content-center" href="{{ route('coord.previewCertificado', [$certificado->id, $usuario->id, $usuario->pivot->comissao_id]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            @break

                                            @case($tipos['inscrito_atividade'])
                                                <a class="text-reset d-flex justify-content-center" href="{{ route('coord.previewCertificado', [$certificado->id, $usuario->id, $usuario->pivot->atividade_id]) }}"
                                                   target="_blank"
                                                   rel="noopener noreferrer">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            @break

                                            @default
                                                <a class="text-reset d-flex justify-content-center" href="{{ route('coord.previewCertificado', [$certificado->id, $usuario->id, 0]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
    </div>
</div>
</div>
@endsection

@section('javascript')
@parent
<script>
    function marcarCheckboxes() {
        $(".trabalhos").prop('checked', $('#selectAllCheckboxes').is(":checked"));
    }
</script>
@endsection
