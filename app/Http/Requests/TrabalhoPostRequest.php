<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use App\Models\Submissao\MidiaExtra;
use App\Models\Submissao\Modalidade;
use App\Rules\CoautorCadastrado;
use App\Rules\CoautorInscritoNoEvento;
use App\Rules\FileType;
use App\Rules\MaxTrabalhosCoautor;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxCoautoresNaModalidade;

class TrabalhoPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $modalidade = Modalidade::find($this->request->get('modalidade_id'));
        $mytime = Carbon::now('America/Recife');
        $evento = Evento::find($modalidade->eventoId);
        // if (! $modalidade->estaEmPeriodoDeSubmissao()) {
        //     return $this->user()->can('isCoordenadorOrCoordenadorDasComissoes', $evento);
        // }
        // if (! $modalidade->estaEmPeriodoDeSubmissao()) {
        //     return redirect()->route('home');
        // }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $modalidade = Modalidade::find(request()->modalidade_id);
        $validate_array = [
            'nomeTrabalho' => ['required', 'string'],
            'nomeTrabalho_en' => ['nullable', 'string'],
            'area_id'       => ['required', 'exists:areas,id'],
            'modalidade_id' => ['required', 'exists:modalidades,id'],
            'evento_id'     => ['required', 'exists:eventos,id'],
            'resumo' => ['nullable', 'string'],
            'resumo_en' => ['nullable', 'string'],
            'autor.email' => ['required', 'email'],
            'autor.nome'  => ['required', 'string', 'max:255'],
            'coautores' => ['array', new MaxCoautoresNaModalidade($modalidade)],
            'coautores.*.nome'  => ['required','string','max:255'],
            'coautores.*.email' => ['required','email:rfc', 'distinct', 'different:autor.email'],
            'arquivo' => ['required', 'file', new FileType($modalidade, new MidiaExtra, request()->arquivo, true)],
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
            'campoextra1simples_en' => ['nullable', 'string'],
            'campoextra2simples_en' => ['nullable', 'string'],
            'campoextra3simples_en' => ['nullable', 'string'],
            'campoextra4simples_en' => ['nullable', 'string'],
            'campoextra5simples_en' => ['nullable', 'string'],
            'campoextra1grande_en' => ['nullable', 'string'],
            'campoextra2grande_en' => ['nullable', 'string'],
            'campoextra3grande_en' => ['nullable', 'string'],
            'campoextra4grande_en' => ['nullable', 'string'],
            'campoextra5grande_en' => ['nullable', 'string'],
        ];

        foreach ($modalidade->midiasExtra as $midia) {
            $validate_array[$midia->hyphenizeNome] = ['required', 'file', new FileType($modalidade, $midia, request()[$midia->hyphenizeNome], false)];
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
