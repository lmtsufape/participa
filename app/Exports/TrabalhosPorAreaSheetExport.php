<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TrabalhosPorAreaSheetExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $trabalhos;
    protected $areaNome;

    public function __construct($trabalhos, $areaNome)
    {
        $this->trabalhos = $trabalhos;
        $this->areaNome = $areaNome;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->trabalhos->map(function ($trabalho) {
            return [
                'Modalidade' => $trabalho->modalidade->nome,
                'Título' => $trabalho->titulo,
                'Autor (Nome)' => $trabalho->autor->name,
                'Autor (CPF)' => $trabalho->autor->cpf,
                'Autor (E-mail)' => $trabalho->autor->email,
                'Autor (Celular)' => $trabalho->autor->celular,
                'Coautores (Nomes)' => $this->coautoresToString($trabalho, 'nome'),
                'Coautores (CPFs)' => $this->coautoresToString($trabalho, 'cpf'),
                'Coautores (E-mails)' => $this->coautoresToString($trabalho, 'email'),
                'Coautores (Celulares)' => $this->coautoresToString($trabalho, 'celular'),
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Modalidade',
            'Título',
            'Autor (Nome)',
            'Autor (CPF)',
            'Autor (E-mail)',
            'Autor (Celular)',
            'Coautores (Nomes)',
            'Coautores (CPFs)',
            'Coautores (E-mails)',
            'Coautores (Celulares)',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->areaNome;
    }


    private function coautoresToString($trabalho, $campo)
    {
        $coautores = $trabalho->coautors;
        $nomes = [];
        foreach ($coautores as $coautor) {
            if ($campo === 'nome') {
                $nomes[] = $coautor->user->name;
            } else {
                $nomes[] = $coautor->user->$campo;
            }
        }
        return implode('; ', $nomes);
    }
}
