<?php

namespace App\Http\Requests;

use App\Models\Submissao\Evento;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ModalidadeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $evento = Evento::find(request()->eventoId);
        return $this->user()->can('isCoordenadorOrCoordenadorDasComissoes', $evento);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome'              => ['required', 'string'],
            'inicioSubmissao'   => ['required', 'date'],
            'fimSubmissao'      => ['required', 'date', 'after:inicioSubmissao'],
            'inicioRevisao'     => ['nullable', 'date', 'after:inicioSubmissao'],
            'fimRevisao'        => ['nullable', 'date', 'after:inicioRevisao'],
            'inicioCorrecao'    => ['nullable', 'date', 'after:fimRevisao', 'required_with:fimCorrecao'],
            'fimCorrecao'       => ['nullable', 'date', 'after:inicioCorrecao', 'required_with:inicioCorrecao'],
            'inicioValidacao'   => ['nullable', 'date', 'after:fimCorrecao', 'required_with:fimValidacao'],
            'fimValidacao'      => ['nullable', 'date', 'after:inicioValidacao', 'required_with:inicioValidacao'],
            'inicioResultado'   => ['required', 'date', 'after:fimSubmissao'],
            'texto'             => ['nullable'],
            'limit'             => ['required_with:texto', 'nullable'],
            'arquivo'           => ['nullable'],
            'apresentacao'      => ['nullable'],
            'pdf'               => ['exclude_unless:arquivo,on', 'required_without_all:jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'jpg'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'jpeg'              => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'png'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'docx'              => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'odt'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'zip'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'svg'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'mp4'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'mp3'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'ogg'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'wav'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'ogv'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,mpg,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'mpg'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpeg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'mpeg'              => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mkv,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'mkv'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,avi,odp,pptx,csv,ods,xlsx', 'boolean'],
            'avi'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,odp,pptx,csv,ods,xlsx', 'boolean'],
            'odp'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,pptx,csv,ods,xlsx', 'boolean'],
            'pptx'              => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,csv,ods,xlsx', 'boolean'],
            'csv'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,ods,xlsx', 'boolean'],
            'ods'               => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,xlsx', 'boolean'],
            'xlsx'              => ['exclude_unless:arquivo,on', 'required_without_all:pdf,jpg,jpeg,png,docx,odt,zip,svg,mp4,mp3,ogg,wav,ogv,mpg,mpeg,mkv,avi,odp,pptx,csv,ods', 'boolean'],
            'mincaracteres'     => ['required_if:limit,limit-option1', 'nullable', 'integer', 'lte:maxcaracteres'],
            'maxcaracteres'     => ['required_if:limit,limit-option1', 'nullable', 'integer', 'gte:mincaracteres'],
            'minpalavras'       => ['required_if:limit,limit-option2', 'nullable', 'integer', 'lte:maxpalavras'],
            'maxpalavras'       => ['required_if:limit,limit-option2', 'nullable', 'integer', 'gte:minpalavras'],
            'arquivoRegras'     => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'arquivoInstrucoes' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'arquivoModelo'     => ['nullable', 'file', 'mimes:odt,ott,docx,doc,rtf,pdf,ppt,pptx,odp', 'max:2048'],
            'arquivosTemplates' => ['nullable', 'file', 'mimes:odt,ott,docx,doc,rtf,txt,pdf', 'max:2048'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $failedRules = $validator->failed();
        if (array_key_exists('pdf', $failedRules) && array_key_exists('RequiredWithoutAll', $failedRules['pdf'])) {
            $validator->errors()->add('arquivo', 'O campo arquivo foi selecionado, mas nenhuma extensão foi selecionada.');
        }
        throw (new ValidationException($validator))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl());
    }

    public function messages()
    {
        return [
            'limit.required_with' => 'A opção de resumo por texto foi selecionada, mas nenhuma restrição de resumo foi selecionada.'
        ];
    }

}
