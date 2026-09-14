<?php

namespace App\Http\Requests\modalidades\forms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFormModalidadeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

       protected function prepareForValidation(): void
    {
        $perguntas = collect($this->input('perguntas', []))
            ->values()
            ->map(function ($pergunta, $index) {
                $pergunta = is_array($pergunta)
                    ? $pergunta
                    : [];

                $tipo = $pergunta['tipo'] ?? null;

                $opcoes = collect($pergunta['opcoes'] ?? [])
                    ->map(function ($opcao) {
                        $opcao = is_array($opcao)
                            ? $opcao
                            : [];

                        return [
                            'id' => $this->emptyToNull(
                                $opcao['id'] ?? null
                            ),

                            'titulo' => $this->trimValue(
                                $opcao['titulo'] ?? null
                            ),
                        ];
                    })

                    // Remove opções completamente vazias.
                    // Como ID e título estão no mesmo array,
                    // o pareamento nunca é perdido.
                    ->filter(
                        fn ($opcao) =>
                            $opcao['titulo'] !== ''
                            && $opcao['titulo'] !== null
                    )

                    ->values()

                    // Gera ordem novamente após remover vazios.
                    ->map(function ($opcao, $index) {
                        return [
                            'id' => $opcao['id'],
                            'titulo' => $opcao['titulo'],
                            'ordem' => $index + 1,
                        ];
                    })

                    ->toArray();

                return [
                    'id' => $this->emptyToNull(
                        $pergunta['id'] ?? null
                    ),

                    'titulo' => $this->trimValue(
                        $pergunta['titulo'] ?? null
                    ),

                    'tipo' => $tipo,

                    // A ordem é definida pelo próprio Request.
                    'ordem' => $index + 1,

                    'visibilidade' => $this->booleanValue(
                        $pergunta['visibilidade'] ?? false
                    ),

                    // Parágrafo nunca possui opções.
                    'opcoes' => $tipo === 'radio'
                        ? $opcoes
                        : [],
                ];
            })
            ->toArray();

        $this->merge([
            'titulo' => $this->trimValue(
                $this->input('titulo')
            ),

            'instrucoes' => $this->trimValue(
                $this->input('instrucoes')
            ),

            'perguntas' => $perguntas,
        ]);
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Formulário
            |--------------------------------------------------------------------------
            */

            'titulo' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'instrucoes' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Perguntas
            |--------------------------------------------------------------------------
            */

            'perguntas' => [
                'required',
                'array',
                'min:1',
            ],

            /*
             * null = pergunta nova
             * valor = pergunta já existente
             */
            'perguntas.*.id' => [
                'nullable',
                'integer',
                'distinct',
            ],

            'perguntas.*.titulo' => [
                'bail',
                'required',
                'string',
                'min:3',
                'max:500',
            ],

            'perguntas.*.tipo' => [
                'bail',
                'required',
                Rule::in([
                    'paragrafo',
                    'radio',
                ]),
            ],

            'perguntas.*.ordem' => [
                'required',
                'integer',
                'min:1',
            ],

            'perguntas.*.visibilidade' => [
                'required',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Opções
            |--------------------------------------------------------------------------
            */

            'perguntas.*.opcoes' => [
                'array',
            ],

            /*
             * null = opção nova
             * valor = opção já existente
             */
            'perguntas.*.opcoes.*.id' => [
                'nullable',
                'integer',
                'distinct',
            ],

            'perguntas.*.opcoes.*.titulo' => [
                'bail',
                'required',
                'string',
                'min:1',
                'max:255',
            ],

            'perguntas.*.opcoes.*.ordem' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    /**
     * Validações dependentes do tipo da pergunta.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            foreach (
                $this->input('perguntas', [])
                as $perguntaIndex => $pergunta
            ) {
                /*
                 * Somente perguntas de múltipla escolha
                 * precisam validar opções.
                 */
                if (($pergunta['tipo'] ?? null) !== 'radio') {
                    continue;
                }

                $opcoes = $pergunta['opcoes'] ?? [];

                /*
                 * Perguntas radio precisam possuir
                 * pelo menos duas alternativas.
                 */
                if (count($opcoes) < 2) {
                    $validator->errors()->add(
                        "perguntas.$perguntaIndex.opcoes",
                        'Perguntas de múltipla escolha precisam ter ao menos duas alternativas.'
                    );

                    continue;
                }

                /*
                 * Normaliza os títulos para detectar
                 * opções duplicadas.
                 *
                 * Exemplo:
                 *
                 * "Sim"
                 * " sim "
                 * "SIM"
                 *
                 * são consideradas iguais.
                 */
                $normalizadas = collect($opcoes)
                    ->pluck('titulo')
                    ->filter(fn ($titulo) => is_string($titulo))
                    ->map(function ($titulo) {
                        $titulo = preg_replace(
                            '/\s+/u',
                            ' ',
                            trim($titulo)
                        );

                        return mb_strtolower($titulo);
                    });

                if ($normalizadas->duplicates()->isNotEmpty()) {
                    $validator->errors()->add(
                        "perguntas.$perguntaIndex.opcoes",
                        'Esta pergunta possui alternativas duplicadas.'
                    );
                }
            }
        });
    }

    /**
     * Mensagens personalizadas.
     */
    public function messages(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Formulário
            |--------------------------------------------------------------------------
            */

            'titulo.required' =>
                'Informe o título do formulário.',

            'titulo.string' =>
                'O título do formulário deve ser um texto.',

            'titulo.min' =>
                'O título deve ter ao menos :min caracteres.',

            'titulo.max' =>
                'O título deve ter no máximo :max caracteres.',

            'instrucoes.string' =>
                'As instruções devem ser um texto.',

            /*
            |--------------------------------------------------------------------------
            | Perguntas
            |--------------------------------------------------------------------------
            */

            'perguntas.required' =>
                'Informe ao menos uma pergunta.',

            'perguntas.array' =>
                'Formato de perguntas inválido.',

            'perguntas.min' =>
                'Informe ao menos uma pergunta.',

            'perguntas.*.id.integer' =>
                'O identificador da pergunta é inválido.',

            'perguntas.*.id.distinct' =>
                'Uma mesma pergunta foi enviada mais de uma vez.',

            'perguntas.*.titulo.required' =>
                'O texto da pergunta é obrigatório.',

            'perguntas.*.titulo.string' =>
                'O texto da pergunta deve ser válido.',

            'perguntas.*.titulo.min' =>
                'Cada pergunta deve ter ao menos :min caracteres.',

            'perguntas.*.titulo.max' =>
                'Cada pergunta deve ter no máximo :max caracteres.',

            'perguntas.*.tipo.required' =>
                'Informe o tipo da pergunta.',

            'perguntas.*.tipo.in' =>
                'Tipo inválido. Use parágrafo ou múltipla escolha.',

            'perguntas.*.ordem.required' =>
                'A ordem da pergunta é obrigatória.',

            'perguntas.*.ordem.integer' =>
                'A ordem da pergunta deve ser um número inteiro.',

            'perguntas.*.ordem.min' =>
                'A ordem da pergunta deve começar em 1.',

            'perguntas.*.visibilidade.required' =>
                'Informe a visibilidade da pergunta.',

            'perguntas.*.visibilidade.boolean' =>
                'A visibilidade da pergunta é inválida.',

            /*
            |--------------------------------------------------------------------------
            | Opções
            |--------------------------------------------------------------------------
            */

            'perguntas.*.opcoes.array' =>
                'As alternativas devem ser enviadas como uma lista.',

            'perguntas.*.opcoes.*.id.integer' =>
                'O identificador da alternativa é inválido.',

            'perguntas.*.opcoes.*.id.distinct' =>
                'Uma mesma alternativa foi enviada mais de uma vez.',

            'perguntas.*.opcoes.*.titulo.required' =>
                'O texto da alternativa é obrigatório.',

            'perguntas.*.opcoes.*.titulo.string' =>
                'O texto da alternativa deve ser válido.',

            'perguntas.*.opcoes.*.titulo.min' =>
                'Cada alternativa deve ter ao menos :min caractere.',

            'perguntas.*.opcoes.*.titulo.max' =>
                'Cada alternativa deve ter no máximo :max caracteres.',

            'perguntas.*.opcoes.*.ordem.required' =>
                'A ordem da alternativa é obrigatória.',

            'perguntas.*.opcoes.*.ordem.integer' =>
                'A ordem da alternativa deve ser um número inteiro.',

            'perguntas.*.opcoes.*.ordem.min' =>
                'A ordem da alternativa deve começar em 1.',
        ];
    }

    /**
     * Nomes amigáveis dos campos.
     */
    public function attributes(): array
    {
        return [
            'titulo' => 'título',
            'instrucoes' => 'instruções',

            'perguntas' => 'perguntas',
            'perguntas.*.id' => 'ID da pergunta',
            'perguntas.*.titulo' => 'pergunta',
            'perguntas.*.tipo' => 'tipo da pergunta',
            'perguntas.*.ordem' => 'ordem da pergunta',
            'perguntas.*.visibilidade' => 'visibilidade',

            'perguntas.*.opcoes' => 'alternativas',
            'perguntas.*.opcoes.*.id' => 'ID da alternativa',
            'perguntas.*.opcoes.*.titulo' => 'alternativa',
            'perguntas.*.opcoes.*.ordem' => 'ordem da alternativa',
        ];
    }

    /**
     * Dados referentes exclusivamente ao formulário.
     */
    public function formData(): array
    {
        return [
            'titulo' => $this->input('titulo'),
            'instrucoes' => $this->input('instrucoes'),
        ];
    }

    /**
     * Retorna as perguntas no formato esperado
     * pelo Controller/Service.
     */
    public function items(): array
    {
        return collect($this->input('perguntas', []))
            ->map(function ($pergunta) {
                return [
                    /*
                     * null = criar nova pergunta
                     * id   = atualizar pergunta existente
                     */
                    'pergunta_id' => $pergunta['id'] ?? null,

                    'titulo' => $pergunta['titulo'],

                    'tipo' => $pergunta['tipo'],

                    'ordem' => $pergunta['ordem'],

                    'visivel' => $pergunta['visibilidade'],

                    'opcoes' => collect(
                        $pergunta['opcoes'] ?? []
                    )
                        ->map(function ($opcao) {
                            return [
                                /*
                                 * null = criar nova opção
                                 * id   = atualizar opção existente
                                 */
                                'id' => $opcao['id'] ?? null,

                                'titulo' => $opcao['titulo'],

                                'ordem' => $opcao['ordem'],
                            ];
                        })
                        ->toArray(),
                ];
            })
            ->toArray();
    }

    /**
     * Converte valores vindos de checkboxes
     * para boolean.
     */
    private function booleanValue(mixed $value): bool
    {
        return in_array(
            $value,
            [
                'on',
                '1',
                1,
                true,
                'true',
                'yes',
                'checked',
            ],
            true
        );
    }

    /**
     * Remove espaços extras sem converter
     * valores inválidos silenciosamente para string.
     */
    private function trimValue(mixed $value): mixed
    {
        return is_string($value)
            ? trim($value)
            : $value;
    }

    /**
     * Inputs hidden vazios normalmente chegam como "".
     * Para atualização, queremos tratá-los como null.
     */
    private function emptyToNull(mixed $value): mixed
    {
        return $value === '' || $value === null
            ? null
            : $value;
    }
}
