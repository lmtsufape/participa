<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Users\User;

class RevisoresExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(private Evento $evento)
    {
    }

    public function collection()
    {
        $query = User::whereHas('revisor', function (Builder $query) {
            $query->where('evento_id', $this->evento->id);
        });

        $query->with(['revisor' => function ($q) {
            $q->where('evento_id', $this->evento->id)
            ->withCount([
                'trabalhosAtribuidos as avaliados_count' => function (Builder $query) {
                    $query->where('parecer', 'avaliado')->orWhere('parecer', 'encaminhado');
                },
                'trabalhosAtribuidos as processando_count' => function (Builder $query) {
                    $query->where('parecer', 'processando');
                },
                'trabalhosAtribuidos as total_atribuidos_count'
            ]);
        }]);

        $revisores = $query->orderBy('name')->get();

        $rows = $revisores->map(function ($revisor) {
            $processandoCount = $revisor->revisor->sum('processando_count');
            $avaliadosCount = $revisor->revisor->sum('avaliados_count');
            $totalAtribuidosCount = $revisor->revisor->sum('total_atribuidos_count');
            
            $documento = $revisor->cpf ?? $revisor->cnpj ?? $revisor->passaporte ?? '';
            $telefone = $revisor->celular ?? '';

            $areas = $revisor->revisor()
                ->where('evento_id', $this->evento->id)
                ->distinct('areaId')
                ->with('area')
                ->get()
                ->pluck('area.nome')
                ->filter()
                ->unique()
                ->values()
                ->implode(', ');

            $modalidades = $revisor->revisor()
                ->where('evento_id', $this->evento->id)
                ->distinct('modalidadeId')
                ->with('modalidade')
                ->get()
                ->pluck('modalidade.nome')
                ->filter()
                ->unique()
                ->values()
                ->implode(', ');

            return [
                $revisor->name ?? '',
                $revisor->email ?? '',
                $telefone,
                $documento,
                $areas,
                $modalidades,
                $totalAtribuidosCount,
                $processandoCount,
                $avaliadosCount,
            ];
        })->sortBy(function ($row) {
            return $row[0];
        })->values();

        return new Collection($rows);
    }

    public function headings(): array
    {
        return ['Nome', 'E-mail', 'Telefone', 'Documento', 'Áreas', 'Modalidades', 'Trabalhos Atribuídos', 'Em Andamento', 'Finalizados'];
    }
} 