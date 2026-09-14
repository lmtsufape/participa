<?php

namespace App\Http\Requests\modalidades\forms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     protected function prepareForValidation(): void
    {
        $perguntas = collect($this->input('perguntas', []))
            ->values()
            ->map(function ($pergunta, $index) {
                $tipo = $pergunta['tipo'] ?? 'paragrafo';

                $opcoes = collect($pergunta['opcoes'] ?? [])
                    ->values()
                    ->map(fn ($opcao) => [
                        'titulo' => trim($opcao['titulo'] ?? ''),
                    ])
                    ->filter(fn ($opcao) => $opcao['titulo'] !== '')
                    ->values()
                    ->map(fn ($opcao, $index) => [
                        ...$opcao,
                        'ordem' => $index + 1,
                    ])
                    ->toArray();

                return [
                    'titulo' => trim($pergunta['titulo'] ?? ''),
                    'tipo' => $tipo,
                    'ordem' => $index + 1,
                    'visibilidade' => $this->booleanValue($pergunta['visibilidade'] ?? false),
                    'opcoes' => $tipo === 'radio' ? $opcoes : [],
                ];
            })
            ->toArray();

        $this->merge([
            'titulo' => trim($this->input('titulo', '')),
            'instrucoes' => trim($this->input('instrucoes', '')),
            'perguntas' => $perguntas,
        ]);
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'min:3', 'max:255'],
            'instrucoes' => ['nullable', 'string'],

            'perguntas' => ['required', 'array', 'min:1'],
            'perguntas.*.titulo' => ['required', 'string', 'min:3', 'max:500'],
            'perguntas.*.tipo' => ['required', Rule::in(['paragrafo', 'radio'])],
            'perguntas.*.ordem' => ['required', 'integer', 'min:1'],
            'perguntas.*.visibilidade' => ['boolean'],

            'perguntas.*.opcoes' => ['array'],
            'perguntas.*.opcoes.*.titulo' => ['required', 'string', 'min:1', 'max:255'],
            'perguntas.*.opcoes.*.ordem' => ['required', 'integer', 'min:1'],
            // 'perguntas.*.opcoes.*.visibilidade' =>
            // 'perguntas.*.opcoes.*.check' =>
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->input('perguntas', []) as $i => $pergunta) {
                if (($pergunta['tipo'] ?? null) !== 'radio') {
                    continue;
                }

                $opcoes = $pergunta['opcoes'] ?? [];

                if (count($opcoes) < 2) {
                    $validator->errors()->add(
                        "perguntas.$i.opcoes",
                        'Perguntas de múltipla escolha precisam ter ao menos duas alternativas.'
                    );

                    continue;
                }

                $normalizadas = collect($opcoes)
                    ->pluck('titulo')
                    ->map(fn ($titulo) => mb_strtolower(trim(preg_replace('/\s+/u', ' ', $titulo))));

                if ($normalizadas->duplicates()->isNotEmpty()) {
                    $validator->errors()->add(
                        "perguntas.$i.opcoes",
                        'Esta pergunta possui alternativas duplicadas.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'Informe o título do formulário.',
            'titulo.min' => 'O título deve ter ao menos :min caracteres.',
            'titulo.max' => 'O título deve ter no máximo :max caracteres.',

            'perguntas.required' => 'Informe ao menos uma pergunta.',
            'perguntas.min' => 'Informe ao menos uma pergunta.',

            'perguntas.*.titulo.required' => 'O texto da pergunta é obrigatório.',
            'perguntas.*.titulo.min' => 'Cada pergunta deve ter ao menos :min caracteres.',
            'perguntas.*.titulo.max' => 'Cada pergunta deve ter no máximo :max caracteres.',

            'perguntas.*.tipo.required' => 'Informe o tipo da pergunta.',
            'perguntas.*.tipo.in' => 'Tipo inválido. Use parágrafo ou múltipla escolha.',

            'perguntas.*.opcoes.*.titulo.required' => 'O texto da alternativa é obrigatório.',
            'perguntas.*.opcoes.*.titulo.max' => 'Cada alternativa deve ter no máximo :max caracteres.',
        ];
    }

    public function formData(): array
    {
        return [
            'titulo' => $this->input('titulo'),
            'instrucoes' => $this->input('instrucoes'),
        ];
    }

    public function items(): array
    {
        return collect($this->input('perguntas', []))
            ->map(function ($pergunta) {
                return [
                    'titulo' => $pergunta['titulo'],
                    'tipo' => $pergunta['tipo'],
                    'ordem' => $pergunta['ordem'],
                    'visivel' => $pergunta['visibilidade'],
                    'opcoes' => collect($pergunta['opcoes'] ?? [])
                        ->map(fn ($opcao) => [
                            'titulo' => $opcao['titulo'],
                            'ordem' => $opcao['ordem'],
                        ])
                        ->toArray(),
                ];
            })
            ->toArray();
    }

    private function booleanValue(mixed $value): bool
    {
        return in_array($value, ['on', '1', 1, true, 'true', 'yes', 'checked'], true);
    }
}
