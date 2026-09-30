<?php

namespace App\Services;

use App\Models\Submissao\Modalidade;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EtapasModalidadeService
{
    public const VALIDACOES = ['corrigido', 'corrigido_parcialmente', 'nao_corrigido'];

    public function impacto(Modalidade $modalidade, string $etapa): array
    {
        $query = $modalidade->trabalho()->where(function ($query) use ($etapa, $modalidade) {
            if ($etapa === 'versao_final') {
                $query->whereHas('versoesFinais');
            } elseif ($etapa === 'correcao') {
                // Mesmos critérios de Trabalho::temCorrecaoSubmetida, incluindo o histórico textual.
                if ($modalidade->arquivo && ! $modalidade->texto) {
                    $query->whereHas('arquivoCorrecao');
                } else {
                    $query->whereNotNull('data_correcao_submetida')
                        ->orWhereIn('avaliado', self::VALIDACOES)
                        ->orWhere(function ($q) {
                            $q->where('permite_correcao', true)->whereColumn('updated_at', '>', 'created_at');
                        });
                }
            } elseif ($etapa === 'validacao') {
                $query->whereIn('avaliado', self::VALIDACOES);
            } else {
                $query->whereIn('avaliado', array_merge(['Avaliado', 'avaliado'], self::VALIDACOES))
                    ->orWhereHas('respostas', fn ($q) => $q->whereNotNull('revisor_id'))
                    ->orWhereHas('avaliacoes')
                    ->orWhereHas('pareceres', fn ($q) => $q->whereNotNull('revisorId'))
                    ->orWhereHas('arquivoAvaliacao')
                    ->orWhereHas('atribuicoes', fn ($q) => $q->whereIn('atribuicaos.parecer', ['avaliado', 'dado']));
            }
        });

        $trabalhos = $query->with(['autor' => fn ($q) => $q->withTrashed(), 'coautors.user' => fn ($q) => $q->withTrashed()])
            ->orderBy('trabalhos.id')->get()->map(fn ($trabalho) => [
                'id' => $trabalho->id,
                'titulo' => $trabalho->titulo,
                'autores' => collect([$trabalho->autor?->name])
                    ->merge($trabalho->coautors->map(fn ($coautor) => $coautor->user?->name))
                    ->filter()->unique()->values()->all(),
            ])->all();

        return [
            'quantidade' => count($trabalhos),
            'trabalhos' => $trabalhos,
            // Vincula a confirmação à modalidade, à etapa e à lista exibida.
            'confirmacao' => hash_hmac('sha256', json_encode([$modalidade->id, $etapa, $trabalhos]), config('app.key')),
        ];
    }

    public function confirmarDesativacao(Request $request, Modalidade $modalidade): void
    {
        foreach (['versao_final' => ['inicio_versao_final', 'fim_versao_final'], 'avaliacao' => ['inicioRevisao', 'fimRevisao'], 'correcao' => ['inicioCorrecao', 'fimCorrecao'], 'validacao' => ['inicioValidacao', 'fimValidacao']] as $etapa => $datas) {
            if ($request->boolean('habilitar_'.$etapa) || (! $modalidade->{$datas[0]} && ! $modalidade->{$datas[1]})) {
                continue;
            }
            $impacto = $this->impacto($modalidade, $etapa);
            if ($impacto['quantidade'] && ! hash_equals($impacto['confirmacao'], (string) $request->input('confirmar_'.$etapa, ''))) {
                throw ValidationException::withMessages([
                    'habilitar_'.$etapa => 'Confira os '.$impacto['quantidade'].' trabalhos com '.(['versao_final' => 'versão final', 'avaliacao' => 'avaliação', 'correcao' => 'correção', 'validacao' => 'validação'][$etapa]).' registrada e confirme a desativação. A lista pode ter sido atualizada.',
                ]);
            }
        }
    }

    public function normalizar(Request $request, ?string $id = null): void
    {
        $campo = 'fim_versao_final'.($id ?? '');
        $inicio = 'inicio_versao_final'.($id ?? '');
        $habilitada = $request->has('habilitar_versao_final')
            ? $request->boolean('habilitar_versao_final') : ($request->filled($campo) || $request->filled($inicio));
        $request->merge(['habilitar_versao_final' => (int) $habilitada]);
        if (! $habilitada) {
            $request->merge([$inicio => null, $campo => null]);
        }
        foreach (['avaliacao' => ['inicioRevisao', 'fimRevisao', 'inícioRevisão', 'fimRevisão'], 'correcao' => ['inicioCorrecao', 'fimCorrecao', 'inícioCorreção', 'fimCorreção'], 'validacao' => ['inicioValidacao', 'fimValidacao', 'inícioValidação', 'fimValidação']] as $etapa => $datas) {
            $inicio = $id === null ? $datas[0] : $datas[2].$id;
            $fim = $id === null ? $datas[1] : $datas[3].$id;
            // Compatibilidade com formulários antigos que ainda não enviam os controles.
            $habilitada = $request->has('habilitar_'.$etapa)
                ? $request->boolean('habilitar_'.$etapa)
                : ($request->filled($inicio) || $request->filled($fim));
            $request->merge(['habilitar_'.$etapa => $habilitada ? 1 : 0]);
            if (! $habilitada) {
                $request->merge([$inicio => null, $fim => null]);
                if ($etapa === 'avaliacao') {
                    $request->merge(['avaliacaoDuranteSubmissao' => false]);
                }
            }
        }
    }
}
