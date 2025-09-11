<?php

namespace App\Exports;

use App\Models\Users\Revisor;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AvaliadoresPorEixoExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private int $eventoId,
        private int $eixoId
    ) {}

    public function query()
    {
        return Revisor::query()
            ->where('evento_id', $this->eventoId)
            ->where('areaId', $this->eixoId)
            ->with(['user:id,name,email'])
            ->distinct('user_id')
            ->orderBy('user_id');
    }

    public function map($revisor): array
    {
        $get = $revisor->user->revisorWithCounts()->where('evento_id', $this->eventoId)->get();
        $processando = $get->sum('processando_count');
        $avaliados = $get->sum('avaliados_count') + $processando;

        return [
            $revisor->user?->name,
            $revisor->user?->email,
            $processando,        // avaliações pendentes
            $avaliados,          // trabalhos atribuídos
        ];
    }

    public function headings(): array
    {
        return ['Avaliador', 'E-mail', 'Avaliações pendentes', 'Trabalhos atribuídos (total)'];
    }
}
