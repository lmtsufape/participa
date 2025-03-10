<?php

namespace App\Exports;

use App\Models\Submissao\Trabalho;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrabalhosExportForCertifica implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $trabalhos;
    protected $ch;

    public function __construct($trabalhos, $ch)
    {
        $this->trabalhos = $trabalhos;
        $this->ch = $ch;
    }

    public function collection(): Collection
    {
        return collect($this->trabalhos->map(function($trabalho){
            $dados_gerais =  [
                'titulo' => $trabalho->titulo,
                'ch' => $this->ch,
                'tipo'  => 'AUTOR',
                'nome' => $trabalho->autor->name,
                'email' => $trabalho->autor->email,
                'cpf'   => $trabalho->autor->cpf,

            ];
            foreach ($trabalho->coautors as $index => $coautor) {
                $dados_gerais["tipo_coautor_" . ($index + 1)] = 'COAUTOR';
                $dados_gerais["nome_coautor_" . ($index + 1)] = $coautor->user->name;
                $dados_gerais["email_coautor_" . ($index + 1)] = $coautor->user->email;
                $dados_gerais["cpf_coautor_" . ($index + 1)] = $coautor->user->cpf;
            }

            return $dados_gerais;
        }));
    }

    public function headings(): array
    {
        return ['TITULO', 'CH', 'TIPO(AUTOR/COAUTOR)', 'NOME', 'E-MAIL', 'CPF'];
    }
}
