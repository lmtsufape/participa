<?php

namespace App\Http\Requests;

use App\Models\Submissao\Trabalho;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrabalhoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $trabalho = Trabalho::find($this->route('id'));
        $evento = $trabalho->evento;
        $mytime = Carbon::now('America/Recife');
        if($mytime > $trabalho->modalidade->fimSubmissao){
            return $this->user()->can('isCoordenadorOrComissao', $evento);
        } else {
            return $this->user()->can('isCoordenadorOrComissaoOrAutor', $trabalho);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'trabalhoEditId'                       => ['required'],
            'nomeTrabalho' . request()->id         => ['required', 'string',],
            'area' . request()->id                 => ['required', 'integer'],
            'modalidade' . request()->id           => ['required', 'integer'],
            'resumo' . request()->id               => ['nullable', 'string'],
            'nomeCoautor_' . request()->id . '.*'  => ['string'],
            'emailCoautor_' . request()->id . '.*' => ['string'],
            'arquivo' . request()->id              => ['nullable', 'file', 'max:2048'],
            'campoextra1arquivo'                   => ['nullable', 'file', 'max:2048'],
            'campoextra2arquivo'                   => ['nullable', 'file', 'max:2048'],
            'campoextra3arquivo'                   => ['nullable', 'file', 'max:2048'],
            'campoextra4arquivo'                   => ['nullable', 'file', 'max:2048'],
            'campoextra5arquivo'                   => ['nullable', 'file', 'max:2048'],
            'campoextra1simples'                   => ['nullable', 'string'],
            'campoextra2simples'                   => ['nullable', 'string'],
            'campoextra3simples'                   => ['nullable', 'string'],
            'campoextra4simples'                   => ['nullable', 'string'],
            'campoextra5simples'                   => ['nullable', 'string'],
            'campoextra1grande'                    => ['nullable', 'string'],
            'campoextra2grande'                    => ['nullable', 'string'],
            'campoextra3grande'                    => ['nullable', 'string'],
            'campoextra4grande'                    => ['nullable', 'string'],
            'campoextra5grande'                    => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'arquivo*.max' => 'O tamanho máximo permitido é de 2mb'
        ];
    }
}
