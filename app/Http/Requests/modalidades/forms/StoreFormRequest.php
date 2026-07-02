<?php

namespace App\Http\Requests\modalidades\forms;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules()
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'instrucoes' => ['nullable', 'string'],

            'perguntas' => ['required', 'array', 'min:1'],
            'perguntas.*.ordem' => ['required', 'integer', 'min:1'],
            'perguntas.*.titulo' => ['required', 'string'],
            'perguntas.*.tipo' => ['required', 'in:paragrafo,radio'],
            'perguntas.*.visibilidade' => ['nullable', 'string'],

            'perguntas.*.opcoes'         => ['nullable', 'array', 'min:2'],
            'perguntas.*.opcoes.*.ordem'       => ['nullable','integer','min:1'],
            'perguntas.*.opcoes.*.titulo'       => ['nullable','string'],

        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
