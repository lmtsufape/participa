<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use App\Models\Users\CoordEixoTematico;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ComissaoCientificaExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(private Evento $evento)
    {
    }

    public function collection()
    {
        $users = $this->evento->usuariosDaComissao;

        $rows = $users->map(function ($user) {
            $funcao = 'Membro da Comissão';
            if ($this->evento->userIsCoordComissaoCientifica($user)) {
                $funcao = 'Coordenador(a) da Comissão';
            } else {
                $eixos = CoordEixoTematico::where('evento_id', $this->evento->id)
                    ->where('user_id', $user->id)
                    ->with('area')
                    ->get();
                if ($eixos->isNotEmpty()) {
                    $nomes = $eixos->pluck('area.nome')->filter()->values()->all();
                    $funcao = 'Coordenador(a) de Eixo: ' . implode(', ', $nomes);
                }
            }

            $documento = $user->cpf ?? $user->cnpj ?? $user->passaporte ?? '';

            return [
                $user->name,
                $user->email,
                $documento,
                $user->celular,
                $funcao,
            ];
        })->sortBy(function ($row) {
            return $row[4]; //ordenação pela função na tabela
        })->values();

        return new Collection($rows);
    }

    public function headings(): array
    {
        return ['Nome', 'E-mail', 'Documento', 'Celular', 'Função'];
    }
} 