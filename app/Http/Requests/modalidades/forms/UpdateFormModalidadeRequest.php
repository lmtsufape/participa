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

   /**
     * Normaliza a entrada para evitar “buracos” e ruído de UI.
     */
    protected function prepareForValidation(): void
    {
        // Força arrays e remove ruídos
        $perguntas     = array_values((array) $this->input('perguntas', []));
        $pergunta_id   = array_values((array) $this->input('pergunta_id', []));
        $tipos         = array_values((array) $this->input('tipos', []));
        $visibilidades = (array) $this->input('visibilidades', []);
        $ordens        = array_values((array) $this->input('ordens', []));
        $opcoesRaw     = (array) $this->input('opcoes', []);
        $opcaoIdRaw    = (array) $this->input('opcao_id', []);

        // Trim perguntas
        $perguntas = array_map(
            fn($v) => is_string($v) ? trim($v) : $v,
            $perguntas
        );

        // Visibilidade por índice (checkbox → bool)
        $visFlags = [];
        foreach ($perguntas as $i => $_) {
            $val = $visibilidades[$i] ?? null;
            $visFlags[$i] = in_array($val, ['on','1',1,true,'true','yes','checked'], true);
        }

        // Opções: limpa vazias/whitespace, reindexa e mantém pareamento opcao_id ↔ titulo
        $opcoes   = [];
        $opcao_id = [];
        foreach ($opcoesRaw as $i => $lista) {
            $lista = is_array($lista) ? $lista : [];
            $lista = array_values(array_filter(
                array_map(fn($v) => is_string($v) ? trim($v) : $v, $lista),
                fn($v) => $v !== '' && $v !== null
            ));
            $opcoes[$i] = $lista;

            $ids = (array) ($opcaoIdRaw[$i] ?? []);
            $opcao_id[$i] = array_values($ids);
        }

        $this->merge(compact(
            'perguntas','pergunta_id','tipos','visFlags','ordens','opcoes','opcao_id'
        ));
    }

    public function rules(): array
    {
        return [
            // PERGUNTAS
            'perguntas'      => ['required','array','min:1'],
            'perguntas.*'    => ['bail','required','string','min:3','max:500'],

            // IDs das PERGUNTAS (podem vir vazios para “nova pergunta”)
            'pergunta_id'    => ['required','array'],
            'pergunta_id.*'  => ['nullable','string'], // use 'integer' se seus IDs forem int

            // TIPOS (alinhado 1:1 com perguntas)
            'tipos'          => ['required','array'],
            'tipos.*'        => ['bail','required', Rule::in(['paragrafo','radio'])],

            // ORDEM (opcional, mas recomendado)
            'ordens'         => ['array'],
            'ordens.*'       => ['nullable','integer','min:0'],

            // VISIBILIDADE (normalizada)
            'visFlags'       => ['array'],
            'visFlags.*'     => ['boolean'],

            // OPCOES (matriz): opcoes[indexPergunta][] = "titulo"
            'opcoes'         => ['array'],
            'opcoes.*'       => ['array'],
            'opcoes.*.*'     => ['nullable','string','min:1','max:255'],

            // Pareamento de IDs de opções: opcao_id[indexPergunta][] = "id"
            'opcao_id'       => ['array'],
            'opcao_id.*'     => ['array'],
            'opcao_id.*.*'   => ['nullable','string'], // 'integer' se seus IDs forem int
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $perguntas  = $this->input('perguntas', []);
            $pergIds    = $this->input('pergunta_id', []);
            $tipos      = $this->input('tipos', []);
            $opcoes     = $this->input('opcoes', []);
            $opcaoIds   = $this->input('opcao_id', []);

            // 1) Alinhamento básico: perguntas x tipos x pergunta_id
            $n = count($perguntas);
            if ($n !== count($tipos) || $n !== count($pergIds)) {
                $v->errors()->add('perguntas', 'As listas de perguntas, tipos e IDs precisam ter o mesmo tamanho.');
                return;
            }

            // 2) Condicionais por índice
            foreach ($tipos as $i => $tipo) {
                if ($tipo === 'radio') {
                    $lista = $opcoes[$i] ?? [];

                    // a) Radio precisa de ao menos 2 opções
                    if (!is_array($lista) || count($lista) < 2) {
                        $v->errors()->add("opcoes.$i", 'Perguntas do tipo "radio" precisam de ao menos duas opções.');
                        continue;
                    }

                    // b) IDs pareados com títulos (se vieram)
                    $ids = $opcaoIds[$i] ?? [];
                    if (count($ids) && count($ids) !== count($lista)) {
                        $v->errors()->add("opcao_id.$i", 'IDs de opções devem estar pareados com os títulos enviados.');
                    }

                    // c) Duplicatas na mesma pergunta (ignorando caixa/whitespace)
                    $norm = static fn(string $s) => mb_strtolower(preg_replace('/\s+/u', ' ', trim($s)));
                    $seen = [];
                    foreach ($lista as $j => $titulo) {
                        $k = $norm((string) $titulo);
                        if (isset($seen[$k])) {
                            $v->errors()->add("opcoes.$i.$j", 'Opção duplicada nesta pergunta.');
                        }
                        $seen[$k] = true;
                    }
                } else {
                    // Se for parágrafo, não deve haver opções “relevantes”
                    // (deixa passar se vier vazio; o controller limpa opcoes ao persistir)
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'perguntas.required'   => 'Informe ao menos uma pergunta.',
            'perguntas.array'      => 'Formato de perguntas inválido.',
            'perguntas.*.required' => 'O texto da pergunta é obrigatório.',
            'perguntas.*.min'      => 'Cada pergunta deve ter ao menos :min caracteres.',
            'perguntas.*.max'      => 'Cada pergunta deve ter no máximo :max caracteres.',

            'pergunta_id.required' => 'Envie a lista de IDs (mesmo que vazios) para manter o pareamento.',
            'tipos.required'       => 'Informe o tipo de cada pergunta.',
            'tipos.*.in'           => 'Tipo inválido. Use "paragrafo" ou "radio".',

            'opcoes.array'         => 'O campo de opções deve ser uma lista.',
            'opcoes.*.array'       => 'As opções de cada pergunta devem estar em lista.',
            'opcoes.*.*.min'       => 'Cada opção deve ter ao menos :min caractere.',
            'opcoes.*.*.max'       => 'Cada opção pode ter no máximo :max caracteres.',

            'ordens.*.integer'     => 'A ordem deve ser um número inteiro.',
        ];
    }

    public function attributes(): array
    {
        return [
            'perguntas.*'  => 'pergunta',
            'pergunta_id.*'=> 'ID da pergunta',
            'tipos.*'      => 'tipo',
            'opcoes.*'     => 'opções',
            'opcoes.*.*'   => 'opção',
            'opcao_id.*.*' => 'ID da opção',
            'ordens.*'     => 'ordem',
        ];
    }

    /**
     * DTO para o controller/service:
     * alinha tudo por índice e entrega um pacote claro para upsert.
     */
    public function items(): array
    {
        $items      = [];
        $perguntas  = $this->input('perguntas', []);
        $pergIds    = $this->input('pergunta_id', []);
        $tipos      = $this->input('tipos', []);
        $visFlags   = $this->input('visFlags', []);
        $ordens     = $this->input('ordens', []);
        $opcoes     = $this->input('opcoes', []);
        $opcaoIds   = $this->input('opcao_id', []);

        foreach ($perguntas as $i => $titulo) {
            $titulos = (array) ($opcoes[$i] ?? []);
            $ids     = (array) ($opcaoIds[$i] ?? []);

            // monta pares (id ↔ titulo) com ordem local
            $opts = [];
            foreach ($titulos as $j => $t) {
                $opts[] = [
                    'id'    => $ids[$j] ?? null,   // se vier, atualiza; se null, cria
                    'titulo'=> (string) $t,
                    'ordem' => $j,
                ];
            }

            $items[] = [
                'pergunta_id' => $pergIds[$i] ?: null, // null => nova
                'titulo'      => (string) $titulo,
                'tipo'        => (string) ($tipos[$i] ?? 'paragrafo'),
                'visivel'     => (bool)   ($visFlags[$i] ?? false),
                'ordem'       => is_numeric($ordens[$i] ?? null) ? (int) $ordens[$i] : $i,
                'opcoes'      => $opts, // só use se tipo=radio
            ];
        }

        return $items;
    }
}