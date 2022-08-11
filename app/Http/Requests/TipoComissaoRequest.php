<?php

namespace App\Http\Requests;

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
        $comissao = $this->route('comissao');
        $loggedUser = auth()->user();

        return policy($evento)->isCoordenadorOrCoordenadorDasComissoes($loggedUser, $evento) || policy($comissao)->isCoordenadorDeOutraComissao($loggedUser, $comissao);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => ['required', 'string', 'min:5'],
        ];
    }
}
