<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\InscricaoPCD;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Submissao\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitacaoPCDRejeitada;
use App\Mail\SolicitacaoPCDAprovada;

class InscricaoPCDController extends Controller
{
    public function store(Request $request, Evento $evento)
    {
        if (!$evento->inscricaoPCDHabilitada()) {
            return redirect()->back()->with(['message' => 'A solicitação de inscrição PCD não está habilitada para este evento.', 'class' => 'danger']);
        }

        $request->validate([
            'comprovante' => 'required|file|mimes:pdf,jpeg,jpg,png|max:5120', // 5MB
        ]);

        $user = auth()->user();

        if (InscricaoPCD::where('user_id', $user->id)->where('evento_id', $evento->id)->exists() || $user->inscricaos()->where('evento_id', $evento->id)->exists()) {
            return redirect()->back()->with(['message' => 'Você já possui uma solicitação ou inscrição para este evento.', 'class' => 'danger']);
        }

        $path = $request->file('comprovante')->store("eventos/{$evento->id}/comprovantes_pcd");

        InscricaoPCD::create([
            'user_id' => $user->id,
            'evento_id' => $evento->id,
            'comprovante_path' => $path,
        ]);

        return redirect()->back()->with(['message' => 'Sua solicitação de inscrição PCD foi enviada com sucesso e aguarda aprovação do coordenador.']);
    }

    public function listar(Request $request)
    {
        $evento = Evento::findOrFail($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        if (!$evento->inscricaoPCDHabilitada()) {
            return redirect()
                ->route('coord.detalhesEvento', ['eventoId' => $evento->id])
                ->with(['message' => 'O módulo de inscrição PCD está desativado para este evento.', 'class' => 'warning']);
        }

        $solicitacoes = InscricaoPCD::where('evento_id', $evento->id)->with('user')->orderBy('created_at', 'desc')->get();

        return view('coordenador.inscricoes.inscritosPCD', compact('evento', 'solicitacoes'));
    }

    public function aprovar(InscricaoPCD $solicitacao)
    {
        $evento = $solicitacao->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        abort_unless($evento->inscricaoPCDHabilitada(), 404);
        
        $solicitacao->update(['status' => 'aprovado']);

        Mail::to($solicitacao->user->email)->send(new SolicitacaoPCDAprovada($solicitacao->user, $solicitacao->evento));

        return redirect()->back()->with(['message' => 'Solicitação aprovada e inscrição criada com sucesso.']);
    }


    public function rejeitar(InscricaoPCD $solicitacao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $solicitacao->evento);
        abort_unless($solicitacao->evento->inscricaoPCDHabilitada(), 404);

        $solicitacao->update(['status' => 'rejeitado']);
        
        Mail::to($solicitacao->user->email)->send(new SolicitacaoPCDRejeitada($solicitacao->user, $solicitacao->evento));

        return redirect()->back()->with(['message' => 'Solicitação rejeitada com sucesso.']);
    }

    public function downloadComprovante(InscricaoPCD $solicitacao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $solicitacao->evento);
        abort_unless($solicitacao->evento->inscricaoPCDHabilitada(), 404);

        return Storage::download($solicitacao->comprovante_path);
    }
}
