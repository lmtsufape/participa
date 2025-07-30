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

    protected $trabalhosPorArea;

    public function __construct($trabalhosPorArea)
    {
        $this->trabalhosPorArea = $trabalhosPorArea;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->trabalhosPorArea as $areaNome => $trabalhos) {
            $sheets[] = new TrabalhosPorAreaSheetExport($trabalhos, $areaNome);
        }

        return $sheets;
    }
}
