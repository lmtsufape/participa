<?php

namespace App\Exports;

use App\Models\CandidatoAvaliador;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class CandidatosAvaliadoresExport implements FromCollection, WithHeadings, WithMapping
{
    protected $eventoId;

    public function __construct(int $eventoId)
    {
        $this->eventoId = $eventoId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        $todosCandidatos = CandidatoAvaliador::with(['user', 'area']) // <-- Corrigido para usar a relação 'area'
            ->where('evento_id', $this->eventoId)
            ->get();

        $candidaturas = $todosCandidatos
            ->groupBy('user_id')
            ->map(function($group) {
                $first = $group->first();
                return (object)[
                    'id'                      => $first->id,
                    'user'                    => $first->user,
                    'aprovado'                => $first->aprovado,
                    'em_analise'              => $first->em_analise,
                    'link_lattes'             => $first->link_lattes,
                    'resumo_lattes'           => $first->resumo_lattes,
                    'eixos'                   => $group->pluck('area.nome')->implode(', '),
                    'ja_avaliou_cba'          => $first->ja_avaliou_cba,
                    'disponibilidade_idiomas' => $first->disponibilidade_idiomas,
                ];
            })
            ->values();

        return $candidaturas;
    }

    /**
     * Define os cabeçalhos das colunas.
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID do Candidato',
            'Nome',
            'Email',
            'Status',
            'Link Lattes',
            'Resumo Lattes',
            'Eixos de Preferência',
            'Já avaliou no CBA?',
            'Disponibilidade de Idiomas',
        ];
    }

    /**
     * Mapeia os dados de cada linha para as colunas.
     * @param mixed $candidatura
     * @return array
     */
    public function map($candidatura): array
    {
        if ($candidatura->aprovado) {
            $status = 'Aprovado';
        } elseif ($candidatura->em_analise) {
            $status = 'Em Análise';
        } else {
            $status = 'Rejeitado';
        }

        return [
            $candidatura->id,
            $candidatura->user->name,
            $candidatura->user->email,
            $status,
            $candidatura->link_lattes,
            $candidatura->resumo_lattes,
            $candidatura->eixos,
            $candidatura->ja_avaliou_cba ? 'Sim' : 'Não',
            $candidatura->disponibilidade_idiomas,
        ];
    }
}
