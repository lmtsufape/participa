<?php

namespace App\Http\Requests\avaliacoes;

use App\Models\Submissao\Form;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAvaliacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'revisor_id' => [
                'required',
                'integer',
                'exists:revisors,id',
            ],

            'trabalho_id' => [
                'required',
                'integer',
                'exists:trabalhos,id',
            ],

            'modalidade_id' => [
                'required',
                'integer',
                'exists:modalidades,id',
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

            'arquivo' => [
                'nullable',
                'file',
                'mimes:pdf,odt,docx,rtf',
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

            if (!$pergunta->visibilidade) {
                continue;
            }

            $respostaPadrao = $pergunta->respostasPadrao->first();

            if (!$respostaPadrao) {
                continue;
            }

            /*
             * Pergunta de opções
             */
            if ($respostaPadrao->opcoes->isNotEmpty()) {

                $rules["respostas.{$pergunta->id}"] = [
                    'required',
                    'integer',

                    Rule::exists('opcaos', 'id')
                        ->where(function ($query) use ($respostaPadrao) {
                            $query->where(
                                'resposta_id',
                                $respostaPadrao->id
                            );
                        }),
                ];

                continue;
            }

            /*
             * Pergunta de parágrafo
             */
            if ($respostaPadrao->paragrafo) {

                $rules["respostas.{$pergunta->id}"] = [
                    'required',
                    'string',
                    'max:10000',
                ];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'respostas.required' =>
                'As respostas da avaliação são obrigatórias.',

            'respostas.array' =>
                'As respostas da avaliação são inválidas.',

            'respostas.*.required' =>
                'Esta pergunta deve ser respondida.',

            'respostas.*.integer' =>
                'A opção selecionada é inválida.',

            'respostas.*.string' =>
                'A resposta informada é inválida.',

            'respostas.*.max' =>
                'A resposta não pode possuir mais de 10.000 caracteres.',

            'arquivo.file' =>
                'O arquivo enviado é inválido.',

            'arquivo.mimes' =>
                'O arquivo deve estar no formato PDF, DOCX, ODT ou RTF.',
        ];
    }
}
