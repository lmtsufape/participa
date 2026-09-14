<?php

namespace App\Http\Controllers\Users;

use App\Enums\EstadoBrasileiro;
use App\Models\PerfilIdentitario;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Submissao\Area;
use App\Models\Submissao\Certificado;
use App\Models\Submissao\Endereco;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Form;
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
        $estados = EstadoBrasileiro::options();
        $areas = Area::orderBy('nome')->get();
        $perfilIdentitario = PerfilIdentitario::query()
            ->where('user_id', $user->id)
            ->first();

        return view('user.perfilUser', compact('user', 'end', 'areas', 'pais', 'perfilIdentitario', 'estados'));
    }

    public function editarPerfil(UpdateUserRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
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
                'usuarioTemp' => false,
            ];

            if ($request->filled('especialidade')) {
                $data['especProfissional'] = $request->input('especialidade');
            }

            $user->fill($data);
            $user->save();

            DB::commit();

            return back()->with('success', 'Perfil atualizado com sucesso! Todas as suas informações foram salvas corretamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o perfil. Por favor, tente novamente. Se o problema persistir, entre em contato com o suporte.');
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return back()->with('success', 'Senha alterada com sucesso.');
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


        if($temp){
            return redirect()->route('index')->with(['message' => 'Perfil atualizado com sucesso!']);
        }

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
        $form = Form::whereHas(
                'perguntas.respostasRevisores',
                function ($query) use ($revisor, $trabalho) {
                    $query->where('trabalho_id', $trabalho->id)
                        ->where('revisor_id', $revisor->id);
                }
            )
            ->with([
                'perguntas.respostasPadrao.opcoes',
                'perguntas.respostasPadrao.paragrafo',

                'perguntas.respostasRevisores' => function ($query) use ($revisor, $trabalho) {
                    $query->where('trabalho_id', $trabalho->id)
                        ->where('revisor_id', $revisor->id)
                        ->with([
                            'opcoes',
                            'paragrafo'
                        ]);
                }
            ])
            ->firstOrFail();
        $arquivoAvaliacao = $trabalho->arquivoAvaliacao()->where('revisorId', $revisor->id)->first();
        if ($arquivoAvaliacao == null) {
            $permissoes_revisao = Revisor::where([['user_id', $revisor->user_id], ['evento_id', $evento->id]])->get()->map->only(['id']);
            $arquivoAvaliacao = $trabalho->arquivoAvaliacao()->whereIn('revisorId', $permissoes_revisao)->first();
        }


        return view('avaliacoes.show', compact('evento', 'modalidade', 'trabalho', 'revisorUser', 'respostas', 'form', 'revisor', 'arquivoAvaliacao'));
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
        $user = User::doesntHave('administrador')->findOrFail($user_id);
        $this->authorize('delete', $user);
        if($user->trabalho()->exists()){
            return redirect()->back()->with('fail', 'Usuário possui trabalhos vinculados!');

        }
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Usuário excluído com sucesso!');
    }

}
