<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Mail\SolicitacaoEstudanteAprovada;
use App\Models\Inscricao\InscricaoEstudante;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Submissao\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitacaoEstudanteRejeitada;

class InscricaoEstudanteController extends Controller
{
    public function store(Request $request, Evento $evento)
    {

        $request->validate([
            'comprovante' => 'required|file|mimes:pdf,jpeg,jpg,png|max:5120', // 5MB
        ]);

        $user = auth()->user();

        if (InscricaoEstudante::where('user_id', $user->id)->where('evento_id', $evento->id)->exists() || $user->inscricaos()->where('evento_id', $evento->id)->exists()) {
            return redirect()->back()->with(['message' => 'Você já possui uma solicitação ou inscrição para este evento.', 'class' => 'danger']);
        }

        $path = $request->file('comprovante')->store("eventos/{$evento->id}/comprovantes_estudantes");

        InscricaoEstudante::create([
            'user_id' => $user->id,
            'evento_id' => $evento->id,
            'comprovante_path' => $path,
        ]);

        return redirect()->back()->with(['message' => 'Sua solicitação de inscrição de estudante foi enviada com sucesso e aguarda aprovação do coordenador.']);
    }

    public function listar(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $solicitacoes = InscricaoEstudante::where('evento_id', $evento->id)->with('user')->orderBy('created_at', 'desc')->get();

        return view('coordenador.inscricoes.inscritosEstudantes', compact('evento', 'solicitacoes'));
    }

    public function aprovar(InscricaoEstudante $solicitacao)
    {
        $evento = $solicitacao->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $todasCategorias = $evento->categoriasParticipantes;


        $solicitacao->update(['status' => 'aprovado']);

        Mail::to($solicitacao->user->email)->send(new SolicitacaoEstudanteAprovada($solicitacao->user, $solicitacao->evento));
        return redirect()->back()->with(['message' => 'Solicitação aprovada com sucesso.']);
    }


    public function rejeitar(InscricaoEstudante $solicitacao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $solicitacao->evento);
        $solicitacao->update(['status' => 'rejeitado']);

        Mail::to($solicitacao->user->email)->send(new SolicitacaoEstudanteRejeitada($solicitacao->user, $solicitacao->evento));

        return redirect()->back()->with(['message' => 'Solicitação rejeitada com sucesso.']);
    }

    public function downloadComprovante(InscricaoEstudante $solicitacao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $solicitacao->evento);
        return Storage::download($solicitacao->comprovante_path);
    }
}
