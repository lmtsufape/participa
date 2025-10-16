<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
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
        $eventos = Evento::all();

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

        return view('administrador.editUser', ['user' => $user, 'end' => $end]);
    }

    public function updateUser(Request $request, $id)
    {
        // dd($request->all());
        $this->authorize('isAdmin', Administrador::class);
        $user = User::find($id);

        if ($request->passaporte != null && $request->cpf != null) {
            $request->merge(['passaporte' => null]);
        }
        if ($user->usuarioTemp == true) {
            $validator = $request->validate([
                'name' => 'bail|required|string|max:255',
                'nomeSocial' => 'nullable|string|max:255',
                'cpf' => ($request->passaporte == null ? ['bail', 'required', 'cpf'] : 'nullable'),
                'passaporte' => ($request->cpf == null ? 'bail|required|max:10' : 'nullable'),
                'celular' => 'nullable|string|max:16',
                'instituicao' => ['nullable','string','max:255','regex:~^[\p{L}\p{M}0-9 .\-(){}\[\],;&@%*+=/\\\\|<>!?`\'"]*$~u'],
                'especialidade' => 'nullable|string',
                'rua' => 'nullable|string|max:255',
                'numero' => 'nullable|string',
                'bairro' => 'nullable|string|max:255',
                'cidade' => 'nullable|string|max:255',
                'complemento' => 'nullable|string|max:255',
                'uf' => 'nullable|string',
                'cep' => 'nullable|string',
                'password' => 'nullable|string|min:8|confirmed',
                // 'primeiraArea' => 'required|string',
            ]);

            // criar endereço apenas se houver dados suficientes
            $enderecoId = null;
            if (!empty($request->input('rua')) && !empty($request->input('cidade')) && !empty($request->input('uf'))) {
                $end = new Endereco();
                $end->rua = $request->input('rua');
                $end->numero = $request->input('numero');
                $end->bairro = $request->input('bairro');
                $end->cidade = $request->input('cidade');
                $end->complemento = $request->input('complemento');
                $end->uf = $request->input('uf');
                $end->cep = $request->input('cep');

                $end->save();
                $enderecoId = $end->id;
            }

            // Atualizar dados não preenchidos de User

            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
            $user->passaporte = $request->input('passaporte');
            $user->celular = $request->input('celular');
            $user->instituicao = $request->input('instituicao');

            $password = $request->input('password');
            if (empty($password)) {
                $password = $this->gerarSenhaAleatoria(8);
            }
            $user->password = bcrypt($password);
            if ($request->input('especialidade') != null) {
                $user->especProfissional = $request->input('especialidade');
            }
            $user->usuarioTemp = null;
            $user->enderecoId = $enderecoId;
            $user->email_verified_at = now();
            $user->save();

            if ($user->perfilIdentitario) {
                $user->perfilIdentitario->nomeSocial = $request->input('nomeSocial');
                $user->perfilIdentitario->save();
            } else {
                $perfilData = [
                    'nomeSocial' => $request->input('nomeSocial'),
                    'dataNascimento' => '2000-01-01',
                    'genero' => 'prefiro_nao_responder',
                    'outroGenero' => '',
                    'raca' => ['prefiro_nao_responder_raca'],
                    'outraRaca' => '',
                    'comunidadeTradicional' => 'false',
                    'nomeComunidadeTradicional' => null,
                    'lgbtqia' => 'false',
                    'deficienciaIdoso' => 'false',
                    'associadoAba' => 'false',
                    'receberInfoAba' => 'false',
                    'participacaoOrganizacao' => 'false',
                    'nomeOrganizacao' => null,
                    'necessidadesEspeciais' => ['nenhuma'],
                    'outraNecessidadeEspecial' => '',
                    'vinculoInstitucional' => '',
                ];

                $perfilIdentitario = new PerfilIdentitario();
                $perfilIdentitario->setAttributes($perfilData);
                $perfilIdentitario->userId = $user->id;
                $perfilIdentitario->save();
            }

            return redirect()->route('admin.users')->with(['message' => 'Cadastro completado com sucesso!']);
        } else {
            if ($request->passaporte != null && $request->cpf != null) {
                $request->merge(['passaporte' => null]);
            }
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'nomeSocial' => 'nullable|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'cpf' => ($request->passaporte == null ? ['bail', 'required', 'cpf', Rule::unique('users')->ignore($user->id)] : 'nullable'),
                'passaporte' => ($request->cpf == null && $request->cpf == null ? ['bail', 'required', 'max:10', Rule::unique('users')->ignore($user->id)] : ['nullable']),
                'celular' => 'nullable|string|max:16',
                'instituicao' => 'nullable|string| max:255',
                // 'especProfissional' => 'nullable|string',
                'rua' => 'nullable|string|max:255',
                'numero' => 'nullable|string',
                'bairro' => 'nullable|string|max:255',
                'complemento' => 'nullable|string|max:255',
                'cidade' => 'nullable|string|max:255',
                'uf' => 'nullable|string',
                'cep' => 'nullable|string',
            ]);

            // User

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->cpf = $request->input('cpf');
            $user->passaporte = $request->input('passaporte');
            $user->celular = $request->input('celular');
            $user->instituicao = $request->input('instituicao');
            $password = $request->input('password');
            if (empty($password)) {
                $password = $this->gerarSenhaAleatoria(8);
            } else {
                $request->validate(['password' => 'string|min:8|confirmed']);
            }
            $user->password = bcrypt($password);
            // $user->especProfissional = $request->input('especProfissional');
            $user->usuarioTemp = null;
            $user->update();

            // endereço
            if ($user->enderecoId) {
                $end = Endereco::find($user->enderecoId);
                if ($end) {
                    $end->rua = $request->input('rua');
                    $end->numero = $request->input('numero');
                    $end->bairro = $request->input('bairro');
                    $end->cidade = $request->input('cidade');
                    $end->complemento = $request->input('complemento');
                    $end->uf = $request->input('uf');
                    $end->cep = $request->input('cep');

                    $end->update();
                }
            }
            if ($user->perfilIdentitario) {
                $user->perfilIdentitario->nomeSocial = $request->input('nomeSocial');
                $user->perfilIdentitario->save();
            } else {
                $perfilData = [
                    'nomeSocial' => $request->input('nomeSocial'),
                    'dataNascimento' => '2000-01-01',
                    'genero' => 'prefiro_nao_responder',
                    'outroGenero' => '',
                    'raca' => ['prefiro_nao_responder_raca'],
                    'outraRaca' => '',
                    'comunidadeTradicional' => 'false',
                    'nomeComunidadeTradicional' => null,
                    'lgbtqia' => 'false',
                    'deficienciaIdoso' => 'false',
                    'associadoAba' => 'false',
                    'receberInfoAba' => 'false',
                    'participacaoOrganizacao' => 'false',
                    'nomeOrganizacao' => null,
                    'necessidadesEspeciais' => ['nenhuma'],
                    'outraNecessidadeEspecial' => '',
                    'vinculoInstitucional' => '',
                ];

                $perfilIdentitario = new PerfilIdentitario();
                $perfilIdentitario->setAttributes($perfilData);
                $perfilIdentitario->userId = $user->id;
                $perfilIdentitario->save();
            }

            // dd([$user,$end]);
            return redirect()->route('admin.users')->with(['message' => 'Usuário atualizado com sucesso!']);
        }

        return redirect()->route('admin.users')->with(['message' => 'Atualizado com sucesso!']);
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

    public function criarUsuario(Request $request)
    {

        $request->merge([
            'email' => strtolower($request->email),
        ]);

        // $this->authorize('cadastrarUsuario'); remoção temporaria

        $users = User::orderBy('updated_at', 'ASC')->paginate(100);

        $validator = $this->validator($request);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $password = $request->input('password');
        if (empty($password)) {
            $password = $this->gerarSenhaAleatoria(8);
        }
        $user->password = bcrypt($password);

        $user->cpf = $request->input('cpf');
        $user->passaporte = $request->input('passaporte');
        $user->celular = $request->input('full_number');
        $user->instituicao = $request->input('instituicao');
        $user->email_verified_at = now();

        if ($request->input('rua') && $request->input('cep'))
        {
            $endereco = new Endereco($request->all());
            $endereco->save();

            $user->enderecoId = $endereco->id;
        }
        else
        {
            $user->enderecoId = null;
        }

        $user->save();

        // Criar perfil identitário
        if ($this->temDadosPerfilIdentitario($request->all())) {
            $perfilData = $this->formatarDadosPerfilIdentitario($request->all());

            $perfilIdentitario = new PerfilIdentitario();
            $perfilIdentitario->setAttributes($perfilData);
            $perfilIdentitario->userId = $user->id;
            $perfilIdentitario->save();
        }

        app()->setLocale('pt-BR');

        return redirect(route('inscricao.inscritos', ['evento' => 2]))->with(['message' => 'Usuário cadastrado com sucesso!']);
    }

    protected function validator(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'cpf' => ($request->input('passaporte') == null ? ['required', 'cpf'] : 'nullable'),
            'passaporte' => ($request->input('cpf') == null ? 'required|max:10' : 'nullable'),
            'celular' => ['nullable', 'string', 'max:20'],
            'instituicao' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ0-9\s\-\.\(\)\[\]\{\}\/\\,;&@#$%*+=|<>!?~`\'"]*$/'],
            'dataNascimento' => ['nullable', 'date'],
            'rua' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'uf' => ['nullable', 'string'],
            'cep' => ['nullable', 'string'],
            'complemento' => ['nullable', 'string'],
            // Campos do perfil identitário
            'genero' => ['nullable', 'string'],
            'raca' => ['nullable', 'array'],
            'comunidadeTradicional' => ['nullable', 'in:true,false'],
            'lgbtqia' => ['nullable', 'in:true,false'],
            'necessidadesEspeciais' => ['nullable', 'array'],
            'deficienciaIdoso' => ['nullable', 'in:true,false'],
            'associadoAba' => ['nullable', 'in:true,false'],
            'receberInfoAba' => ['nullable', 'in:true,false'],
            'participacaoOrganizacao' => ['nullable', 'in:true,false'],
            'vinculoInstitucional' => ['nullable', 'string', 'max:1000'],
        ]);
    }
    private function gerarSenhaAleatoria(int $length = 8): string
    {
        $chars = '0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $result;
    }

    private function temDadosPerfilIdentitario(array $data): bool
    {
        $camposMinimos = ['dataNascimento', 'genero', 'raca'];

        foreach ($camposMinimos as $campo) {
            if (empty($data[$campo])) {
                return false;
            }
        }

        return true;
    }
    private function formatarDadosPerfilIdentitario(array $data): array
    {
        $perfilData = [
            'nomeSocial' => $data['nomeSocial'] ?? '',
            'dataNascimento' => $data['dataNascimento'],
            'genero' => $data['genero'] ?? 'não informado',
            'outroGenero' => $data['outroGenero'] ?? '',
            'raca' => is_array($data['raca']) ? $data['raca'] : [$data['raca']],
            'outraRaca' => $data['outraRaca'] ?? '',

            'comunidadeTradicional' => $data['comunidadeTradicional'] ? 'true' : 'false',
            'nomeComunidadeTradicional' => $data['nomeComunidadeTradicional'] ?? null,

            'lgbtqia' => $data['lgbtqia'] ? 'true' : 'false',
            'deficienciaIdoso' => $data['deficienciaIdoso'] ? 'true' : 'false',
            'associadoAba' => $data['associadoAba'] ? 'true' : 'false',
            'receberInfoAba' => $data['receberInfoAba'] ? 'true' : 'false',

            'participacaoOrganizacao' => $data['participacaoOrganizacao'] ? 'true' : 'false',
            'nomeOrganizacao' => $data['nomeOrganizacao'] ?? null,

            'necessidadesEspeciais' => $data['necessidadesEspeciais'] ?? ['nenhuma'],
            'outraNecessidadeEspecial' => $data['outraNecessidadeEspecial'] ?? '',
            'vinculoInstitucional' => $data['vinculoInstitucional'] ?? '',
        ];

        return $perfilData;
    }
}
