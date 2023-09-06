<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaParticipanteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $evento = Evento::find($this->evento_id);
        return $evento && $this->user()->can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'editarCategoria' => 'required',
            "nome_{$this->id}" => 'required',
            "valor_total_{$this->id}" => 'required|numeric|min:0',
            "tipo_valor_{$this->id}.*" => 'nullable',
            "valorDesconto_{$this->id}.*" => "required_with:tipo_valor_{$this->id}.*|numeric|min:0",
            "inícioDesconto_{$this->id}.*" => "required_with:tipo_valor_{$this->id}.*|date",
            "fimDesconto_{$this->id}.*" => "required_with:tipo_valor_{$this->id}.*|date|after:inícioDesconto_{$this->id}.*",
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            "valorDesconto_[{$this->id}].*" => 'Digite um valor positivo ou 0 para gratuito.',
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
            "valorDesconto_{$this->id}.*" => 'O valor',
            'permite_submissao' => 'permite submissão'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'permite_submissao_'.$this->id => $this->has('permite_submissao_'.$this->id),
        ]);
    }
}
