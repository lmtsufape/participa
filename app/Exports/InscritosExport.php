<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InscritosExport implements WithMultipleSheets
{
    protected Evento $evento;

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    /**
     * Carrega todas as inscrições com relações necessárias.
     *
     * @return Collection
     */
    protected function allInscricoes(): Collection
    {
        return $this->evento
            ->inscricaos()
            ->with([
                'user.endereco',
                'categoria',
                'camposPreenchidos',
                'evento.camposFormulario',
            ])
            ->get();
    }

    /**
     * Retorna array de sheets. Se existir campo "Eixo temático" -> uma sheet por eixo,
     * caso contrário -> uma sheet única "Todos".
     *
     * @return array
     */
    public function sheets(): array
    {
        $inscricoes = $this->allInscricoes();
        $camposFormulario = $this->evento->camposFormulario()->get();

        $eixoCampo = $camposFormulario->first(function ($c) {
            return $this->normalize($c->titulo) === $this->normalize('Eixo temático')
                || $this->normalize($c->titulo) === $this->normalize('Eixo tematico');
        });

        if (!$eixoCampo) {
            // apenas uma sheet com todas as inscrições
            return [
                $this->makeSheet($this->evento, $inscricoes, 'Todos'),
            ];
        }

        // agrupa por valor do campo Eixo temático
        $grouped = $inscricoes->groupBy(function ($inscricao) use ($eixoCampo) {
            $preenchido = $inscricao->camposPreenchidos->firstWhere('id', $eixoCampo->id);
            $valor = $preenchido && isset($preenchido->pivot->valor) ? trim((string) $preenchido->pivot->valor) : '';
            return $valor !== '' ? $valor : 'Sem Eixo';
        });

        $sheets = [];
        foreach ($grouped as $eixoNome => $colecaoDoEixo) {
            $title = $this->sanitizeSheetTitle((string) $eixoNome);
            $sheets[] = $this->makeSheet($this->evento, $colecaoDoEixo, $title);
        }

        return $sheets;
    }

    /**
     * Cria uma sheet anônima (objeto) que implementa FromCollection, WithHeadings, WithMapping, WithTitle.
     *
     * @param Evento $evento
     * @param Collection $inscricoes
     * @param string $title
     * @return object
     */
    protected function makeSheet(Evento $evento, Collection $inscricoes, string $title)
    {
        $export = new class($evento, $inscricoes, $title) implements FromCollection, WithHeadings, WithMapping, WithTitle {
            protected Evento $evento;
            protected Collection $inscricoes;
            protected string $title;

            public function __construct(Evento $evento, Collection $inscricoes, string $title)
            {
                $this->evento = $evento;
                $this->inscricoes = $inscricoes;
                $this->title = $title;
            }

            public function title(): string
            {
                return $this->title;
            }

            public function collection()
            {
                return $this->inscricoes;
            }

            public function headings(): array
            {
                $camposBase = [
                    '#', 'Status', 'Pagamento Confirmado', 'Nome Completo', 'Nome Social', 'E-mail', 'CPF/CNPJ/Passaporte',
                    'Data de Nascimento', 'Instituição', 'Celular', 'País', 'Estado', 'Cidade', 'Bairro',
                    'Rua', 'Número', 'CEP', 'Complemento', 'Categoria', 'Valor (R$)',
                ];

                $camposExtras = $this->evento
                    ->camposFormulario()
                    ->pluck('titulo')
                    ->all();

                return array_merge($camposBase, $camposExtras);
            }

            public function map($inscricao): array
            {
                $user      = $inscricao->user;
                $categoria = $inscricao->categoria;

                $valor     = $categoria
                    ? number_format($categoria->valor_total, 2, ',', '.')
                    : 'N/A';

                $documento = $user->cpf
                    ?? $user->cnpj
                    ?? $user->passaporte;

                $valoresBase = [
                    $inscricao->id,
                    $inscricao->finalizada ? 'Inscrito' : 'Pré-inscrito',
                    $inscricao->finalizada ? 'Sim' : 'Não',
                    $user->name ?? '',
                    $user->nomeSocial ?? '',
                    $user->email ?? '',
                    $documento ?? '',
                    $user->dataNascimento ? \Carbon\Carbon::parse($user->dataNascimento)->format('d/m/Y') : '',
                    $user->instituicao ?? '',
                    $user->celular ?? '',
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

                // valores dos campos extras preenchidos
                $camposExtrasValores = [];
                $camposFormulario    = $this->evento->camposFormulario;
                foreach ($camposFormulario as $campo) {
                    $preenchido = $inscricao->camposPreenchidos
                        ->firstWhere('id', $campo->id);
                    $camposExtrasValores[] = $preenchido
                        ? ($preenchido->pivot->valor ?? '')
                        : '';
                }

                return array_merge($valoresBase, $camposExtrasValores);
            }
        };

        return $export;
    }

    /**
     * Normaliza strings (remove acentos, minúsculas, trim) para comparar títulos.
     *
     * @param string $s
     * @return string
     */
    protected function normalize(string $s): string
    {
        $t = @iconv('UTF-8', 'ASCII//TRANSLIT', $s) ?: $s;
        $t = mb_strtolower($t);
        $t = preg_replace('/\s+/', ' ', $t);
        return trim($t);
    }

    /**
     * Sanitiza título de sheet: remove caracteres inválidos e limita 31 chars.
     *
     * @param string $title
     * @return string
     */
    protected function sanitizeSheetTitle(string $title): string
    {
        $clean = preg_replace('/[\\\\\/\*\[\]\:\?]/u', '', $title);
        $clean = trim($clean);
        if ($clean === '') {
            $clean = 'Sem Eixo';
        }
        return mb_strimwidth($clean, 0, 31);
    }
}
