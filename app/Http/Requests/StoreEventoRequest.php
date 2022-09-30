<?php

namespace App\Http\Requests;

use App\Rules\NaoESubEvento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreEventoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome'        => ['required', 'string'],
            'descricao'   => ['required', 'string'],
            'tipo'        => ['required', 'string'],
            'dataInicio'  => ['required', 'date', 'after:yesterday'],
            'dataFim'     => ['required', 'date'],
            'fotoEvento'  => ['file', 'mimes:png, jpg,jpeg'],
            'icone'  => ['file', 'mimes:png, jpg,jpeg'],
            'rua'         => ['required', 'string'],
            'numero'      => ['required', 'string'],
            'bairro'      => ['required', 'string'],
            'cidade'      => ['required', 'string'],
            'uf'          => ['required', 'string'],
            'cep'         => ['required', 'string'],
            'complemento' => ['nullable', 'string'],
            'eventoPai'   => ['nullable', new NaoESubEvento],
            'email_coordenador' => ['exclude_if:eventoPai,null','exclude_if:email_coordenador,null', 'nullable', 'email'],
            'termos'      => ['required'],
            'dataLimiteInscricao'   => ['nullable', 'date'],
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
}
