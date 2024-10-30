<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArquivoInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('isCoordenadorOrCoordenadorDasComissoes', request()->evento);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => ['required', 'string'],
            'arquivo' => ['required', 'file', 'max:10240', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,pdf,doc,docx,pptx'],
        ];
    }
}
