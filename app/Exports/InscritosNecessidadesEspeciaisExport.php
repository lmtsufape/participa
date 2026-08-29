<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InscritosNecessidadesEspeciaisExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $evento;

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    public function collection()
    {
        // Se o evento possuir subeventos, inclui as inscrições deles também
        if ($this->evento->subeventos()->count() > 0) {
            $eventoIds = $this->evento->subeventos->pluck('id')->push($this->evento->id)->toArray();
        } else {
            $eventoIds = [$this->evento->id];
        }

        // Filtra apenas inscritos que possuem perfil identitário com necessidades especiais preenchidas e diferentes de 'nenhuma'
        return \App\Models\Inscricao\Inscricao::whereIn('evento_id', $eventoIds)
            ->with(['user.endereco', 'categoria', 'evento'])
            ->whereHas('user', function ($qUser) {
                $qUser->whereExists(function ($query) {
                    $query->select(\Illuminate\Support\Facades\DB::raw(1))
                        ->from('perfil_identitarios')
                        ->whereColumn('perfil_identitarios.userId', 'users.id')
                        ->whereNotNull('perfil_identitarios.necessidadesEspeciais')
                        ->where('perfil_identitarios.necessidadesEspeciais', '!=', '[]')
                        ->where('perfil_identitarios.necessidadesEspeciais', '!=', '["nenhuma"]')
                        ->where('perfil_identitarios.necessidadesEspeciais', '!=', 'nenhuma');
                });
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Inscrição',
            'Evento',
            'Nome',
            'Nome Social',
            'E-mail',
            'CPF / CNPJ / Passaporte',
            'Celular',
            'Instituição',
            'Categoria',
            'Status Inscrição',
            'Necessidades Especiais',
            'Outra Necessidade Especial',
        ];
    }

    public function map($inscricao): array
    {
        $user = $inscricao->user;
        
        // Busca o perfil identitário do usuário
        $perfil = \App\Models\PerfilIdentitario::where('userId', $user->id)->first();

        // Formata as necessidades especiais (array para string separada por vírgula)
        $necessidades = '';
        if ($perfil && !empty($perfil->necessidadesEspeciais)) {
            $arrayNec = is_array($perfil->necessidadesEspeciais) 
                ? $perfil->necessidadesEspeciais 
                : json_decode($perfil->necessidadesEspeciais, true);

            if (is_array($arrayNec)) {
                $necessidades = implode(', ', array_filter($arrayNec, fn($item) => strtolower($item) !== 'nenhuma'));
            } else {
                $necessidades = $perfil->necessidadesEspeciais;
            }
        }

        $documento = $user->cpf ?: ($user->cnpj ?: ($user->passaporte ?: 'Não informado'));

        return [
            $inscricao->id,
            $inscricao->evento->nome ?? 'N/A',
            $user->name,
            $user->nomeSocial ?? 'Não informado',
            $user->email,
            $documento,
            $user->celular ?? 'Não informado',
            $user->instituicao ?? 'Não informada',
            $inscricao->categoria?->nome ?? 'N/A',
            $inscricao->finalizada ? 'Inscrito' : 'Pré-inscrito',
            $necessidades ?: 'Não especificado',
            $perfil->outraNecessidadeEspecial ?? 'Não informado',
        ];
    }
}