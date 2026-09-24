<?php

namespace App\UseCases\Modalidade;

use App\Enums\TipoArquivo;
use App\Http\Requests\modalidades\UpdateModalidadeRequest;
use App\Models\Submissao\Modalidade;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateModalidadeUseCase
{
    public function execute(
        UpdateModalidadeRequest $request,
        Modalidade $modalidade
    ): void {
        DB::transaction(function () use (
            $request,
            $modalidade
        ) {
            $modalidade->fill(
                $request->payload()
            );

            $this->atualizarArquivos(
                $request,
                $modalidade
            );

            $modalidade->save();

            $this->sincronizarTiposApresentacao(
                $request,
                $modalidade
            );

            $this->sincronizarMidiasExtras(
                $request,
                $modalidade
            );

            $this->sincronizarDatasExtras(
                $request,
                $modalidade
            );
        });
    }

    private function atualizarArquivos(
        UpdateModalidadeRequest $request,
        Modalidade $modalidade
    ): void {
        $id = $modalidade->id;

        $arquivos = [
            [
                'input' => "arquivoRegras{$id}",
                'delete' => 'deleteregra',
                'atributo' => 'regra',
                'diretorio' => 'regras',
                'nome_original' => true,
            ],

            [
                'input' => "arquivoInstrucoes{$id}",
                'delete' => 'deleteinstrucoes',
                'atributo' => 'instrucoes',
                'diretorio' => 'instrucoes',
                'nome_original' => false,
            ],

            [
                'input' => "arquivoTemplates{$id}",
                'delete' => 'deletetemplate',
                'atributo' => 'template',
                'diretorio' => 'templates',
                'nome_original' => true,
            ],

            [
                'input' => "arquivoModelos{$id}",
                'delete' => 'deleteapresentacao',
                'atributo' => 'modelo_apresentacao',
                'diretorio' => 'modelos',
                'nome_original' => true,
            ],
        ];

        foreach ($arquivos as $config) {
            $input = $config['input'];
            $atributo = $config['atributo'];

            /*
             * Se houver novo arquivo, ele substitui
             * o existente independentemente do checkbox
             * de exclusão.
             */
            if ($request->hasFile($input)) {
                $this->substituirArquivo(
                    $modalidade,
                    $request->file($input),
                    $atributo,
                    $config['diretorio'],
                    $config['nome_original']
                );

                continue;
            }

            if ($request->boolean($config['delete'])) {
                $this->excluirArquivo(
                    $modalidade->{$atributo}
                );

                $modalidade->{$atributo} = null;
            }
        }
    }

    private function substituirArquivo(
        Modalidade $modalidade,
        UploadedFile $arquivo,
        string $atributo,
        string $diretorio,
        bool $nomeOriginal
    ): void {
        $this->excluirArquivo(
            $modalidade->{$atributo}
        );

        $diretorio = $diretorio
            . '/'
            . $modalidade->nome;

        if ($nomeOriginal) {
            $path = Storage::putFileAs(
                $diretorio,
                $arquivo,
                $arquivo->getClientOriginalName()
            );
        } else {
            $path = $arquivo->store(
                $diretorio
            );
        }

        $modalidade->{$atributo} = $path;
    }

    private function excluirArquivo(
        ?string $path
    ): void {
        if (
            $path
            && Storage::exists($path)
        ) {
            Storage::delete($path);
        }
    }

    private function sincronizarTiposApresentacao(
        UpdateModalidadeRequest $request,
        Modalidade $modalidade
    ): void {
        $selecionados = collect(
            $request->tiposApresentacao()
        );

        /*
         * Remove os tipos que deixaram
         * de estar selecionados.
         */
        if ($selecionados->isEmpty()) {
            $modalidade
                ->tiposApresentacao()
                ->delete();

            return;
        }

        $modalidade
            ->tiposApresentacao()
            ->whereNotIn(
                'tipo',
                $selecionados->all()
            )
            ->delete();

        /*
         * Recupera os que já existem para
         * criar apenas os novos.
         */
        $existentes = $modalidade
            ->tiposApresentacao()
            ->pluck('tipo');

        $novos = $selecionados
            ->diff($existentes)
            ->map(fn (string $tipo) => [
                'tipo' => $tipo,
            ])
            ->values()
            ->all();

        if (!empty($novos)) {
            $modalidade
                ->tiposApresentacao()
                ->createMany($novos);
        }
    }

    private function sincronizarMidiasExtras(
        UpdateModalidadeRequest $request,
        Modalidade $modalidade
    ): void {
        $ids = array_values(
            (array) $request->input(
                'docsID',
                []
            )
        );

        $documentos = array_values(
            (array) $request->input(
                'documentosExtra',
                []
            )
        );

        /*
         * Garante que somente IDs realmente
         * pertencentes à modalidade sejam usados.
         */
        $idsExistentes = empty($ids)
            ? collect()
            : $modalidade
                ->midiasExtra()
                ->whereIn('id', $ids)
                ->pluck('id');

        /*
         * Exclui documentos removidos no formulário.
         */
        if ($idsExistentes->isEmpty()) {
            $modalidade
                ->midiasExtra()
                ->delete();
        } else {
            $modalidade
                ->midiasExtra()
                ->whereNotIn(
                    'id',
                    $idsExistentes->all()
                )
                ->delete();
        }

        foreach ($documentos as $indice => $documento) {
            $dados = $this->midiaExtraPayload(
                $documento
            );

            /*
             * Os documentos existentes ocupam
             * as primeiras posições, seguindo
             * a estrutura atual do formulário.
             */
            $id = $ids[$indice] ?? null;

            if (
                $id
                && $idsExistentes->contains((int) $id)
            ) {
                $modalidade
                    ->midiasExtra()
                    ->whereKey($id)
                    ->update($dados);

                continue;
            }

            $modalidade
                ->midiasExtra()
                ->create($dados);
        }
    }

    private function midiaExtraPayload(
        array $documento
    ): array {
        $valores = array_values($documento);

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

    private function sincronizarDatasExtras(
        UpdateModalidadeRequest $request,
        Modalidade $modalidade
    ): void {
        $nomes = (array) $request->input(
            'nomeDataExtra',
            []
        );

        if (empty($nomes)) {
            $modalidade
                ->datasExtras()
                ->delete();

            return;
        }

        /*
         * Identifica quais índices realmente
         * correspondem a registros existentes.
         */
        $idsExistentes = $modalidade
            ->datasExtras()
            ->pluck('id');

        $idsMantidos = collect(
            array_keys($nomes)
        )
            ->filter(
                fn ($indice) =>
                    is_numeric($indice)
                    && $idsExistentes->contains(
                        (int) $indice
                    )
            )
            ->map(
                fn ($indice) =>
                    (int) $indice
            )
            ->values();

        /*
         * Exclui as datas removidas.
         */
        if ($idsMantidos->isEmpty()) {
            $modalidade
                ->datasExtras()
                ->delete();
        } else {
            $modalidade
                ->datasExtras()
                ->whereNotIn(
                    'id',
                    $idsMantidos->all()
                )
                ->delete();
        }

        $submissoes = (array) $request->input(
            'submissaoDataExtra',
            []
        );

        foreach ($nomes as $indice => $nome) {
            $dados = [
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
            ];

            if (
                is_numeric($indice)
                && $idsExistentes->contains(
                    (int) $indice
                )
            ) {
                $modalidade
                    ->datasExtras()
                    ->whereKey($indice)
                    ->update($dados);

                continue;
            }

            $modalidade
                ->datasExtras()
                ->create($dados);
        }
    }
}
