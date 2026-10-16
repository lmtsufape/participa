<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $evento = Evento::findOrFail($this->route('evento_id'));

         return [
            'nome' => ['required', 'string', 'max:255'],
            'resumo' => ['nullable', 'string', 'max:255'],

            'nome_en' => [
                Rule::when($evento->is_multilingual, 'required', 'exclude'),
                'string',
                'max:255',
            ],
            'resumo_en' => [
                Rule::when(!$evento->is_multilingual, 'exclude'),
                'nullable',
                'string',
                'max:255',
            ],

            'nome_es' => [
                Rule::when($evento->is_multilingual, 'required', 'exclude'),
                'string',
                'max:255',
            ],
            'resumo_es' => [
                Rule::when(!$evento->is_multilingual, 'exclude'),
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome.',
            'nome.string' => 'O nome deve ser um texto válido.',
            'nome.max' => 'O nome não pode ultrapassar 255 caracteres.',

            'resumo.string' => 'O resumo deve ser um texto válido.',
            'resumo.max' => 'O resumo não pode ultrapassar 255 caracteres.',

            'nome_en.required' => 'Informe o nome em inglês.',
            'nome_en.string' => 'O nome em inglês deve ser um texto válido.',
            'nome_en.max' => 'O nome em inglês não pode ultrapassar 255 caracteres.',

            'resumo_en.string' => 'O resumo em inglês deve ser um texto válido.',
            'resumo_en.max' => 'O resumo em inglês não pode ultrapassar 255 caracteres.',

            'nome_es.required' => 'Informe o nome em espanhol.',
            'nome_es.string' => 'O nome em espanhol deve ser um texto válido.',
            'nome_es.max' => 'O nome em espanhol não pode ultrapassar 255 caracteres.',

            'resumo_es.string' => 'O resumo em espanhol deve ser um texto válido.',
            'resumo_es.max' => 'O resumo em espanhol não pode ultrapassar 255 caracteres.',
        ];
    }
}
