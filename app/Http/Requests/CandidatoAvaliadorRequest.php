<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CandidatoAvaliadorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'evento_id'           => 'required|integer|exists:eventos,id',
            'lattes_link' => 'required|url',
            'lattes_resumo' => 'required|string',
            'eixos'          => 'required|array|min:1|max:3',
            'eixos.*'        => 'integer|distinct|exists:areas,id',
            'avaliou_antes'        => 'required|in:sim,nao',
            'idiomas'              => 'nullable|array',
            'idiomas.*'            => 'in:nao,espanhol,ingles',
        ];
    }
}
