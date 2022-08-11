<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemoriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('evento') && $this->user()->can('isCoordenadorOrCoordenadorDasComissoes', $this->route('evento'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titulo' => ['required', 'string'],
            'arquivo' => [
                'nullable',
                'file',
                'max:2048',
                function ($attribute, $value, $fail) {
                    if ($value && $this->link) {
                        $fail('Informe somente um link ou arquivo');
                    }
                },
            ],
            'link' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value && $this->arquivo) {
                        $fail('Informe somente um link ou arquivo');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            '*.required_if' => 'Informe um link ou arquivo',
        ];
    }
}
