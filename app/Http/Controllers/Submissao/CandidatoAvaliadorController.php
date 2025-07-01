<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Requests\CandidatoAvaliadorRequest;
use App\Models\Submissao\Area;
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
use App\Exports\CandidatosAvaliadoresExport;
use Maatwebsite\Excel\Facades\Excel;

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
            //log the error for debugging
            \Log::error('Erro ao cadastrar candidato avaliador: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'evento_id' => $eventoId,
                'areas' => $areas,
                'link_lattes' => $link_lattes,
                'resumo_lattes' => $resumo_lattes,
                'ja_avaliou' => $jaAvaliou,
                'dispIdiomas' => $dispIdiomas,
            ]);
            return redirect()->back()->with(['message' => 'Houve um erro durante sua candidatura!', 'class' => 'danger']);
        }
    }

    public function listarCandidatos($eventoId)
    {
        $evento = Evento::findOrFail($eventoId);

        $todos = CandidatoAvaliador::with(['user','area'])
            ->where('evento_id', $evento->id)
            ->get();


        $statusPorUsuarioEixo = [];
        foreach ($todos as $item) {
            $statusPorUsuarioEixo[$item->user_id][$item->area->nome] = [
                'aprovado'   => $item->aprovado,
                'em_analise' => $item->em_analise,
            ];
        }


        $candidaturas = $todos
            ->groupBy('user_id')
            ->map(function($group) {
                $first = $group->first();
                return (object)[
                    'id'            => $first->id,
                    'user'          => $first->user,
                    'aprovado'      => $first->aprovado,
                    'em_analise'    => $first->em_analise,
                    'lattes_link'   => $first->link_lattes,
                    'resumo_lattes' => $first->resumo_lattes,
                    'eixos'         => $group->pluck('area.nome')->toArray(),
                    'avaliou_antes' => $first->ja_avaliou_cba ? 'sim' : 'nao',
                    'idiomas'       => explode(',', $first->disponibilidade_idiomas),
                ];
            })
            ->values();

        return view(
            'coordenador.revisores.listarCandidatos',
            compact('evento','candidaturas','statusPorUsuarioEixo')
        );
    }

    public function aprovar(Request $request)
    {

        $eventoId = $request->input('evento_id');
        $userId   = $request->input('user_id');
        $eixo   = $request->input('eixo');
        $area = Area::where('nome', $eixo)->firstOrFail();
        CandidatoAvaliador::where('area_id', $area->id)
        ->where('user_id',   $userId)
        ->update([
            'aprovado'   => true,
            'em_analise' => false,
        ]);

        $user   = User::findOrFail($userId);
        $evento = Evento::findOrFail($eventoId);


        $modalidades = $evento->modalidades()->pluck('id')->toArray();


        $jaRevisor = $user->revisor()
            ->where('evento_id', $eventoId)
            ->where('areaId',     $area->id)
            ->exists();

        if (! $jaRevisor) {
            foreach ($modalidades as $modalidadeId) {
                Revisor::create([
                    'user_id'              => $user->id,
                    'evento_id'            => $evento->id,
                    'areaId'               => $area->id,
                    'modalidadeId'         => $modalidadeId,
                    'trabalhosCorrigidos'  => 0,
                    'correcoesEmAndamento' => 0,
                ]);
            }
        } else {
            return redirect()->back()
                ->withErrors(['errorRevisor' => 'Você já é revisor desta área para o evento.']);
        }

        // --- Envio de e-mail e retorno ---
        Mail::to($user->email)
            ->send(new EmailRespostaAvaliador($user, $evento, 'aprovada', $area->nome));

        return redirect()->back()
            ->with('sucesso', 'Candidatura aprovada e candidato notificado.');
    }

    public function exportar(Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        return Excel::download(new CandidatosAvaliadoresExport($evento->id), 'candidatos-avaliadores-'.$evento->nome.'.xlsx');
    }

    public function rejeitar(Request $request)
    {
        $eventoId = $request->input('evento_id');
        $userId   = $request->input('user_id');
        $eixo     = $request->input('eixo');
        $area     = Area::where('nome', $eixo)->firstOrFail();

        CandidatoAvaliador::where('evento_id', $eventoId)
            ->where('user_id',    $userId)
            ->where('area_id',    $area->id)
            ->update([
                'aprovado'   => false,
                'em_analise' => false,
            ]);

        $usuario = User::findOrFail($userId);
        $evento  = Evento::findOrFail($eventoId);

        Mail::to($usuario->email)
            ->send(new EmailRespostaAvaliador(
                $usuario,
                $evento,
                'rejeitada',
                $area->nome
            ));

        // 5) redireciona com feedback
        return redirect()->back()
            ->with('sucesso', 'Candidatura rejeitada e candidato notificado.');
    }
}
