<?php

namespace App\Exports;

use App\Models\CandidatoAvaliador;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CandidatosAvaliadoresExport implements FromCollection, WithHeadings, WithMapping
{
    protected $eventoId;
    private $lastUserId = null;    // guarda o último user_id processado

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function collection()
    {
        return CandidatoAvaliador::with(['user', 'area'])
            ->where('evento_id', $this->eventoId)
            ->orderBy('area_id')      // agrupa por área antes de ordenar por usuário
            ->orderBy('user_id')      // depois ordena por user_id dentro da área
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Email',
            'Status',
            'Link Lattes',
            'Resumo Lattes',
            'Área de Preferência',
            'Já avaliou?',
            'Disponibilidade de Idiomas',
        ];
    }

    public function map($candidato): array
    {
        // monta texto de status
        if ($candidato->aprovado) {
            $status = 'Aprovado';
        } elseif ($candidato->em_analise) {
            $status = 'Em Análise';
        } else {
            $status = 'Rejeitado';
        }

        // se for o primeiro registro deste usuário, exibe todos os campos
        if ($this->lastUserId !== $candidato->user_id) {
            $this->lastUserId = $candidato->user_id;

            return [
                $candidato->user->name ?? 'N/A',
                $candidato->user->email ?? 'N/A',
                $status,
                $candidato->link_lattes,
                $candidato->resumo_lattes,
                $candidato->area->nome,
                $candidato->ja_avaliou ? 'Sim' : 'Não',
                $candidato->disponibilidade_idiomas,
            ];
        }

        // linhas seguintes: só o eixo, demais colunas em branco
        return [
            '', '', '', '', '',
            $candidato->area->nome,
            '', '',
        ];
    }
}
