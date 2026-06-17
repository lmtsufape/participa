<?php

namespace App\Exports;

use App\Models\Users\Revisor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

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
            ->where('revisors.evento_id', $this->eventoId)
            // Filtra apenas revisores que possuem relacionamento em "respostas"
            ->whereHas('respostas') 
            // Faz o join com a tabela de usuários para poder ordenar por nome
            ->join('users', 'revisors.user_id', '=', 'users.id')
            // Agrupa pelo ID do usuário para eliminar qualquer duplicidade de linhas
            ->groupBy('revisors.user_id', 'users.name', 'users.email') 
            ->orderBy('users.name', 'asc')
            // Seleciona as colunas explicitamente (usando MAX ou agregadores se o banco for rigoroso)
            ->select('revisors.user_id', 'users.name', 'users.email', DB::raw('MAX(revisors."trabalhosCorrigidos") as "trabalhosCorrigidos"'));
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