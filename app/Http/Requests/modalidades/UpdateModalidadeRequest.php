<?php

namespace App\Http\Requests\modalidades;

use App\Models\Submissao\Modalidade;

class UpdateModalidadeRequest extends ModalidadeRequest
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
        return Modalidade::with('evento')
            ->findOrFail(
                $this->route('modalidade_id')
            )
            ->evento;
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),

            'deleteapresentacao' => [
                'nullable',
                'boolean',
            ],

            'deleteregra' => [
                'nullable',
                'boolean',
            ],

            'deletetemplate' => [
                'nullable',
                'boolean',
            ],

            'deleteinstrucoes' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
