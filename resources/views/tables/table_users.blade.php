<table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome</th>
        <th scope="col">Email</th>
        <th scope="col">Função</th>
        <th scope="col">Opções</th>

      </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>

                <th scope="row">{{ $users->firstItem() + $loop->index }}</th>
                <th scope="row">{{ $user->name }}</th>
                <td>
                    <select name="" id="">


                        @if($user->coordComissaoCientifica)
                            <option value="">{{ "Cood. Comissão Cientifica" }}</option>
                        @endif
                        @if($user->coordComissaoOrganizadora)
                            <option value="">{{ "Cood. Comissão Organizadora" }}</option>
                        @endif
                        @if($user->revisor)
                            <option value="">{{ "Avaliador" }}</option>
                        @endif
                        @if($user->coautor)
                            <option value="">{{ "Coutor" }}</option>
                        @endif
                        @if($user->participante)
                            <option value="">{{ "Participante" }}</option>
                        @endif
                        @if($user->coordEvento)
                            <option value="">{{ "Coordenador de Evento" }}</option>
                        @endif
                    </select>
                </td>
                <th scope="row">{{ $user->email }}</th>
                <td colspan="2">
                    <a href="{{ route('admin.editUser', ['id' => $user->id]) }}" class="btn btn-warning">Editar</a>
                    {{-- <a href="{{ route('admin.deleteUser', ['id' => $user->id]) }}" onclick="this.confirm()" class="btn btn-danger">Excluir</a> --}}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
{{  $users->links() }}
