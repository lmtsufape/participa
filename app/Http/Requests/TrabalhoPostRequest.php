<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use App\Models\Submissao\MidiaExtra;
use App\Models\Submissao\Modalidade;
use App\Rules\FileType;
use App\Rules\MaxTrabalhosCoautor;
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
        if (! $modalidade->estaEmPeriodoDeSubmissao()) {
            return $this->user()->can('isCoordenadorOrCoordenadorDasComissoes', $evento);
        }
        if (! $modalidade->estaEmPeriodoDeSubmissao()) {
            return redirect()->route('home');
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
        $evento = Evento::find(request()->eventoId);
        $modalidade = Modalidade::find(request()->modalidadeId);
        $validate_array = [
            'nomeTrabalho' => ['required', 'string'],
            'areaId' => ['required', 'integer'],
            'modalidadeId' => ['required', 'integer'],
            'eventoId' => ['required', 'integer'],
            'resumo' => ['nullable', 'string'],
            'nomeCoautor.*' => ['string'],
            'emailCoautor.*' => ['string', new MaxTrabalhosCoautor($evento->numMaxCoautores)],
            'arquivo' => ['nullable', 'file', new FileType($modalidade, new MidiaExtra, request()->arquivo, true)],
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
            'campoextra1grande' => ['nullable', 'string'],
            'campoextra2grande' => ['nullable', 'string'],
            'campoextra3grande' => ['nullable', 'string'],
            'campoextra4grande' => ['nullable', 'string'],
            'campoextra5grande' => ['nullable', 'string'],
        ];

        foreach ($modalidade->midiasExtra as $midia) {
            $validate_array[$midia->hyphenizeNome()] = ['required', 'file', new FileType($modalidade, $midia, request()[$midia->hyphenizeNome()], false)];
        }

        return $validate_array;
    }

    public function messages()
    {
        return [
            'arquivo.max' => 'O tamanho máximo permitido é de 2mb',
        ];
    }

    public function attributes()
    {
        return [
            'nomeTrabalho' => 'título do trabalho',
            'areaId' => 'área',
            'modalidadeId' => 'modalidade',
            'eventoId' => 'evento',
            'resumo' => 'resumo',
            'nomeCoautor.*' => 'nome',
            'emailCoautor.*' => 'email',
        ];
    }
}
