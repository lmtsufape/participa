<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use App\Models\PerfilIdentitario;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InscritosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $evento;

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->evento->inscricaos()->with(['user.endereco', 'categoria', 'camposPreenchidos.campoFormulario'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $camposBase = [
            '#', 'Status', 'Pagamento Confirmado', 'Nome Completo', 'Nome Social', 'E-mail', 'CPF/CNPJ/Passaporte',
            'Data de Nascimento', 'Gênero', 'Raça/Cor', 'Comunidade Tradicional', 'LGBTQIA+', 
            'Pessoa Idosa ou com Deficiência', 'Necessidades Especiais', 'Associado à ABA',
            'Instituição', 'Celular', 'País', 'Estado', 'Cidade', 'Bairro', 'Rua', 'Número', 'CEP', 'Complemento',
            'Categoria', 'Valor (R$)',
        ];

        $camposExtras = $this->evento->camposFormulario()->pluck('titulo')->all();

        return array_merge($camposBase, $camposExtras);
    }

    /**
     * @param mixed $inscricao
     * @return array
     */
    public function map($inscricao): array
    {
        $user = $inscricao->user;
        $categoria = $inscricao->categoria;

        $perfil = PerfilIdentitario::where('userId', $user->id)->first();

        $valor = $categoria ? number_format($categoria->valor_total, 2, ',', '.') : 'N/A';
        $documento = $user->cpf ?? ($user->cnpj ?? $user->passaporte);
        $genero = ($perfil->genero ?? '') === 'outro' ? $perfil->outroGenero : ucfirst($perfil->genero ?? '');
        $raca = is_array($perfil->raca ?? []) ? implode(', ', array_map('ucfirst', str_replace('_', ' ', $perfil->raca))) : '';
        if (str_contains($raca, 'Outra raca')) {
            $raca = 'Outra: ' . $perfil->outraRaca;
        }

        $valoresBase = [
            $inscricao->id,
            $inscricao->finalizada ? 'Inscrito' : 'Pré-inscrito',
            $inscricao->finalizada ? 'Sim' : 'Não',
            $user->name,
            $perfil->nomeSocial ?? '',
            $user->email,
            $documento,
            $perfil && $perfil->dataNascimento ? Carbon::parse($perfil->dataNascimento)->format('d/m/Y') : '',
            $genero,
            $raca,
            ($perfil->comunidadeTradicional ?? false) ? $perfil->nomeComunidadeTradicional : 'Não',
            ($perfil->lgbtqia ?? false) ? 'Sim' : 'Não',
            ($perfil->deficienciaIdoso ?? false) ? 'Sim' : 'Não',
            is_array($perfil->necessidadesEspeciais ?? []) ? implode(', ', $perfil->necessidadesEspeciais) : '',
            ($perfil->associadoAba ?? false) ? 'Sim' : 'Não',
            $user->instituicao,
            $user->celular,
            $user->endereco->pais ?? '',
            $user->endereco->uf ?? '',
            $user->endereco->cidade ?? '',
            $user->endereco->bairro ?? '',
            $user->endereco->rua ?? '',
            $user->endereco->numero ?? '',
            $user->endereco->cep ?? '',
            $user->endereco->complemento ?? '',
            $categoria->nome ?? 'Não definida',
            $valor,
        ];
        
        $camposExtrasValores = [];
        $camposFormulario = $this->evento->camposFormulario;
        foreach ($camposFormulario as $campo) {
            $campoPreenchido = $inscricao->camposPreenchidos->firstWhere('id', $campo->id);
            $camposExtrasValores[] = $campoPreenchido ? $campoPreenchido->pivot->valor : '';
        }

        return array_merge($valoresBase, $camposExtrasValores);
    }
}
