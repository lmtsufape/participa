<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TrabalhosExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected $trabalhos;

    /**
     * Define os cabeçalhos das colunas no XLSX.
     */
    public function headings(): array
    {
        return [
            'Área/Eixo',
            'Modalidade',
            'Título do trabalho',
            'Autor',
            'CPF',
            'E-mail',
            'Telefone',
            'Co-autor(es)',
            'CPF Co-autor',
            'E-mail Co-autor',
            'Telefone Co-autor',
        ];
    }

    /**
     * Recebe a coleção de trabalhos a ser exportada.
     */
    public function __construct($trabalhos)
    {
        $this->trabalhos = $trabalhos;
    }

    /**
     * Retorna a coleção que será transformada em planilha.
     */
    public function collection()
    {
        return $this->trabalhos;
    }
}
