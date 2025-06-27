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
            ->orderBy('user_id')      // garante que todos os registros de um mesmo usuário venham juntos
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
            'Eixo de Preferência',
            'Já avaliou no CBA?',
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
                $candidato->user->name,
                $candidato->user->email,
                $status,
                $candidato->link_lattes,
                $candidato->resumo_lattes,
                $candidato->area->nome,
                $candidato->ja_avaliou_cba ? 'Sim' : 'Não',
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
