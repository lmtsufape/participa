<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AutoresTrabalhosCompletosExport extends StringValueBinder implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithCustomValueBinder
{
    public function __construct(private Builder $trabalhos)
    {
    }

    public function query()
    {
        return (clone $this->trabalhos)->setEagerLoads([])->with('autor');
    }

    public function headings(): array
    {
        return ['Autor', 'E-mail', 'Telefone', 'Título do trabalho'];
    }

    public function map($trabalho): array
    {
        return [
            $trabalho->autor?->name ?? '',
            $trabalho->autor?->email ?? '',
            $trabalho->autor?->celular ?? '',
            $trabalho->titulo ?? '',
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 50, 'B' => 45, 'C' => 25, 'D' => 80];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:D'.$sheet->getHighestRow());
        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getStyle('A1:D'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);

        return [1 => [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3D93A9']],
        ]];
    }
}
