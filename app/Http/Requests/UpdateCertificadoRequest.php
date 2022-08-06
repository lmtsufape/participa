<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'assinaturas' => 'required',
            'data' => 'required|date',
            'tipo' => 'required',
            'verso' => 'nullable|boolean',
            'tipo_comissao_id' => 'required_if:tipo,8|exclude_unless:tipo,8',
            'atividade_id' => 'required_if:tipo,9|exclude_unless:tipo,9',
        ];
    }

    public function attributes()
    {
        return [
            'tipo_comissao_id' => 'tipo da comissão',
            'atividade_id' => 'atividade'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'verso' => $this->has('verso'),
        ]);
    }
}
