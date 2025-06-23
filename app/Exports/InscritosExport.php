<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
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
            '#',
            'Evento/Subevento',
            'Nome',
            'E-mail',
            'Instituição',
            'Celular',
            'CPF',
            'Passaporte',
            'Especialização Profissional',
            'Rua',
            'Número',
            'Bairro',
            'Cidade',
            'Estado',
            'CEP',
            'Complemento',
            'País',
            'Status',
            'Pagamento Confirmado',
            'Categoria',
            'Valor (R$)',
            'Isento',
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
        $valor = $inscricao->categoria ? number_format($inscricao->categoria->valor_total, 2, ',', '.') : 'N/A';
        $isento = $inscricao->categoria && $inscricao->categoria->valor_total == 0 ? 'Sim' : 'Não';
        
        $valoresBase = [
            $inscricao->id,
            $inscricao->evento->nome,
            $inscricao->user->name,
            $inscricao->user->email,
            $inscricao->user->instituicao,
            $inscricao->user->celular,
            $inscricao->user->cpf,
            $inscricao->user->passaporte,
            $inscricao->user->especProfissional,
            $inscricao->user->endereco ? $inscricao->user->endereco->rua : '',
            $inscricao->user->endereco ? $inscricao->user->endereco->numero : '',
            $inscricao->user->endereco ? $inscricao->user->endereco->bairro : '',
            $inscricao->user->endereco ? $inscricao->user->endereco->cidade : '',
            $inscricao->user->endereco ? $inscricao->user->endereco->uf : '',
            $inscricao->user->endereco ? $inscricao->user->endereco->cep : '',
            $inscricao->user->endereco ? $inscricao->user->endereco->complemento : '',
            $inscricao->user->endereco ? $inscricao->user->endereco->pais : '',
            $inscricao->finalizada ? 'Inscrito' : 'Pré-inscrito',
            $inscricao->finalizada ? 'Sim' : 'Não',
            $inscricao->categoria->nome ?? 'Não definida',
            $valor,
            $isento,
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
