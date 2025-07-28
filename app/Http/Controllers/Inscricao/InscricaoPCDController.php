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

class InscricaoPCDController extends Controller
{
    public function store(Request $request, Evento $evento)
    {
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
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $solicitacoes = InscricaoPCD::where('evento_id', $evento->id)->with('user')->orderBy('created_at', 'desc')->get();

        return view('coordenador.inscricoes.inscritosPCD', compact('evento', 'solicitacoes'));
    }

    public function aprovar(InscricaoPCD $solicitacao)
    {
        $evento = $solicitacao->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $todasCategorias = $evento->categoriasParticipantes;

        $categoriaPCD = null;
        foreach ($todasCategorias as $categoria) {
            if (strtolower(trim($categoria->nome)) === 'pessoa com deficiência (pcd)') {
                $categoriaPCD = $categoria;
                break;
            }
        }

        if (!$categoriaPCD) {
            return redirect()->back()->with([
                'message' => 'ERRO CRÍTICO: A categoria "Pessoa com Deficiência (PCD)" não foi encontrada. Verifique o nome na base de dados.', 
                'class' => 'danger'
            ]);
        }
        
        $solicitacao->update(['status' => 'aprovado']);

        $solicitacao->user->inscricaos()->create([
            'evento_id' => $evento->id,
            'categoria_participante_id' => $categoriaPCD->id,
            'finalizada' => true,
        ]);

        return redirect()->back()->with(['message' => 'Solicitação aprovada e inscrição criada com sucesso.']);
    }


    public function rejeitar(InscricaoPCD $solicitacao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $solicitacao->evento);
        $solicitacao->update(['status' => 'rejeitado']);
        
        Mail::to($solicitacao->user->email)->send(new SolicitacaoPCDRejeitada($solicitacao->user, $solicitacao->evento));

        return redirect()->back()->with(['message' => 'Solicitação rejeitada com sucesso.']);
    }

    public function downloadComprovante(InscricaoPCD $solicitacao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $solicitacao->evento);
        return Storage::download($solicitacao->comprovante_path);
    }
}
