<?php

namespace App\Exports;

use App\Models\Submissao\Trabalho;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class RelatorioGeralExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $eventoId;

    public function __construct(int $eventoId)
    {
        $this->eventoId = $eventoId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Trabalho::where('eventoId', $this->eventoId)
            ->with([
                'modalidade',
                'area',
                'autor',
                'coautors.user',
                'atribuicoes.user',
                'arquivoCorrecao'
            ])
            ->get();
    }

    /**
     * Define os cabeçalhos das colunas com base no arquivo de exemplo e correção solicitada.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            "ID",
            "Tipo de Submissão",
            "Título",
            "Nome do autor",
            "Email do autor",
            "Nome do(s) co-autor(es)",
            "e-mail dos coautores",
            "Área Temática",
            "Data de submissão",
            "Nome do(s) avaliador(es)",
            "Email do(s) avaliador(es)",
            "Status do convite de avaliação",
            "Status da avaliação",
            "Data de envio para avaliação",
            "Parecer do(s) avaliador(es)",
            "Avaliação liberada para os autores", // Coluna adicionada conforme solicitação
            "Correção",
            "Lembrete enviado",
            "Validação pelo avaliador",
            "Aprovação final",
        ];
    }

    /**
     * Mapeia os dados de cada trabalho para as colunas correspondentes.
     *
     * @param mixed $trabalho
     * @return array
     */
    public function map($trabalho): array
    {
        // --- Processamento de Coautores ---
        $nomesCoautores = [];
        $emailsCoautores = [];
        foreach ($trabalho->coautors as $coautor) {
            if ($coautor->user) {
                $nomesCoautores[] = $coautor->user->name;
                $emailsCoautores[] = $coautor->user->email;
            }
        }

        // --- Processamento de Avaliadores e Avaliações ---
        $nomesAvaliadores = [];
        $emailsAvaliadores = [];
        $statusConvites = [];
        $statusAvaliacoesRastreio = []; // Para controle interno
        $datasEnvioAvaliacao = [];
        $pareceresFinais = []; // Array para armazenar apenas os pareceres finais

        $avaliacaoLiberada = "Não"; // Valor padrão para a nova coluna

        foreach ($trabalho->atribuicoes as $revisor) {
            if ($revisor->user) {
                $nomesAvaliadores[] = $revisor->user->name;
                $emailsAvaliadores[] = $revisor->user->email;
            } else {
                $nomesAvaliadores[] = 'Avaliador não identificado';
                $emailsAvaliadores[] = '';
            }

            // Status do Convite
            if ($revisor->pivot->confirmacao === true) {
                $statusConvites[] = "Aceito";
            } elseif ($revisor->pivot->confirmacao === false) {
                $statusConvites[] = "Recusado";
            } else {
                $statusConvites[] = "Pendente";
            }

            // Status da Avaliação e Parecer Final (Ajuste conforme feedback)
            if ($revisor->pivot->parecer != 'processando') {
                $statusAvaliacoesRastreio[] = "Avaliado";
                $avaliacaoLiberada = "Sim"; // Se qualquer avaliação estiver completa, libera para o autor

                switch ($revisor->pivot->parecer) {
                    case 'aprovado':
                        $pareceresFinais[] = 'Aceito na íntegra';
                        break;
                    case 'aprovado_com_correcoes':
                        $pareceresFinais[] = 'Aceito com correções';
                        break;
                    case 'reprovado':
                        $pareceresFinais[] = 'Não aceito';
                        break;
                    // Outros status de parecer finalizados (se houver) não serão incluídos
                    // para atender à restrição de "apenas esses 3".
                }
            } else {
                $statusAvaliacoesRastreio[] = "Processando";
            }
            $datasEnvioAvaliacao[] = $revisor->pivot->created_at ? $revisor->pivot->created_at->format('d/m/Y H:i') : '';
        }
        
        // Determina o status geral da avaliação para a coluna "Status da avaliação"
        $statusAvaliacaoFinal = in_array("Processando", $statusAvaliacoesRastreio) ? "Processando" : "Avaliado";
        if (empty($statusAvaliacoesRastreio)) {
            $statusAvaliacaoFinal = "Sem avaliador";
        }

        // --- Status da Correção ---
        $statusCorrecao = 'N/A';
        if ($trabalho->permite_correcao) {
            $statusCorrecao = $trabalho->arquivoCorrecao ? 'Trabalho revisado enviado' : 'Aguardando envio';
        }

        // --- Status de Validação e Aprovação Final ---
        $validacaoAvaliador = '';
        switch ($trabalho->status) {
            case 'corrigido':
                $validacaoAvaliador = 'Finalizado: aprovado';
                break;
            case 'avaliado':
                if ($trabalho->permite_correcao) {
                    $validacaoAvaliador = 'Em análise de correção';
                } else {
                    $validacaoAvaliador = 'Finalizado';
                }
                break;
            default:
                $validacaoAvaliador = 'Pendente';
        }

        $aprovacaoFinal = 'Em análise';
        if ($trabalho->aprovado === true) {
            $aprovacaoFinal = 'Aprovado';
        } elseif ($trabalho->aprovado === false) {
            $aprovacaoFinal = 'Reprovado';
        }

        return [
            $trabalho->id,
            $trabalho->modalidade->nome ?? '',
            $trabalho->titulo,
            $trabalho->autor->name ?? '',
            $trabalho->autor->email ?? '',
            implode('; ', array_unique($nomesCoautores)),
            implode('; ', array_unique($emailsCoautores)),
            $trabalho->area->nome ?? '',
            $trabalho->created_at ? $trabalho->created_at->format('d/m/Y H:i') : '',
            implode('; ', array_unique($nomesAvaliadores)),
            implode('; ', array_unique($emailsAvaliadores)),
            implode('; ', array_unique($statusConvites)),
            $statusAvaliacaoFinal,
            implode('; ', array_unique($datasEnvioAvaliacao)),
            implode('; ', array_unique($pareceresFinais)), 
            $avaliacaoLiberada, 
            $statusCorrecao,
            $trabalho->lembrete_enviado ? 'Sim' : 'Não',
            $validacaoAvaliador,
            $aprovacaoFinal,
        ];
    }
}