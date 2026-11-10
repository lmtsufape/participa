<?php

namespace App\Http\Controllers\Users;

use App\Enums\EstadoBrasileiro;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetUserPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\PerfilIdentitario;
use App\Models\Submissao\Endereco;
use App\Models\Submissao\Evento;
use App\Models\Users\Administrador;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

use function PHPUnit\Framework\callback;

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
        $eventos = QueryBuilder::for(Evento::class)
            ->allowedFilters(
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    $search = trim((string) $value);

                    if ($search === '') {
                        return;
                    }

                    $query->where(function (Builder $query) use ($search) {
                        $query->where('nome', 'ilike', "%{$search}%")
                            ->orWhere('descricao', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");

                        if (ctype_digit($search)) {
                            $query->orWhere('id', $search);
                        }
                    });
                }),


                AllowedFilter::exact('tipo'),

                AllowedFilter::exact('publicado'),

                AllowedFilter::callback('classificacao', function (Builder $query, $value) {
                    if ($value === 'principal') {
                        $query->whereNull('evento_pai_id');
                    }

                    if ($value === 'subevento') {
                        $query->whereNotNull('evento_pai_id');
                    }
                }),
            )
            ->latest()
            ->withCount([
                'inscricaos as inscricoes_finalizadas_count' => function ($query) {
                        $query->where('finalizada', true);
                    },
                'modalidades'
            ])->paginate(15)
            ->withQueryString();


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

        $users = QueryBuilder::for(User::class)
            ->allowedFilters(
                AllowedFilter::trashed(),

                AllowedFilter::callback('search', function ($query, $value) {
                    $value = trim($value);

                    if ($value === '') {
                        return;
                    }

                    $documento = preg_replace('/\D/', '', $value);

                    $query->where(function ($query) use ($value, $documento) {
                        $query->where('name', 'ILIKE', "%{$value}%")
                            ->orWhere('email', 'ILIKE', "%{$value}%")
                            ->orWhere('passaporte', 'ILIKE', "%{$value}%");

                        if ($documento !== '') {
                            $query->orWhereRaw(
                                "REGEXP_REPLACE(cpf, '[^0-9]', '', 'g') LIKE ?",
                                ["%{$documento}%"]
                            )->orWhereRaw(
                                "REGEXP_REPLACE(cnpj, '[^0-9]', '', 'g') LIKE ?",
                                ["%{$documento}%"]
                            );
                        }
                    });
                }),
            )
            ->orderBy('name')
            ->paginate(50)
            ->withQueryString();

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

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $payload = $request->payload();

            $enderecoData = $payload['endereco'] ?? [];

            $possuiDadosEndereco = collect($enderecoData)
                ->contains(fn ($valor) => $valor !== null && $valor !== '');

            if ($user->endereco) {
                $user->endereco->update($enderecoData);
            } elseif ($possuiDadosEndereco) {
                $endereco = Endereco::create($enderecoData);
                $user->enderecoId = $endereco->id;
            }

            $perfilIdentitarioData = $payload['perfilIdentitario'] ?? [];

            if (!empty($perfilIdentitarioData)) {
                if ($user->perfilIdentitario) {
                    $user->perfilIdentitario->update($perfilIdentitarioData);
                } else {
                    PerfilIdentitario::create([
                        ...$perfilIdentitarioData,
                        'user_id' => $user->id,
                    ]);
                }
            }

            $data = [
                ...$payload['user'],
                'usuarioTemp' => null,
            ];

            if ($request->filled('especialidade')) {
                $data['especProfissional'] = $request->input('especialidade');
            }

            $user->fill($data);
            $user->save();

            DB::commit();

            return redirect()
                ->route('admin.users')
                ->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Ocorreu um erro ao atualizar o usuário. Por favor, tente novamente.'
                );
        }
    }

    public function resetPassword(ResetUserPasswordRequest $request, User $user)
    {
        $user->update([
            'password' => bcrypt($request->validated('password')),
        ]);

        return back()->with(
            'success',
            'Senha redefinida com sucesso!'
        );
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
