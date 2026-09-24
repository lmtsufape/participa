<?php

namespace App\UseCases\Modalidade;

use App\Enums\TipoArquivo;
use App\Http\Requests\modalidades\StoreModalidadeRequest;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Modalidade;
use Illuminate\Support\Facades\DB;

class StoreModalidadeUseCase
{
    public function execute(
        StoreModalidadeRequest $request,
        Evento $evento
    ): Modalidade {
        return DB::transaction(function () use ($request, $evento) {
            $modalidade = new Modalidade();

            $modalidade->fill(
                $request->payload()
            );

            /*
             * Mantido assim por enquanto.
             * Depois você pode ajustar para model binding/rota.
             */
            $modalidade->evento_id = $evento->id;

            /*
             * Salva primeiro porque precisamos do ID
             * para os relacionamentos.
             */
            $modalidade->save();

            $this->salvarArquivos(
                $request,
                $modalidade
            );

            $this->criarTiposApresentacao(
                $request,
                $modalidade
            );

            $this->criarMidiasExtras(
                $request,
                $modalidade
            );

            $this->criarDatasExtras(
                $request,
                $modalidade
            );

            /*
             * salvarArquivos() altera atributos
             * da própria modalidade.
             */
            if ($modalidade->isDirty()) {
                $modalidade->save();
            }

            return $modalidade;
        });
    }

    private function salvarArquivos(
        StoreModalidadeRequest $request,
        Modalidade $modalidade
    ): void {
        $arquivos = [
            'arquivoRegras' => [
                'atributo' => 'regra',
                'diretorio' => 'regras',
            ],

            'arquivoInstrucoes' => [
                'atributo' => 'instrucoes',
                'diretorio' => 'instrucoes',
            ],

            'arquivoTemplates' => [
                'atributo' => 'template',
                'diretorio' => 'templates',
            ],

            'arquivoModelos' => [
                'atributo' => 'modelo_apresentacao',
                'diretorio' => 'modelos',
            ],
        ];

        foreach ($arquivos as $input => $config) {
            if (!$request->hasFile($input)) {
                continue;
            }

            $modalidade->{$config['atributo']} =
                $request
                    ->file($input)
                    ->store(
                        "{$config['diretorio']}/{$modalidade->nome}"
                    );
        }
    }

    private function criarTiposApresentacao(
        StoreModalidadeRequest $request,
        Modalidade $modalidade
    ): void {
        $dados = collect(
            $request->tiposApresentacao()
        )
            ->map(fn (string $tipo) => [
                'tipo' => $tipo,
            ])
            ->values()
            ->all();

        if (empty($dados)) {
            return;
        }

        $modalidade
            ->tiposApresentacao()
            ->createMany($dados);
    }

    private function criarMidiasExtras(
        StoreModalidadeRequest $request,
        Modalidade $modalidade
    ): void {
        $documentos = (array) $request->input(
            'documentosExtra',
            []
        );

        foreach ($documentos as $documento) {
            $modalidade
                ->midiasExtra()
                ->create(
                    $this->midiaExtraPayload(
                        $documento
                    )
                );
        }
    }

    private function midiaExtraPayload(
        array $documento
    ): array {
        $valores = array_values($documento);

        /*
         * Na estrutura atual do formulário,
         * o primeiro valor é o nome.
         */
        $nome = array_shift($valores);

        $extensoes = collect($valores);

        $dados = [
            'nome' => $nome,
        ];

        foreach (TipoArquivo::cases() as $tipo) {
            $dados[$tipo->value] =
                $extensoes->contains(
                    $tipo->value
                );
        }

        return $dados;
    }

    private function criarDatasExtras(
        StoreModalidadeRequest $request,
        Modalidade $modalidade
    ): void {
        $nomes = (array) $request->input(
            'nomeDataExtra',
            []
        );

        $submissoes = (array) $request->input(
            'submissaoDataExtra',
            []
        );

        foreach ($nomes as $indice => $nome) {
            $modalidade
                ->datasExtras()
                ->create([
                    'nome' => $nome,

                    'inicio' => $request->input(
                        "inicioDataExtra.{$indice}"
                    ),

                    'fim' => $request->input(
                        "finalDataExtra.{$indice}"
                    ),

                    'permitir_submissao' =>
                        array_key_exists(
                            $indice,
                            $submissoes
                        ),
                ]);
        }
    }
}
