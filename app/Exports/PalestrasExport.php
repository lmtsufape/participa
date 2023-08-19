<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PalestrasExport implements FromCollection, WithHeadings, WithMapping
{

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ($this->evento->palestrantes()->with('palestra')->get());
    }

    public function headings(): array
    {
        return [
            'titulo',
            'palestrante',
            'email',
        ];
    }

    public function map($palestrante): array
    {
        return [
            $palestrante->palestra->titulo,
            $palestrante->nome,
            $palestrante->email,
        ];
    }
}
