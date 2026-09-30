<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Mail\EmailVersaoFinalTrabalho;
use App\Models\Submissao\Trabalho;
use App\Models\Submissao\VersaoFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VersaoFinalController extends Controller
{
    public function listar(Request $request, \App\Models\Submissao\Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordCientificaOrCoordEixo', $evento);
        $filtros = $request->validate([
            'id' => ['nullable', 'integer', 'min:1'],
            'titulo' => ['nullable', 'string', 'max:255'],
            'modalidade_id' => ['nullable', 'integer'],
            'eixo_id' => ['nullable', 'integer'],
        ]);
        $areas = $evento->areas()->orderBy('nome');
        $query = Trabalho::where('eventoId', $evento->id)->where('status', '!=', 'arquivado')
            ->whereHas('versoesFinais')->with(['autor', 'modalidade', 'area', 'versoesFinais']);
        if (! $request->user()->can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)) {
            $ids = \App\Models\Users\CoordEixoTematico::where('user_id', $request->user()->id)
                ->where('evento_id', $evento->id)->pluck('area_id');
            $query->whereIn('areaId', $ids);
            $areas->whereIn('id', $ids);
        }
        foreach (['id' => 'id', 'modalidade_id' => 'modalidadeId', 'eixo_id' => 'areaId'] as $filtro => $coluna) {
            if (! empty($filtros[$filtro])) {
                $query->where($coluna, $filtros[$filtro]);
            }
        }
        if (! empty($filtros['titulo'])) {
            $query->whereRaw('LOWER(titulo) LIKE ?', ['%'.mb_strtolower($filtros['titulo']).'%']);
        }

        return view('coordenador.trabalhos.listarVersoesFinais', [
            'evento' => $evento,
            'trabalhos' => $query->orderBy('titulo')->orderBy('id')->paginate(15)->withQueryString(),
            'modalidades' => $evento->modalidades()->orderBy('nome')->get(),
            'areas' => $areas->get(),
        ]);
    }

    public function index(Trabalho $trabalho)
    {
        $this->authorize('visualizarVersaoFinal', $trabalho);

        return view('trabalho.versao-final', [
            'trabalho' => $trabalho->load('modalidade', 'evento'),
            'versoes' => $trabalho->versoesFinais()->with('autorEnvio')->paginate(15),
        ]);
    }

    public function store(Request $request, Trabalho $trabalho)
    {
        $this->authorize('enviarVersaoFinal', $trabalho);
        $request->validate([
            'arquivo_versao_final' => ['required', 'file', 'mimes:pdf,docx,odt,rtf', 'extensions:pdf,docx,odt,rtf', 'max:5120'],
        ], [], ['arquivo_versao_final' => 'arquivo da versão final']);

        $arquivo = $request->file('arquivo_versao_final');
        $caminho = $arquivo->store('versoes-finais/'.$trabalho->eventoId.'/'.$trabalho->id, 'local');
        abort_unless($caminho, 500, 'Não foi possível salvar o arquivo. Tente novamente.');
        try {
            DB::transaction(function () use ($request, $trabalho, $arquivo, $caminho) {
                // Serializa envios do mesmo trabalho e reconsulta a existência de versão final.
                // Uma segunda requisição é recusada após aguardar a primeira transação.
                $atual = Trabalho::whereKey($trabalho->id)->lockForUpdate()->firstOrFail();
                $this->authorize('enviarVersaoFinal', $atual);
                $atual->versoesFinais()->create([
                    'user_id' => $request->user()->id,
                    'caminho' => $caminho,
                    'nome_original' => Str::limit(basename(str_replace('\\', '/', $arquivo->getClientOriginalName())), 240, ''),
                ]);
                // Não altera updated_at nem estados do trabalho (usados no histórico de correção).
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($caminho);
            throw $exception;
        }

        $trabalho->load('autor', 'atribuicoes.user', 'evento');
        $destinatarios = collect([$trabalho->autor])->merge($trabalho->atribuicoes->pluck('user'))
            ->filter(fn ($user) => $user && $user->email)
            ->unique(fn ($user) => strtolower($user->email));
        foreach ($destinatarios as $destinatario) {
            try {
                Mail::to($destinatario->email)->send(new EmailVersaoFinalTrabalho($trabalho, $destinatario->name ?? 'Participante'));
            } catch (\Throwable $exception) {
                Log::warning('Falha ao notificar envio da versão final.', [
                    'trabalho_id' => $trabalho->id, 'user_id' => $destinatario->id, 'exception' => get_class($exception),
                ]);
            }
        }

        return redirect()->route('trabalho.versao-final.index', $trabalho)
            ->with('success', 'Versão final enviada com sucesso!');
    }

    public function download(Trabalho $trabalho, VersaoFinal $versaoFinal)
    {
        $this->authorize('visualizarVersaoFinal', $trabalho);
        abort_unless((int) $versaoFinal->trabalho_id === (int) $trabalho->id, 404);
        abort_unless(Storage::disk('local')->exists($versaoFinal->caminho), 404);

        return Storage::disk('local')->download($versaoFinal->caminho,
            'versao-final-'.$trabalho->id.'-'.$versaoFinal->id.'.'.pathinfo($versaoFinal->caminho, PATHINFO_EXTENSION));
    }
}
