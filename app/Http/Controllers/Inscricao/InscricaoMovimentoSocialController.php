<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Mail\SolicitacaoMovimentoAprovada;
use App\Mail\SolicitacaoMovimentoRejeitada;
use App\Models\Inscricao\InscricaoMovimentoSocial;
use App\Models\Submissao\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class InscricaoMovimentoSocialController extends Controller
{
    public function store(Request $request, Evento $evento)
    {
        $request->validate([
            'comprovante' => 'required|file|mimes:pdf,jpeg,jpg,png|max:5120',
        ]);

        $user = auth()->user();

        if (InscricaoMovimentoSocial::where('user_id', $user->id)->where('evento_id', $evento->id)->exists() || $user->inscricaos()->where('evento_id', $evento->id)->exists()) {
            return redirect()->back()->with(['message' => 'Você já possui uma solicitação ou inscrição para este evento.', 'class' => 'danger']);
        }

        $path = $request->file('comprovante')->store("eventos/{$evento->id}/comprovantes_movimentos_sociais");

        InscricaoMovimentoSocial::create([
            'user_id' => $user->id,
            'evento_id' => $evento->id,
            'comprovante_path' => $path,
        ]);

        return redirect()->back()->with(['message' => 'Sua solicitação de inscrição por movimento social foi enviada com sucesso e aguarda aprovação.']);
    }

    public function listar(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $solicitacoes = InscricaoMovimentoSocial::where('evento_id', $evento->id)->with('user')->orderBy('created_at', 'desc')->get();

        return view('coordenador.inscricoes.inscritosMovimentosSociais', compact('evento', 'solicitacoes'));
    }

    public function aprovar(InscricaoMovimentoSocial $solicitacao)
    {
        $evento = $solicitacao->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $solicitacao->update(['status' => 'aprovado']);

        Mail::to($solicitacao->user->email)->send(new SolicitacaoMovimentoAprovada($solicitacao->user, $solicitacao->evento));
        return redirect()->back()->with(['message' => 'Solicitação de movimento social aprovada com sucesso.']);
    }

    public function rejeitar(InscricaoMovimentoSocial $solicitacao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $solicitacao->evento);
        $solicitacao->update(['status' => 'rejeitado']);

        Mail::to($solicitacao->user->email)->send(new SolicitacaoMovimentoRejeitada($solicitacao->user, $solicitacao->evento));

        return redirect()->back()->with(['message' => 'Solicitação de movimento social rejeitada com sucesso.']);
    }

    public function downloadComprovante(InscricaoMovimentoSocial $solicitacao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $solicitacao->evento);
        return Storage::download($solicitacao->comprovante_path);
    }
}