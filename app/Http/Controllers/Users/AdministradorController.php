<?php

namespace App\Http\Controllers\Users;

use App\Enums\EstadoBrasileiro;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\PerfilIdentitario;
use App\Models\Submissao\Endereco;
use App\Models\Submissao\Evento;
use App\Models\Users\Administrador;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin', Administrador::class);

        return view('administrador.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin', Administrador::class);

        return view('administrador.index');
    }

    public function editais()
    {
        $this->authorize('isAdmin', Administrador::class);

        return view('administrador.index');
    }

    public function eventos()
    {
        $eventos = Evento::latest()->get();

        return view('coordenador.index', ['eventos' => $eventos]);
    }

    public function areas()
    {
        $this->authorize('isAdmin', Administrador::class);

        return view('administrador.index');
    }

    public function users()
    {
        $this->authorize('isAdmin', Administrador::class);
        $users = User::orderBy('updated_at', 'ASC')->paginate(100);
        return view('administrador.users', compact('users'));
    }

    public function editUser($id)
    {
        $this->authorize('isAdmin', Administrador::class);
        $user = User::with('perfilIdentitario')->find($id);
        $end = $user->endereco;
        $estados = EstadoBrasileiro::options();

        return view('administrador.editUser', ['user' => $user, 'estados' => $estados, 'end' => $end]);
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        $this->authorize('isAdmin', Administrador::class);
        $user = User::find($id);
        $payload = $request->payload();
        if (!empty($payload['endereco'])) {
            if ($user->endereco()->exists()) {
                $endereco = Endereco::findOrFail($user->enderecoId);
                $endereco->update($payload['endereco']);
            }else{
                $endereco_id = Endereco::create($payload['endereco'])->id;
            }
        }

        if (!empty($payload['perfilIdentitario'])) {
            if ($user->perfilIdentitario()->exists()) {
                $user->perfilIdentitario->update($payload['perfilIdentitario']);
            } else {

                $perfilIdentitario = PerfilIdentitario::create([...$payload['perfilIdentitario'], 'user_id' => $user->id]);
            }
        }
        $data = [
            ...$payload['user'],
            'usuarioTemp' => null,
        ];

        if (isset($endereco_id)) {
            $payload['enderecoId'] = $endereco_id;
        }
        if ($request->filled('especialidade')) {
            $data['especProfissional'] = $request->input('especialidade');
        }

        $user->update($data);

        return redirect()->route('admin.users')->with(['success' => 'Usuário atualizado com sucesso!']);
    }

    public function search(Request $request)
    {
        $this->authorize('isAdmin', Administrador::class);
        $busca = $request->search;

        try {
            $users = User::whereRaw('unaccent(lower(email)) ILIKE unaccent(lower(?))', ['%' . $busca . '%'])
                ->orWhereRaw('unaccent(lower(name)) ILIKE unaccent(lower(?))', ['%' . $busca . '%'])
                ->orWhereRaw('unaccent(lower(cpf)) ILIKE unaccent(lower(?))', ['%' . $busca . '%'])
                ->paginate(100);
        } catch (\Exception $e) {
            $busca = strtolower($busca);
            $users = User::whereRaw('LOWER(email) like ?', ['%' . $busca . '%'])
                ->orWhereRaw('LOWER(name) like ?', ['%' . $busca . '%'])
                ->orWhereRaw('LOWER(cpf) like ?', ['%' . $busca . '%'])
                ->paginate(100);
        }

        if ($users->count() == 0) {
            return view('administrador.users', compact('users'))->with(['message' => 'Nenhum Resultado encontrado!']);
        }

        return view('administrador.users', compact('users'));
    }

    public function createUser()
    {
        $estados = EstadoBrasileiro::options();

        return view('administrador.cadastrarUsuario', ['estados' => $estados]);
    }

    public function criarUsuario(StoreUserRequest $request)
    {

        $payload = $request->payload();

        if (empty($payload['endereco'])) {
            $endereco_id = Endereco::create($payload['endereco'])->id;
        }

        $data = [
            ...$payload['user'],
            'usuarioTemp' => null,
        ];

        if (isset($endereco_id)) {
            $payload['enderecoId'] = $endereco_id;
        }
        if ($request->filled('especialidade')) {
            $data['especProfissional'] = $request->input('especialidade');
        }

        $user = User::create($data);

        if (empty($payload['perfilIdentitario'])) {
            PerfilIdentitario::create([...$payload['perfilIdentitario'], 'user_id' => $user->id]);

        }

        app()->setLocale('pt-BR');
        return redirect()->route('admin.users')->with(['success' => 'Usuário atualizado com sucesso!']);

    }
}
