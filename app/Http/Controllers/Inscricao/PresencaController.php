<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\Inscricao;
use App\Models\Submissao\Evento;
use Illuminate\Http\Request;

class PresencaController extends Controller
{
    public function index(Request $request, Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $data = $request->validate([
            'busca' => ['nullable', 'string', 'max:255'],
            'presenca' => ['nullable', 'in:presente,pendente'],
        ]);
        $query = Inscricao::where('evento_id', $evento->id)->whereHas('user')->with('user');
        $busca = trim($data['busca'] ?? '');
        if ($busca !== '') {
            $cpf = preg_replace('/\D/', '', $busca);
            $query->whereHas('user', function ($q) use ($busca, $cpf) {
                $q->where(function ($q) use ($busca, $cpf) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($busca).'%'])
                        ->orWhereRaw('LOWER(email) LIKE ?', ['%'.mb_strtolower($busca).'%']);
                    if ($cpf !== '' && preg_match('/^[\d.\-\s]+$/', $busca)) {
                        $q->orWhereRaw("REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), ' ', '') LIKE ?", ['%'.$cpf.'%']);
                    }
                });
            });
        }
        if (($data['presenca'] ?? '') === 'presente') {
            $query->where('is_presente', true);
        } elseif (($data['presenca'] ?? '') === 'pendente') {
            $query->where(fn ($q) => $q->where('is_presente', false)->orWhereNull('is_presente'));
        }

        return view('coordenador.inscricoes.presencas', [
            'evento' => $evento,
            'inscricoes' => $query->orderBy('user_id')->orderBy('id')->paginate(30)->withQueryString(),
        ]);
    }

    public function update(Request $request, Evento $evento, Inscricao $inscricao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        abort_unless((int) $inscricao->evento_id === (int) $evento->id, 404);
        $data = $request->validate(['is_presente' => ['required', 'boolean']]);
        abort_unless($inscricao->finalizada && $inscricao->user()->exists(), 422, 'A inscrição precisa estar confirmada para atualizar a presença.');
        // Atualiza somente esta inscrição, preservando marcações feitas em outras páginas ou por outros operadores.
        $inscricao->update(['is_presente' => (bool) $data['is_presente']]);

        return response()->json(['is_presente' => (bool) $inscricao->is_presente]);
    }
}
