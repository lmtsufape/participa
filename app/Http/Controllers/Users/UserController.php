<?php

namespace App\Http\Controllers\Users;

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
    //
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

        return view('user.perfilUser', compact('user', 'end', 'areas', 'pais'));
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
        $validations = [
            'name' => 'required|string|max:255',
            'cpf' => ($request->passaporte == null && $request->cnpj == null ? ['bail', 'required', 'cpf'] : 'nullable'),
            'cnpj' => ($request->passaporte == null && $request->cpf == null ? ['bail', 'required'] : 'nullable'),
            'passaporte' => ($request->cpf == null && $request->cnpj == null ? ['bail', 'required', 'max:10'] : ['nullable']),
            'celular' => 'required|string|max:20',
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
        if ($user->usuarioTemp) {
            $validations['password'] = 'required|string|min:8|confirmed';
            $validations['email'] = '';
        }
        $validator = $request->validate($validations);

        if ($request->senha_atual != null) {
            if (! (Hash::check($request->senha_atual, $user->password))) {
                return redirect()->back()->withErrors(['senha_atual' => 'A senha digitada não correspondente a senha cadastrada.'])->withInput($validator);
            }

            if (! ($request->password != null)) {
                return redirect()->back()->withErrors(['password' => 'Digite a nova senha.'])->withInput($validator);
            }

            if (! ($request->input('password-confirm') != null)) {
                return redirect()->back()->withErrors(['password-confirm' => 'Digite a confirmação da senha.'])->withInput($validator);
            }

            if (! ($request->password == $request->input('password-confirm'))) {
                return redirect()->back()->withErrors(['password' => 'A confirmação não confere com a nova senha.'])->withInput($validator);
            }

            $password = Hash::make($request->password);

            $user->password = $password;
        }

        if ($user->usuarioTemp) {
            $user->password = Hash::make($request->password);
        }

        if ($user->email != $request->email && !$user->usuarioTemp) {
            $check_user_email = User::where('email', $request->email)->first();
            if ($check_user_email == null) {
                $user->email = $request->email;
                $user->email_verified_at = null;
            } else {
                return redirect()->back()->withErrors(['email' => 'Já existe uma conta registrada com esse e-mail.'])->withInput($validator);
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
        app()->setLocale('pt-BR');

        return back()->with(['message' => 'Atualizado com sucesso!']);
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
        $user = User::where('email', $request->email)->first('name');

        return response()->json([
            'user' => [
                $user,
            ],
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
