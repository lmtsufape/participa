<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use Illuminate\Foundation\Http\FormRequest;

class PalestranteStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $evento = Evento::find(request()->eventoId);

        return $this->user()->can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'eventoId' => ['required'],
            'titulo' => ['required', 'string', 'min:5'],
            'nomeDoPalestrante' => ['array', 'min:1'],
            'nomeDoPalestrante.*' => ['required', 'string', 'min:10'],
            'emailDoPalestrante' => ['array', 'min:1'],
            'emailDoPalestrante.*' => ['required', 'email'],
            'fotoPalestrante.*'     => ['nullable','file','mimes:png,jpg,jpeg','dimensions:max_width=760,max_height=360'],
        ];
    }

    public function attributes()
    {
        return [
            'nomeDoPalestrante.*' => 'nome do palestrante',
            'emailDoPalestrante.*' => 'email do palestrante',
        ];
    }
}
