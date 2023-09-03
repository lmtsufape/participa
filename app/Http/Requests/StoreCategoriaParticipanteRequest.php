<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaParticipanteRequest extends FormRequest
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
            'nome' => 'required',
            'valor_total' => 'required|numeric|min:0',
            'tipo_valor.*' => 'nullable',
            'valorDesconto.*' => 'required_with:tipo_valor.*|numeric|min:0',
            'inícioDesconto.*' => 'required_with:tipo_valor.*|date',
            'fimDesconto.*' => 'required_with:tipo_valor.*|date|after:inícioDesconto.*',
            'permite_submissao' => 'boolean',
            'permite_inscricao' => 'boolean',
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
            'valor_total.min' => 'Digite um valor positivo ou 0 para gratuito.',
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
            'valorDesconto.*' => 'O valor',
            'permite_submissao' => 'permite submissão',
            'permite_inscricao' => 'permite inscrição',
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
            'permite_submissao' => $this->has('permite_submissao'),
            'permite_inscricao' => $this->has('permite_inscricao'),
        ]);
    }
}
