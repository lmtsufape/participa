<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssinaturaRequest extends FormRequest
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
            'nome'              => 'required|string|min:10|max:290',
            'cargo'              => 'required|string|max:290',
            'fotoAssinatura'  => 'required|file|mimes:png,jpeg,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'fotoAssinatura.required'     => "A imagem da assinatura é obrigatória",
            'fotoAssinatura.max'          => "A imagem da assinatura deve ter no máximo 2MB",
            'fotoAssinatura.mimes'        => "A imagem da assinatura deve ser em um dos formatos permitidos",
        ];
    }
}
