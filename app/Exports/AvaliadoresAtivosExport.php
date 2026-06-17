<?php

namespace App\Exports;

use App\Models\Users\Revisor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AvaliadoresAtivosExport implements FromQuery, WithHeadings, WithMapping
{
    protected $eventoId;

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    /**
    * Query que busca os revisores filtrados e ordenados
    */
    public function query()
    {
        return Revisor::query()
            ->where('evento_id', $this->eventoId)
            // Filtra apenas revisores que possuem relacionamento em "respostas" (indica que avaliou algo)
            // Ou use 'avaliacoes' dependendo de como seu sistema valida uma avaliação concluída.
            ->whereHas('respostas') 
            // Faz o join com a tabela de usuários para poder ordenar por nome
            ->join('users', 'revisors.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('revisors.*'); // Evita sobreposição de colunas do join
    }

    /**
    * Cabeçalho do arquivo XLSX
    */
    public function headings(): array
    {
        return [
            'Nome',
            'E-mail',
            'Trabalhos Corrigidos',
        ];
    }

    /**
    * Mapeamento de cada linha do Excel
    */
    public function map($revisor): array
    {
        return [
            $revisor->user->name ?? 'Cadastro Incompleto',
            $revisor->user->email,
            $revisor->trabalhosCorrigidos,
        ];
    }
}