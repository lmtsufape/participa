<?php

namespace App\Http\Requests\modalidades;

use App\Models\Submissao\Evento;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreModalidadeRequest extends ModalidadeRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function evento()
    {
        return Evento::findOrFail(
            $this->route('evento_id')
        );
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            ...parent::rules(),

        ];
    }
}
