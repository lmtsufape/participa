<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificadoRequest extends FormRequest
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
            'tipo' => 'required',
            'fotoCertificado' => 'required|file|mimes:png,jpeg,jpg|max:2048',
            'assinaturas' => 'required_if:imagem_assinada,false',
            'data' => 'required|date',
            'tipo_comissao_id' => 'required_if:tipo,8|exclude_unless:tipo,8',
            'atividade_id' => 'required_if:tipo,9|exclude_unless:tipo,9',
            'verso' => 'required|boolean',
            'imagem_assinada' => 'nullable|boolean',
            'has_imagem_verso' => 'exclude_if:verso,false',
            'imagem_verso' => 'exclude_if:verso,false,|exclude_if:has_imagem_verso,false|required_if:has_imagem_verso,true|image',
        ];
    }

    public function attributes()
    {
        return [
            'tipo_comissao_id' => 'tipo da comissão',
            'atividade_id' => 'atividade',
        ];
    }

    public function messages()
    {
        return [
            'assinaturas.required' => 'Selecione ao menos uma assinatura para o certificado',
            'fotoCertificado.required' => 'A imagem do certificado é obrigatória',
            'fotoCertificado.max' => 'A imagem do certificado deve ter no máximo 2MB',
            'fotoCertificado.mimes' => 'A imagem do certificado deve ser em um dos formatos permitidos',
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
