<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use Illuminate\Foundation\Http\FormRequest;

class TipoComissaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $evento = $this->route('evento');
        return $this->user()->can('isCoordenadorOrCoordenadorDasComissoes', $evento);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => ['required', 'string', 'min:5']
        ];
    }
}
