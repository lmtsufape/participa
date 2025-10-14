<?php

namespace App\Http\Controllers\Users;

use App\Models\PerfilIdentitario;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Submissao\Area;
use App\Models\Submissao\Certificado;
use App\Models\Submissao\Endereco;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Modalidade;
use App\Models\Submissao\Palestra;
use App\Models\Submissao\TipoComissao;
use App\Models\Submissao\Trabalho;
use App\Models\Users\Coautor;
use App\Models\Users\Revisor;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function perfil($pais = null)
    {
        $user = User::find(Auth::user()->id);
        $end = $user->endereco;
        if ($pais) {
            if ($pais != 'brasil') {
                app()->setLocale('en');
            } else {
                app()->setLocale('pt-BR');
            }
        } elseif ($end && $end->pais != 'brasil') {
            app()->setLocale('en');
        } else {
            app()->setLocale('pt-BR');
        }
        $areas = Area::orderBy('nome')->get();
        $perfilIdentitario = PerfilIdentitario::query()
            ->where('userId', $user->id)
            ->first();

        if ($user->usuarioTemp) {
            app()->setLocale('pt-BR');
        }

        return view('user.perfilUser', compact('user', 'end', 'areas', 'pais', 'perfilIdentitario'));
    }

    public function editarPerfil(Request $request)
    {


        if ($request->passaporte != null && $request->cpf != null ||
            $request->passaporte != null && $request->cnpj != null ) {

            $request->merge(['passaporte' => null]);
        }

        if ($request->cpf != null && $request->cnpj != null ) {

            $request->merge(['cpf' => null]);
        }

        $user = User::find($request->id);

        $temp = $user->usuarioTemp;


        $validations = [
            'name' => 'required|string|max:255',
            'documentos' => ['required', 'in:cpf,cnpj,passaporte'],
            'cpf' =>  ['exclude_unless:documentos,cpf', 'bail', 'required', 'cpf'],
            'cnpj' =>  ['exclude_unless:documentos,cnpj', 'bail', 'required', 'cnpj'],
            'passaporte' => ['exclude_unless:documentos,passaporte', 'bail', 'required'],
            'celular' => '|string|max:20',
            'instituicao' => 'required|string| max:255',
            'rua' => 'required|string|max:255',
            'numero' => 'required|string',
            'bairro' => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string',
            'cep' => 'required|string',
            'pais' => 'required',
            'email' => 'required|string|email|max:255',
            'senha_atual' => 'nullable|string|min:8',
            'password' => 'nullable|string|min:8',
            'password-confirm' => 'nullable|string|min:8',
        ];
        $data = $request->all();
        if ($data['pais'] == 'outro'){
            $validations['uf'] = ['nullable', 'string'];
            $validations['numero'] = ['nullable', 'string'];
            $validations['bairro'] = ['nullable', 'string'];
            $validations['cep'] = ['nullable', 'string'];
        }
        try {
            $validator = $request->validate($validations);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Por favor, corrija os erros no formulário antes de continuar.');
        }

        if ($request->senha_atual != null) {
            if (! (Hash::check($request->senha_atual, $user->password))) {
                return redirect()->back()
                    ->withErrors(['senha_atual' => 'A senha atual informada está incorreta. Verifique e tente novamente.'])
                    ->withInput($validator)
                    ->with('error', 'Erro na alteração de senha. Verifique os dados informados.');
            }

            if (! ($request->password != null)) {
                return redirect()->back()
                    ->withErrors(['password' => 'Digite a nova senha desejada.'])
                    ->withInput($validator)
                    ->with('error', 'Campo de nova senha é obrigatório.');
            }

            if (! ($request->input('password-confirm') != null)) {
                return redirect()->back()
                    ->withErrors(['password-confirm' => 'Digite a confirmação da nova senha.'])
                    ->withInput($validator)
                    ->with('error', 'Confirmação de senha é obrigatória.');
            }

            if (! ($request->password == $request->input('password-confirm'))) {
                return redirect()->back()
                    ->withErrors(['password' => 'A confirmação da senha não confere com a nova senha digitada.'])
                    ->withInput($validator)
                    ->with('error', 'As senhas não coincidem. Verifique e tente novamente.');
            }

            $password = Hash::make($request->password);
            $user->password = $password;
        }


        if ($user->email != $request->email && !$user->usuarioTemp) {
            $check_user_email = User::where('email', $request->email)->first();
            if ($check_user_email == null) {
                $user->email = $request->email;
                $user->email_verified_at = null;
            } else {
                return redirect()->back()
                    ->withErrors(['email' => 'Este e-mail já está sendo usado por outra conta. Use um e-mail diferente.'])
                    ->withInput($validator)
                    ->with('error', 'E-mail já cadastrado. Escolha outro endereço de e-mail.');
            }
        }

        $user->name = $request->input('name');
        $user->cpf = $request->input('cpf');
        $user->cnpj = $request->input('cnpj');
        $user->passaporte = $request->input('passaporte');
        $user->celular = $request->input('full_number');
        $user->instituicao = $request->input('instituicao');
        if ($request->input('especialidade') != null) {
            $user->especProfissional = $request->input('especialidade');
        }
        try {
            $user->usuarioTemp = null;
            $user->update();

            if ($user->enderecoId == null) {
                $end = new Endereco($request->all());
                $end->save();
                $user->enderecoId = $end->id;
                $user->update();
            } else {
                $end = Endereco::find($user->enderecoId);
                $end->fill($validator);
                $end->update();
            }

            $perfilIdentitario = PerfilIdentitario::query()
                ->where('userId', $user->id)
                ->first();

            if ($perfilIdentitario == null) {
                $perfilIdentitario = new PerfilIdentitario();
                $perfilIdentitario->userId = $user->id;
                $perfilIdentitario->setAttributes($data);
                $perfilIdentitario->save();
            }
            else{
                $perfilIdentitario->editAttributes($data);
                $perfilIdentitario->save();
            }

            if($temp){
                return redirect()->route('index')->with('success', 'Perfil atualizado com sucesso! Seus dados foram salvos e você já pode participar dos eventos.');
            }

            return back()->with('success', 'Perfil atualizado com sucesso! Todas as suas informações foram salvas corretamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o perfil. Por favor, tente novamente. Se o problema persistir, entre em contato com o suporte.');
        }
    }

    public function meusCertificados()
    {
        $usuario = auth()->user();
        $tiposView = ['Apresentador', 'Comissão científica', 'Comissão organizadora', 'Revisor', 'Participante', 'Palestrante', 'Coordenador da comissao científica', 'Outras comissoes', 'Inscrito em uma atividade', 'Inscrito em evento'];
        $certificadosPorTipo = $usuario->certificados->groupBy('tipo');
        $tipos = array_flip(Certificado::TIPO_ENUM);
        $comissoes = TipoComissao::find($usuario->certificados->pluck('pivot.comissao_id'));
        $palestras = Palestra::find($usuario->certificados->pluck('pivot.palestra_id'));
        $trabalhos = Trabalho::find($usuario->certificados->pluck('pivot.trabalho_id'));

        return view('user.meusCertificados', compact('tiposView', 'usuario', 'certificadosPorTipo', 'tipos', 'comissoes', 'palestras', 'trabalhos'));
    }

    public function meusTrabalhos()
    {
        $agora = Carbon::now();
        $user = Auth::user();
        $trabalhos = Trabalho::where('autorId', $user->id)->where('status', '!=', 'arquivado')->get();
        $comoCoautor = Coautor::where('autorId', $user->id)->first();

        $trabalhosCoautor = collect();

        if ($comoCoautor != null) {
            $trabalhosC = $comoCoautor->trabalhos;
            foreach ($trabalhosC as $trab) {
                if ($trab->autorId != auth()->user()->id) {
                    $trabalhosCoautor->push($trab);
                }
            }
        }


        return view('user.meusTrabalhos', [
            'trabalhos' => $trabalhos,
            'trabalhosCoautor' => $trabalhosCoautor,
            'agora' => $agora,
        ]);
    }

    public function visualizarParecer(Request $request)
    {
        $trabalho = Trabalho::find($request->trabalhoId);
        $revisor = Revisor::find($request->revisorId);
        if (! $trabalho->getParecerAtribuicao($revisor->user) == 'encaminhado') {
            $this->authorize('permissaoVisualizarParecer', $trabalho);
        }
        $evento = Evento::find($request->eventoId);
        $modalidade = Modalidade::find($request->modalidadeId);
        $revisorUser = User::find($revisor->user_id);
        $respostas = collect();
        foreach ($modalidade->forms as $form) {
            foreach ($form->perguntas as $pergunta) {
                $respostas->push($pergunta->respostas->where('trabalho_id', $trabalho->id)->where('revisor_id', $revisor->id)->first());
            }
        }

        $arquivoAvaliacao = $trabalho->arquivoAvaliacao()->where('revisorId', $revisor->id)->first();
        if ($arquivoAvaliacao == null) {
            $permissoes_revisao = Revisor::where([['user_id', $revisor->user_id], ['evento_id', $evento->id]])->get()->map->only(['id']);
            $arquivoAvaliacao = $trabalho->arquivoAvaliacao()->whereIn('revisorId', $permissoes_revisao)->first();
        }


        return view('user.visualizarParecer', compact('evento', 'modalidade', 'trabalho', 'revisorUser', 'respostas', 'revisor', 'arquivoAvaliacao'));
    }

    public function searchUser(Request $request)
    {
        $user = null;

        if ($request->has('email') && !empty($request->email)) {
            $user = User::where('email', $request->email)->first(['name', 'email']);
        }
        if ($request->has('cpf') && !empty($request->cpf)) {
            $user = User::where('cpf', $request->cpf)->first(['name', 'cpf']);
        }

        return response()->json([
            'user' => [
                $user,
            ],
        ]);
    }

    public function searchUserInscricao(Request $request)
    {
        $user = null;
        $inscricaoFinalizada = null;

        if ($request->has('email') && !empty($request->email)) {
            $user = User::where('email', $request->email)->first(['id', 'name', 'email']);

            if($user && $request->has('evento_id')){
                $inscricao = $user->inscricaos()->where('evento_id', $request->evento_id)->first();
                $inscricaoFinalizada = $inscricao ? $inscricao->finalizada : null;
            }

            $response = [
                'user' => [$user],
                'inscricaoFinalizada' => $inscricaoFinalizada
            ];
            return response()->json($response);
        }

        if ($request->has('cpf') && !empty($request->cpf)) {
            $user = User::where('cpf', $request->cpf)->first(['id', 'name', 'cpf']);
            if($user && $request->has('evento_id')){
                $inscricao = $user->inscricaos()->where('evento_id', $request->evento_id)->first();
                $inscricaoFinalizada = $inscricao ? $inscricao->finalizada : null;
            }

            $response = [
                'user' => [$user],
                'inscricaoFinalizada' => $inscricaoFinalizada
            ];
            return response()->json($response);
        }

        return response()->json([
            'user' => [null],
            'inscricaoFinalizada' => null
        ]);
    }

    public function areaParticipante(){
        $user = Auth::user();

        $eventos = Evento::whereHas('inscricaos', function($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        $eventos = $eventos->paginate(9);

        return view('user.areaParticipante', ['eventos' => $eventos]);

    }

    public function meusComprovantes(Request $request){
        $user = Auth::user();

        $eventos = Evento::whereHas('inscricaos', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('finalizada', true);
            });

        if ($request->filled('busca')) {
            $eventos->where('nome', 'ilike', '%' . $request->busca . '%');
        }

        if ($request->filled('ordenar')) {
            switch ($request->ordenar) {
                case 'nome':
                    $eventos->orderBy('nome');
                    break;
                case 'data':
                default:
                    $eventos->orderBy('dataFim', 'desc');
                    break;
            }
        } else {
            $eventos->orderBy('dataFim', 'desc');
        }

        $eventos = $eventos->paginate(9);

        return view('user.meusComprovantes', ['eventos' => $eventos]);
    }

    public function destroy($user_id)
    {
        $user = User::doesntHave('administradors')->findOrFail($user_id);
        $this->authorize('delete', $user);
        if($user->trabalho()->exists()){
            return redirect()->back()->with('fail', 'Usuário possui trabalhos vinculados!');

        }
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Usuário excluído com sucesso!');
    }

}
