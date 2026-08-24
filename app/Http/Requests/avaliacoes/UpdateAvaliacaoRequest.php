<?php

namespace App\Http\Requests\avaliacoes;

use App\Models\Submissao\Form;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAvaliacaoRequest extends FormRequest
{
       public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'trabalho_id' => [
                'required',
                'integer',
                'exists:trabalhos,id',
            ],

            'revisor_id' => [
                'required',
                'integer',
                'exists:revisors,id',
            ],

            'form_id' => [
                'required',
                'integer',
                'exists:forms,id',
            ],

            'respostas' => [
                'required',
                'array',
            ],

            'visibilidade' => [
                'nullable',
                'array',
            ],

            'arquivoAvaliacao' => [
                'nullable',
                'file',
                'max:5120',
            ],
        ];


        $form = Form::with([
            'perguntas.respostasPadrao.opcoes',
            'perguntas.respostasPadrao.paragrafo',
        ])->find($this->input('form_id'));


        if (!$form) {
            return $rules;
        }


        foreach ($form->perguntas as $pergunta) {

            $respostaPadrao = $pergunta
                ->respostasPadrao
                ->first();

            if (!$respostaPadrao) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Pergunta de opções
            |--------------------------------------------------------------------------
            */

            if ($respostaPadrao->opcoes->isNotEmpty()) {

                $rules["respostas.{$pergunta->id}"] = [
                    'required',
                    'integer',

                    Rule::exists('opcaos', 'id')
                        ->where(
                            fn ($query) =>
                                $query->where(
                                    'resposta_id',
                                    $respostaPadrao->id
                                )
                        ),
                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Pergunta de parágrafo
            |--------------------------------------------------------------------------
            */

            if ($respostaPadrao->paragrafo) {

                $rules["respostas.{$pergunta->id}"] = [
                    'required',
                    'string',
                    'max:10000',
                ];


                /*
                 * Checkbox só é enviado quando marcado.
                 */
                $rules["visibilidade.{$pergunta->id}"] = [
                    'nullable',
                    'boolean',
                ];
            }
        }


        return $rules;
    }


    public function messages(): array
    {
        return [
            'trabalho_id.required' =>
                'O trabalho é obrigatório.',

            'trabalho_id.exists' =>
                'O trabalho informado é inválido.',

            'revisor_id.required' =>
                'O avaliador é obrigatório.',

            'revisor_id.exists' =>
                'O avaliador informado é inválido.',

            'form_id.required' =>
                'O formulário é obrigatório.',

            'form_id.exists' =>
                'O formulário informado é inválido.',


            'respostas.required' =>
                'As respostas da avaliação são obrigatórias.',

            'respostas.array' =>
                'As respostas da avaliação são inválidas.',

            'respostas.*.required' =>
                'Esta pergunta deve possuir uma resposta.',

            'respostas.*.integer' =>
                'A opção selecionada é inválida.',

            'respostas.*.string' =>
                'A resposta informada é inválida.',

            'respostas.*.max' =>
                'A resposta não pode possuir mais de 10.000 caracteres.',

            'respostas.*.exists' =>
                'A opção selecionada não pertence a esta pergunta.',


            'visibilidade.array' =>
                'As configurações de visibilidade são inválidas.',

            'visibilidade.*.boolean' =>
                'A configuração de visibilidade informada é inválida.',


            'arquivoAvaliacao.file' =>
                'O arquivo enviado é inválido.',

            'arquivoAvaliacao.max' =>
                'O arquivo não pode possuir mais de 5 MB.',
        ];
    }
}
