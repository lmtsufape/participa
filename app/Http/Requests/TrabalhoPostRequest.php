<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use App\Models\Submissao\Modalidade;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TrabalhoPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $modalidade = Modalidade::find($this->route('id'));
        $mytime = Carbon::now('America/Recife');
        $evento = Evento::find(request()->eventoId);
        if ($mytime > $modalidade->fimSubmissao) {
            return $this->user()->can('isCoordenadorOrComissao', $evento);
        }
        if ($evento->inicioSubmissao > $mytime) {
            if ($mytime >= $evento->fimSubmissao) {
                return redirect()->route('home');
            }
        }
        return 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nomeTrabalho'       => ['required', 'string',],
            'areaId'             => ['required', 'integer'],
            'modalidadeId'       => ['required', 'integer'],
            'eventoId'           => ['required', 'integer'],
            'resumo'             => ['nullable', 'string'],
            'nomeCoautor.*'      => ['string'],
            'emailCoautor.*'     => ['string'],
            'arquivo'            => ['nullable', 'max:2048'],
            'campoextra1arquivo' => ['nullable', 'file', 'max:2048'],
            'campoextra2arquivo' => ['nullable', 'file', 'max:2048'],
            'campoextra3arquivo' => ['nullable', 'file', 'max:2048'],
            'campoextra4arquivo' => ['nullable', 'file', 'max:2048'],
            'campoextra5arquivo' => ['nullable', 'file', 'max:2048'],
            'campoextra1simples' => ['nullable', 'string'],
            'campoextra2simples' => ['nullable', 'string'],
            'campoextra3simples' => ['nullable', 'string'],
            'campoextra4simples' => ['nullable', 'string'],
            'campoextra5simples' => ['nullable', 'string'],
            'campoextra1grande'  => ['nullable', 'string'],
            'campoextra2grande'  => ['nullable', 'string'],
            'campoextra3grande'  => ['nullable', 'string'],
            'campoextra4grande'  => ['nullable', 'string'],
            'campoextra5grande'  => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'arquivo.max' => 'O tamanho máximo permitido é de 2mb'
        ];
    }
}
