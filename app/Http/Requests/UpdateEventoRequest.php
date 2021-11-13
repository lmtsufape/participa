<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateEventoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $evento = Evento::find(request()->id);
        return auth()->user()->can('isCoordenador', $evento);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome'        => ['required', 'string'],
            'descricao'   => ['required', 'string'],
            'tipo'        => ['required', 'string'],
            'dataInicio'  => ['required', 'date'],
            'dataFim'     => ['required', 'date'],
            'fotoEvento'  => ['file', 'mimes:png,jpg,jpeg'],
            'rua'         => ['required', 'string'],
            'numero'      => ['required', 'string'],
            'bairro'      => ['required', 'string'],
            'cidade'      => ['required', 'string'],
            'uf'          => ['required', 'string'],
            'cep'         => ['required', 'string'],
            'complemento' => ['nullable', 'string'],
        ];
    }
}
