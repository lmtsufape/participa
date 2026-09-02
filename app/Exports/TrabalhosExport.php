<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TrabalhosExport implements WithMultipleSheets
{
    use Exportable;

    protected $trabalhos;

    public function headings(): array
    {
        return [
            'Id',
            'Área/Eixo',
            'Modalidade',
            'Título do trabalho',
            'Autor',
            'CPF',
            'E-mail',
            'Telefone',
            'Co-autor(es)',
            'CPF',
            'E-mail',
            'Telefone',
        ];
    }

    public function __construct($trabalhos)
    {
        $this->trabalhos = $trabalhos;
    }

    public function collection()
    {
        return $this->trabalhos;
    }
}
