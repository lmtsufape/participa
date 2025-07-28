<?php

namespace App\Http\Requests;

use App\Rules\NaoESubEvento;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth()->user();
        return $user != null && $user->administradors()->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => ['required', 'string'],
            'nome_en' => [ 'nullable','string'],
            'descricao' => ['required', 'string'],
            'descricao_en' => ['nullable', 'string'],
            'nome_es' => ['nullable', 'string'],
            'descricao_es' => ['nullable', 'string'],
            'tipo' => ['required', 'string'],
            'dataInicio' => ['required', 'date', 'after:yesterday'],
            'dataFim' => ['required', 'date'],
            'fotoEvento'     => ['required','file','mimes:png,jpg,jpeg','dimensions:max_width=1024,max_height=425'],
            'fotoEvento_en'  => ['nullable','file','mimes:png,jpg,jpeg','dimensions:max_width=1024,max_height=425'],
            'fotoEvento_es'  => ['nullable','file','mimes:png,jpg,jpeg','dimensions:max_width=1024,max_height=425'],
            'icone'          => ['required','file','mimes:png,jpg,jpeg','dimensions:max_width=600,max_height=600'],
            'icone_en'       => ['nullable','file','mimes:png,jpg,jpeg','dimensions:max_width=600,max_height=600'],
            'icone_es'       => ['nullable','file','mimes:png,jpg,jpeg','dimensions:max_width=600,max_height=600'],
            'rua' => ['required', 'string'],
            'numero' => ['required', 'string'],
            'bairro' => ['required', 'string'],
            'cidade' => ['required', 'string'],
            'uf' => ['required', 'string'],
            'cep' => ['required', 'string'],
            'complemento' => ['nullable', 'string'],
            'eventoPai' => ['nullable', new NaoESubEvento],
            'email_coordenador' => ['exclude_if:eventoPai,null', 'exclude_if:email_coordenador,null', 'nullable', 'email'],
            'termos' => ['required'],
            'dataLimiteInscricao' => ['nullable', 'date'],
            'instagram' => ['nullable', 'string'],
            'contato_suporte' => ['nullable', 'string'],

        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'termos.required' => 'É necessário concordar com os termos de uso',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            // 'email' => 'email address',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes(['nome_en', 'descricao_en', 'nome_es', 'descricao_es'], 'required|string', function ($input) {
            return filter_var($input->is_multilingual, FILTER_VALIDATE_BOOLEAN);
        });
    }
}
