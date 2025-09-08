<?php

namespace App\Exports;

use App\Models\Submissao\Trabalho;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RelatorioGeralExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $eventoId;

    public function __construct(int $eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function query(): Builder
    {
        return Trabalho::where('eventoId', $this->eventoId)
            ->with([
                'modalidade',
                'area',
                'autor',
                'coautors.user',
                'atribuicoes.user',
                'arquivoCorrecao'
            ]);
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
            "Avaliação liberada para os autores",
            "Correção",
            "Lembrete enviado",
            "Validação pelo avaliador",
            "Aprovação final",
        ];
    }

    /**
     * Mapeia os dados de cada trabalho para as colunas correspondentes.
     *
     * @param mixed $trabalho O Eloquent Model do Trabalho.
     * @return array
     */

    public function map($trabalho): array
    {
        $nomesCoautores = [];
        $emailsCoautores = [];
        foreach ($trabalho->coautors as $coautor) {
            if ($coautor && $coautor->user) {
                $nomesCoautores[] = $coautor->user->name;
                $emailsCoautores[] = $coautor->user->email;
            }
        }

        $nomesAvaliadores = [];
        $emailsAvaliadores = [];
        $statusConvites = [];
        $statusAvaliacoesRastreio = [];
        $datasEnvioAvaliacao = [];
        $pareceresFinais = [];

        $avaliacaoLiberada = "Não";

        foreach ($trabalho->atribuicoes as $revisor) {
            if ($revisor && $revisor->user) {
                $nomesAvaliadores[] = $revisor->user->name;
                $emailsAvaliadores[] = $revisor->user->email;
            } else {
                $nomesAvaliadores[] = 'Avaliador não identificado';
                $emailsAvaliadores[] = '';
            }

            if (isset($revisor->pivot)) {
                if ($revisor->pivot->confirmacao === true) {
                    $statusConvites[] = "Aceito";
                } elseif ($revisor->pivot->confirmacao === false) {
                    $statusConvites[] = "Recusado";
                } else {
                    $statusConvites[] = "Pendente";
                }

                if ($revisor->pivot->parecer != 'processando') {
                    $statusAvaliacoesRastreio[] = "Avaliado";
                    $avaliacaoLiberada = "Sim";

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
                    }
                } else {
                    $statusAvaliacoesRastreio[] = "Processando";
                }
                $datasEnvioAvaliacao[] = optional($revisor->pivot->created_at)->format('d/m/Y H:i') ?? '';
            }
        }
        
        $statusAvaliacaoFinal = in_array("Processando", $statusAvaliacoesRastreio) ? "Processando" : "Avaliado";
        if (empty($statusAvaliacoesRastreio)) {
            $statusAvaliacaoFinal = "Sem avaliador";
        }

        $statusCorrecao = 'N/A';
        if ($trabalho->permite_correcao) {
            $statusCorrecao = $trabalho->arquivoCorrecao ? 'Trabalho revisado enviado' : 'Aguardando envio';
        }

        $validacaoAvaliador = '';
        switch ($trabalho->status) {
            case 'corrigido':
                $validacaoAvaliador = 'Finalizado: aprovado';
                break;
            case 'avaliado':
                $validacaoAvaliador = $trabalho->permite_correcao ? 'Em análise de correção' : 'Finalizado';
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
            optional($trabalho->modalidade)->nome ?? '', 
            $trabalho->titulo,
            optional($trabalho->autor)->name ?? '',     
            optional($trabalho->autor)->email ?? '',   
            implode('; ', array_unique($nomesCoautores)),
            implode('; ', array_unique($emailsCoautores)),
            optional($trabalho->area)->nome ?? '',       
            optional($trabalho->created_at)->format('d/m/Y H:i') ?? '', 
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