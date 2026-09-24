<?php

namespace App\Http\Requests\modalidades;

use App\Enums\TipoArquivo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

abstract class ModalidadeRequest extends FormRequest
{
    abstract protected function evento();

    public function rules(): array
    {
        $evento = $this->evento();

        return [
            'nome' => ['required', 'string', 'min:2'],

            'nome_en' => [
                Rule::requiredIf($evento->is_multilingual),
                'nullable',
                'string',
            ],

            'nome_es' => [
                Rule::requiredIf($evento->is_multilingual),
                'nullable',
                'string',
            ],

            'numMaxCoautores' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'inicioSubmissao' => [
                'required',
                'date',
            ],

            'fimSubmissao' => [
                'required',
                'date',
                'after:inicioSubmissao',
            ],

            'inicioRevisao' => [
                'required',
                'date',
                'after_or_equal:inicioSubmissao',

                Rule::when(
                    !$this->boolean('avaliacaoDuranteSubmissao'),
                    ['after_or_equal:fimSubmissao']
                ),
            ],

            'fimRevisao' => [
                'required',
                'date',
                'after:inicioRevisao',
            ],

            'inicioCorrecao' => [
                'required',
                'date',
                'after:fimRevisao',
                'required_with:fimCorrecao',
            ],

            'fimCorrecao' => [
                'required',
                'date',
                'after:inicioCorrecao',
                'required_with:inicioCorrecao',
            ],

            'inicioValidacao' => [
                'nullable',
                'date',
                'required_with:fimValidacao',
            ],

            'fimValidacao' => [
                'nullable',
                'date',
                'after:inicioValidacao',
                'required_with:inicioValidacao',
            ],

            'inicioResultado' => [
                'required',
                'date',
                'after:fimRevisao',
            ],

            'avaliacaoDuranteSubmissao' => [
                'nullable',
                'boolean',
            ],

            'texto' => [
                'nullable',
                'boolean',
            ],

            'limit' => [
                'required_if:texto,1',
                'nullable',
                'in:caracteres,palavras',
            ],

            'mincaracteres' => [
                'exclude_unless:texto,1',
                'required_if:limit,caracteres',
                'nullable',
                'integer',
                'min:0',
                'lte:maxcaracteres',
            ],

            'maxcaracteres' => [
                'exclude_unless:texto,1',
                'required_if:limit,caracteres',
                'nullable',
                'integer',
                'min:0',
                'gte:mincaracteres',
            ],

            'minpalavras' => [
                'exclude_unless:texto,1',
                'required_if:limit,palavras',
                'nullable',
                'integer',
                'min:0',
                'lte:maxpalavras',
            ],

            'maxpalavras' => [
                'exclude_unless:texto,1',
                'required_if:limit,palavras',
                'nullable',
                'integer',
                'min:0',
                'gte:minpalavras',
            ],

            'arquivo' => [
                'nullable',
                'boolean',
            ],

            ...collect(TipoArquivo::cases())
                ->mapWithKeys(fn (TipoArquivo $tipo) => [
                    $tipo->value => [
                        'exclude_unless:arquivo,1',
                        'nullable',
                        'boolean',
                    ],
                ])
                ->all(),

            'apresentacao' => [
                'nullable',
                'boolean',
            ],

            'presencial' => [
                'exclude_unless:apresentacao,1',
                'nullable',
                'boolean',
            ],

            'remoto' => [
                'exclude_unless:apresentacao,1',
                'nullable',
                'boolean',
            ],

            'a_distancia' => [
                'exclude_unless:apresentacao,1',
                'nullable',
                'boolean',
            ],

            'semipresencial' => [
                'exclude_unless:apresentacao,1',
                'nullable',
                'boolean',
            ],

            'submissaoUnica' => [
                'nullable',
                'boolean',
            ],

            'arquivoRegras' => [
                'nullable',
                'file',
                'max:10240',
                'mimes:pdf',
            ],

            'arquivoInstrucoes' => [
                'nullable',
                'file',
                'max:2048',
                'mimes:pdf',
            ],

            'arquivoModelos' => [
                'nullable',
                'file',
                'max:2048',
                'mimes:odt,ott,docx,doc,rtf,txt,pdf,odp,ppt,pptx',
            ],

            'arquivoTemplates' => [
                'nullable',
                'file',
                'max:2048',
                'mimes:odt,ott,docx,doc,rtf,txt,pdf,pptx',
            ],

            'documentosExtra' => [
                'nullable',
                'array',
            ],

            'documentosExtra.*' => [
                'array',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'limit.required_if' =>
                'A opção de resumo por texto foi selecionada, mas nenhuma restrição foi selecionada.',

            'mincaracteres.required_if' =>
                'O campo mínimo é obrigatório quando quantidade de caracteres está selecionada.',

            'maxcaracteres.required_if' =>
                'O campo máximo é obrigatório quando quantidade de caracteres está selecionada.',

            'minpalavras.required_if' =>
                'O campo mínimo é obrigatório quando quantidade de palavras está selecionada.',

            'maxpalavras.required_if' =>
                'O campo máximo é obrigatório quando quantidade de palavras está selecionada.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $this->validarTiposArquivo($validator);
                $this->validarTiposApresentacao($validator);
                $this->validarMidiasExtras($validator);
            },
        ];
    }

