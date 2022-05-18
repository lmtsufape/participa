<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InscritosExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array
    {
        return [
            'evento/subevento',
            'nome',
            'email',
            'instituicao',
            'celular',
            'cpf',
            'passaporte',
            'especialicazao profissional',
            'rua',
            'numero',
            'bairro',
            'cidade',
            'estado',
            'cep',
            'complemento',
            'pais'

        ];
    }

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    public function collection()
    {
        return $this->evento->inscritos();
    }

    public function map($inscricao): array
    {
        return [
            $inscricao->evento->nome,
            $inscricao->user->name,
            $inscricao->user->email,
            $inscricao->user->instituicao,
            $inscricao->user->celular,
            $inscricao->user->cpf,
            $inscricao->user->passaporte,
            $inscricao->user->especProfissional,
            $inscricao->user->endereco ? $inscricao->user->endereco->rua : '' ,
            $inscricao->user->endereco ? $inscricao->user->endereco->numero : '' ,
            $inscricao->user->endereco ? $inscricao->user->endereco->bairro : '' ,
            $inscricao->user->endereco ? $inscricao->user->endereco->cidade : '' ,
            $inscricao->user->endereco ? $inscricao->user->endereco->uf : '' ,
            $inscricao->user->endereco ? $inscricao->user->endereco->cep : '' ,
            $inscricao->user->endereco ? $inscricao->user->endereco->complemento : '' ,
            $inscricao->user->endereco ? $inscricao->user->endereco->pais : '' ,
        ];
    }
}
