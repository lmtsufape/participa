<?php

namespace App\Exports;

use App\Models\CandidatoAvaliador;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CandidatosAvaliadoresExport implements FromCollection, WithHeadings, WithMapping
{
    protected $eventoId;
    protected $eixos;
    private $lastUserId = null;    // guarda o último user_id processado

    public function __construct($eventoId, $eixos = null)
    {
        $this->eventoId = $eventoId;
        $this->eixos = $eixos; 
    }

    public function collection()
    {
        $query = CandidatoAvaliador::with(['user', 'area'])
            ->where('evento_id', $this->eventoId)
            ->orderBy('user_id');

        if ($this->eixos && is_array($this->eixos)) {
            $query->whereIn('area_id', $this->eixos);
        }

        return $query->get();
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

        return [
            $candidato->user->name ?? 'N/A',
            $candidato->user->email ?? 'N/A',
            $status,
            $candidato->link_lattes,
            $candidato->resumo_lattes,
            $candidato->area->nome,
            $candidato->ja_avaliou_cba ? 'Sim' : 'Não',
            $candidato->disponibilidade_idiomas,
        ];
    }
}
