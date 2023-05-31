<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateCertificadoRequest extends FormRequest
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
            'local' => 'required|string|min:3|max:40',
            'nome' => 'required|string|min:5|max:290',
            'texto' => 'required|string|min:5',
            'assinaturas' => 'required_if:imagem_assinada,false',
            'data' => 'required|date',
            'tipo' => 'required',
            'verso' => 'boolean',
            'tipo_comissao_id' => 'required_if:tipo,8|exclude_unless:tipo,8',
            'atividade_id' => 'required_if:tipo,9|exclude_unless:tipo,9',
            'imagem_assinada' => 'boolean',
            'has_imagem_verso' => 'exclude_if:verso,false',
            'imagem_verso' => 'exclude_if:verso,false,|exclude_if:has_imagem_verso,false|nullable|image|max:2048'
        ];
    }

    public function attributes()
    {
        return [
            'tipo_comissao_id' => 'tipo da comissão',
            'atividade_id' => 'atividade',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'verso' => $this->has('verso'),
            'imagem_assinada' => $this->has('imagem_assinada'),
            'has_imagem_verso' => $this->has('has_imagem_verso'),
        ]);
    }
}
