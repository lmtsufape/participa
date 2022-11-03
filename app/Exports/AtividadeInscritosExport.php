<?php

namespace App\Exports;

use App\Models\Submissao\Atividade;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AtividadeInscritosExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array
    {
        return [
            'evento/subevento',
            'atividade',
            'nome',
            'e-mail',
            'instituição',
            'celular',
            'cpf',
            'passaporte',
            'especialização profissional',
            'rua',
            'numero',
            'bairro',
            'cidade',
            'estado',
            'cep',
            'complemento',
            'pais',
        ];
    }

    public function __construct(Atividade $atividade)
    {
        $this->atividade = $atividade;
        $this->evento = $atividade->evento()->first();
    }

    public function collection()
    {
        return $this->atividade->users()->get();
    }

    public function map($inscricao): array
    {
        return [
            $this->evento->nome,
            $this->atividade->titulo,
            $inscricao->name,
            $inscricao->email,
            $inscricao->instituicao,
            $inscricao->celular,
            $inscricao->cpf,
            $inscricao->passaporte,
            $inscricao->especProfissional,
            $inscricao->endereco ? $inscricao->endereco->rua : '',
            $inscricao->endereco ? $inscricao->endereco->numero : '',
            $inscricao->endereco ? $inscricao->endereco->bairro : '',
            $inscricao->endereco ? $inscricao->endereco->cidade : '',
            $inscricao->endereco ? $inscricao->endereco->uf : '',
            $inscricao->endereco ? $inscricao->endereco->cep : '',
            $inscricao->endereco ? $inscricao->endereco->complemento : '',
            $inscricao->endereco ? $inscricao->endereco->pais : '',
        ];
    }
}
