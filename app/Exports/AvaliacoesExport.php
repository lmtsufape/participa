<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AvaliacoesExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $trabalhos;

    protected $headings_form;

    public function headings(): array
    {
        return $this->headings_form;
    }

    public function __construct($trabalhos, array $headings_form)
    {
        $this->trabalhos = $trabalhos;
        $this->headings_form = $headings_form;
    }

    public function collection()
    {
        return $this->trabalhos;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'line_ending' => ";\n",
            'enclosure' => '',
        ];
    }
}
