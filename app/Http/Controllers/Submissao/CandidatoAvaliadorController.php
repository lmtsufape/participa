<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Requests\CandidatoAvaliadorRequest;
use App\Models\Submissao\Evento;
use App\Models\CandidatoAvaliador;
use App\Mail\EmailConviteAvaliador;
use App\Mail\EmailRespostaAvaliador;
use App\Http\Controllers\Controller;
use App\Models\Users\Revisor;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class CandidatoAvaliadorController extends Controller
{
    public function store(CandidatoAvaliadorRequest $request)
    {
        $user = Auth::user();
        $eventoId = $request->input('evento_id');
        $evento    = Evento::findOrFail($eventoId);
        $link_lattes = $request->input('lattes_link');
        $resumo_lattes = $request->input('lattes_resumo');
        $jaAvaliou   = $request->input('avaliou_antes') === 'sim';
        $idiomas     = $request->input('idiomas', []);
        $dispIdiomas = implode(',', $idiomas);

         $areas = $request->input('eixos', []);

        try {
            foreach ($areas as $area_id) {
                CandidatoAvaliador::create([
                    'user_id' => $user->id,
                    'evento_id' => $eventoId,
                    'link_lattes' => $link_lattes,
                    'resumo_lattes' => $resumo_lattes,
                    'ja_avaliou_cba' => $jaAvaliou,
                    'disponibilidade_idiomas' => $dispIdiomas,
                    'area_id' => $area_id,
                ]);
            }

            Mail::send(new EmailConviteAvaliador($user, $evento));

            return redirect()->route('evento.visualizar', ['id' => $eventoId])->with(['message' => 'Candidatura enviada com sucesso!', 'class' => 'success']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'Houve um erro durante sua candidatura!', 'class' => 'danger']);
        }
    }

    public function listarCandidatos($eventoId)
    {

        $evento = Evento::findOrFail($eventoId);

        $todosCandidatos = CandidatoAvaliador::with(['user', 'area'])
            ->where('evento_id', $evento->id)
            ->get();

        // agrupa por usuário e monta objeto com todos os eixos num array
        $candidaturas = $todosCandidatos
            ->groupBy('user_id')
            ->map(function($group) {
                $first = $group->first();
                return (object)[
                    'id'            => $first->id,
                    'user'          => $first->user,
                    'aprovado'      => $first->aprovado,
                    'em_analise'   => $first->em_analise,
                    'lattes_link'   => $first->link_lattes,
                    'resumo_lattes' => $first->resumo_lattes,
                    'eixos'         => $group->pluck('area.nome')->toArray(),
                    'avaliou_antes' => $first->ja_avaliou_cba ? 'sim' : 'nao',
                    'idiomas'       => explode(',', $first->disponibilidade_idiomas),
                ];
            })
            ->values();

        return view('coordenador.revisores.listarCandidatos', compact('evento', 'candidaturas'));
    }

    public function aprovar(Request $request)
    {
        $eventoId = $request->input('evento_id');
        $userId   = $request->input('user_id');
        CandidatoAvaliador::where('evento_id', $eventoId)
        ->where('user_id',   $userId)
        ->update([
            'aprovado'   => true,
            'em_analise' => false,
        ]);

        $user = User::find($request->user_id);
        $evento = Evento::find($request->evento_id);

         $areas = CandidatoAvaliador::where('evento_id', $eventoId)
            ->where('user_id', $userId)
            ->pluck('area_id')
            ->unique()
            ->toArray();

        $modalidades = $evento->modalidades()->pluck('id')->toArray();

        if ($user->revisor()->where('evento_id', $eventoId)->doesntExist()) {
            foreach ($areas as $areaId) {
                foreach ($modalidades as $modalidadeId) {
                    Revisor::create([
                        'user_id'               => $user->id,
                        'evento_id'             => $evento->id,
                        'areaId'                => $areaId,
                        'modalidadeId'          => $modalidadeId,
                        'trabalhosCorrigidos'   => 0,
                        'correcoesEmAndamento'  => 0,
                    ]);
                }
            }
        } else {
            return redirect()->back()->withErrors(['errorRevisor' => 'Esse revisor já está cadastrado para o evento.']);
        }

        $status  = 'aprovada';
        Mail::to($user->email)
        ->send(new EmailRespostaAvaliador(
            $user,
            $evento,
            $status
        ));

        return redirect()->back()
            ->with('sucesso', 'Candidatura aprovada e candidato notificado.');
    }

    public function rejeitar(Request $request)
    {
        $eventoId = $request->input('evento_id');
        $userId   = $request->input('user_id');
        CandidatoAvaliador::where('evento_id', $eventoId)
        ->where('user_id',   $userId)
        ->update([
            'aprovado'   => false,
            'em_analise' => false,
        ]);
        $usuario = User::find($request->user_id);
        $evento = Evento::find($request->evento_id);
        $status  = 'rejeitada';

        Mail::to($usuario->email)
        ->send(new EmailRespostaAvaliador(
            $usuario,
            $evento,
            $status
        ));

        return redirect()->back()
            ->with('sucesso', 'Candidatura rejeitada e candidato notificado.');
    }
}
