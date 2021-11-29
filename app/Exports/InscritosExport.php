<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InscritosExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'nome',
            'email',
        ];
    }

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    public function collection()
    {
        return new Collection(DB::table('inscricaos AS i')
            ->join('users AS u', 'u.id', 'i.user_id')
            ->where('i.evento_id', $this->evento->id)
            ->select('u.name', 'u.email')
            ->get());
    }
}
