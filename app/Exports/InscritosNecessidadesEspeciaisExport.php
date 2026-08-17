<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use App\Models\Inscricao\Inscricao;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InscritosNecessidadesEspeciaisExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Evento $evento;

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    public function collection()
    {
        if ($this->evento->subeventos->count() > 0) {
            $eventoIds = array_merge([$this->evento->id], $this->evento->subeventos->pluck('id')->toArray());
        } else {
            $eventoIds = [$this->evento->id];
        }

        return Inscricao::query()
            ->whereIn('evento_id', $eventoIds)
            ->whereHas('user.perfilIdentitario')
            ->with([
                'user.perfilIdentitario',
                'user.endereco',
                'categoria',
                'evento'
            ])
            ->get()
            ->filter(function ($inscricao) {
                $perfil = $inscricao->user?->perfilIdentitario;
                if (!$perfil) {
                    return false;
                }

                $temOutra = !empty(trim((string) $perfil->outraNecessidadeEspecial));

                $necessidades = is_array($perfil->necessidadesEspeciais) ? $perfil->necessidadesEspeciais : [];
                $necessidadesValidas = array_filter($necessidades, function ($item) {
                    $itemLimpo = strtolower(trim((string) $item));
                    return $itemLimpo !== '' && $itemLimpo !== 'nenhuma';
                });

                return $temOutra || !empty($necessidadesValidas);
            })
            ->values();
    }

    public function headings(): array
    {
        return [
            'ID Inscrição',
            'Evento / Subevento',
            'Nome Completo',
            'Nome Social',
            'E-mail',
            'CPF/CNPJ/Passaporte',
            'Celular',
            'Instituição',
            'Categoria',
            'Status Inscrição',
            'Necessidades Especiais Marcadas',
            'Outra Necessidade Especial (Detalhada)',
            'Pessoa Idosa ou PCD (Sim/Não)',
            'Cidade/UF',
            'Data da Inscrição'
        ];
    }

    /**
     * @param \App\Models\Inscricao\Inscricao $inscricao
     */
    public function map($inscricao): array
    {
        $user = $inscricao->user;
        $perfil = $user?->perfilIdentitario;

        $necessidades = 'Não informado';
        if ($perfil && !empty($perfil->necessidadesEspeciais) && is_array($perfil->necessidadesEspeciais)) {
            $necessidadesFiltradas = array_filter($perfil->necessidadesEspeciais, function ($n) {
                return strtolower(trim((string) $n)) !== 'nenhuma';
            });

            if (!empty($necessidadesFiltradas)) {
                $necessidades = collect($necessidadesFiltradas)->map(function ($item) {
                    return ucfirst(str_replace('_', ' ', $item));
                })->implode(', ');
            }
        }

        $documento = $user->cpf ?: ($user->cnpj ?: ($user->passaporte ?: 'Não informado'));

        $cidadeUf = $user->endereco ? "{$user->endereco->cidade}/{$user->endereco->uf}" : 'Não informado';

        return [
            $inscricao->id,
            $inscricao->evento->nome ?? 'N/A',
            $user->name ?? 'N/A',
            $perfil->nomeSocial ?? 'Não informado',
            $user->email ?? 'N/A',
            $documento,
            $user->celular ?? 'Não informado',
            $user->instituicao ?? 'Não informado',
            $inscricao->categoria->nome ?? 'N/A',
            $inscricao->finalizada ? 'Finalizada' : 'Pendente',
            $necessidades,
            $perfil->outraNecessidadeEspecial ?? 'Não informado',
            ($perfil && ($perfil->deficienciaIdoso === true || $perfil->deficienciaIdoso === 'true')) ? 'Sim' : 'Não',
            $cidadeUf,
            $inscricao->created_at ? $inscricao->created_at->format('d/m/Y H:i') : 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}