    public function payload(): array
    {
        return [
            'nome' => $this->input('nome'),
            'nome_en' => $this->input('nome_en'),
            'nome_es' => $this->input('nome_es'),

            'numMaxCoautores' =>
                $this->input('numMaxCoautores'),

            'inicioSubmissao' =>
                $this->input('inicioSubmissao'),

            'fimSubmissao' =>
                $this->input('fimSubmissao'),

            'inicioRevisao' =>
                $this->input('inicioRevisao'),

            'fimRevisao' =>
                $this->input('fimRevisao'),

            'inicioCorrecao' =>
                $this->input('inicioCorrecao'),

            'fimCorrecao' =>
                $this->input('fimCorrecao'),

            'inicioValidacao' =>
                $this->input('inicioValidacao'),

            'fimValidacao' =>
                $this->input('fimValidacao'),

            'inicioResultado' =>
                $this->input('inicioResultado'),

            'avaliacaoDuranteSubmissao' =>
                $this->boolean('avaliacaoDuranteSubmissao'),

            'arquivo' =>
                $this->boolean('arquivo'),

            'apresentacao' =>
                $this->boolean('apresentacao'),

            'submissaoUnica' =>
                $this->boolean('submissaoUnica'),

            ...$this->resumoPayload(),

            ...$this->tiposArquivoPayload(),
        ];
    }

    public function tiposApresentacao(): array
    {
        if (!$this->boolean('apresentacao')) {
            return [];
        }

        $tipos = [
            'presencial' => 'Presencial',
            'remoto' => 'Remoto',
            'a_distancia' => 'À distância',
            'semipresencial' => 'Semipresencial',
        ];

        return collect($tipos)
            ->filter(
                fn (string $tipo, string $campo) =>
                    $this->boolean($campo)
            )
            ->values()
            ->all();
    }

    private function resumoPayload(): array
    {
        if (!$this->boolean('texto')) {
            return [
                'texto' => false,
                'caracteres' => false,
                'palavras' => false,
                'mincaracteres' => null,
                'maxcaracteres' => null,
                'minpalavras' => null,
                'maxpalavras' => null,
            ];
        }

        $porCaracteres =
            $this->input('limit') === 'caracteres';

        return [
            'texto' => true,
            'caracteres' => $porCaracteres,
            'palavras' => !$porCaracteres,

            'mincaracteres' => $porCaracteres
                ? $this->integer('mincaracteres')
                : null,

            'maxcaracteres' => $porCaracteres
                ? $this->integer('maxcaracteres')
                : null,

            'minpalavras' => !$porCaracteres
                ? $this->integer('minpalavras')
                : null,

            'maxpalavras' => !$porCaracteres
                ? $this->integer('maxpalavras')
                : null,
        ];
    }

    private function tiposArquivoPayload(): array
    {
        return collect(TipoArquivo::cases())
            ->mapWithKeys(fn (TipoArquivo $tipo) => [
                $tipo->value =>
                    $this->boolean('arquivo')
                        ? $this->boolean($tipo->value)
                        : false,
            ])
            ->all();
    }

    private function validarTiposArquivo(
        Validator $validator
    ): void {
        if (!$this->boolean('arquivo')) {
            return;
        }

        $possuiTipo = collect(TipoArquivo::cases())
            ->contains(
                fn (TipoArquivo $tipo) =>
                    $this->boolean($tipo->value)
            );

        if (!$possuiTipo) {
            $validator->errors()->add(
                'tipos_arquivo',
                'Selecione pelo menos um tipo de arquivo permitido.'
            );
        }
    }

    private function validarTiposApresentacao(
        Validator $validator
    ): void {
        if (!$this->boolean('apresentacao')) {
            return;
        }

        $possuiTipo = collect([
            'presencial',
            'remoto',
            'a_distancia',
            'semipresencial',
        ])->contains(
            fn (string $campo) =>
                $this->boolean($campo)
        );

        if (!$possuiTipo) {
            $validator->errors()->add(
                'tipos_apresentacao',
                'Selecione pelo menos uma forma de apresentação.'
            );
        }
    }

    private function validarMidiasExtras(
        Validator $validator
    ): void {
        foreach (
            (array) $this->input('documentosExtra', [])
            as $indice => $documento
        ) {
            if (!is_array($documento)) {
                continue;
            }

            $valores = array_values($documento);

            $nome = array_shift($valores);

            if (blank($nome)) {
                $validator->errors()->add(
                    "documentosExtra.{$indice}",
                    'Informe o nome do documento.'
                );

                continue;
            }

            $possuiExtensao = collect($valores)
                ->contains(
                    fn ($extensao) =>
                        is_string($extensao)
                        && TipoArquivo::tryFrom($extensao) !== null
                );

            if (!$possuiExtensao) {
                $validator->errors()->add(
                    "documentosExtra.{$indice}",
                    'Selecione pelo menos um tipo de extensão.'
                );
            }
        }
    }
}
