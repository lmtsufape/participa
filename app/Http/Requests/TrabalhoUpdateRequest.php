<?php

namespace App\Http\Requests;

use App\Models\Submissao\MidiaExtra;
use App\Models\Submissao\Trabalho;
use App\Rules\FileType;
use App\Rules\MaxTrabalhosAutorUpdate;
use App\Rules\MaxTrabalhosCoautorUpdate;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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
        if (!$trabalho->modalidade->estaEmPeriodoDeSubmissao()) {
            return $this->user()->can('isCoordenadorOrCoordenadorDasComissoes', $evento);
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
        $id = request()->id;
        $request = request();
        $evento = Trabalho::find($id)->evento;
        $modalidade = Trabalho::find(request()->trabalhoEditId)->modalidade;

        $validate_array = [
            'trabalhoEditId'                       => ['required'],
            'nomeTrabalho'.$id                   => ['required', 'string'],
            'area'.$id                           => ['required', 'integer'],
            'modalidade'.$id                     => ['required', 'integer'],
            'resumo'.$id                         => ['nullable', 'string'],
            'emailCoautor_'.$id                  => ['required', 'array', 'min:1'],
            'nomeCoautor_'.$id.'.*'            => ['string'],
            'emailCoautor_'.$id.'.0'           => ['string', new MaxTrabalhosAutorUpdate($evento->numMaxTrabalhos)],
            'emailCoautor_'.$id.'.*'           => ['string', new MaxTrabalhosCoautorUpdate($evento->numMaxCoautores)],
            'arquivo'.$id                        => ['nullable', 'file', new FileType($modalidade, new MidiaExtra, $request['arquivo'.$id], true)],
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

        foreach ($modalidade->midiasExtra as $midia) {
            $validate_array[$midia->hyphenizeNome()] = ['nullable', 'file', new FileType($modalidade, $midia, request()[$midia->hyphenizeNome()], false)];
        }

        return $validate_array;
    }

    public function messages()
    {
        $id = request()->id;

        return [
            'arquivo*.max' => 'O tamanho máximo permitido é de 2mb',
            'emailCoautor_'.$id.'*.required' => 'O trabalho deve conter pelo seu autor.',
        ];
    }
}
