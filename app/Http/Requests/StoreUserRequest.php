<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        if($this->filled('email')){
            $this->merge([
                'email' => mb_strtolower(trim($this->input('email'))),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'max:255'],
            'nomeSocial' => ['nullable', 'string', 'max:255'],
            'email' => ['bail', 'required', 'email', Rule::unique('users', 'email')],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'documento_tipo' => ['required', 'in:cpf,cnpj,passaporte'],
            'cpf' => ['exclude_unless:documento_tipo,cpf', 'required', 'cpf', Rule::unique('users', 'cpf')],
            'cnpj' => ['exclude_unless:documento_tipo,cnpj', 'required', 'cnpj', Rule::unique('users', 'cnpj')],
            'passaporte' => ['exclude_unless:documento_tipo,passaporte', 'required', 'min:6', 'max:9', 'regex:/^[A-Za-z0-9]{6,9}$/', Rule::unique('users', 'passaporte')],
            'celular' => ['nullable', 'string', 'max:16'],
            'instituicao' => ['nullable','string','max:255','regex:~^[\p{L}\p{M}0-9 .\-(){}\[\],;&@%*+=/\\\\|<>!?`\'"]*$~u'],
            'especialidade' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rua' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'uf' => ['nullable', 'string'],
            'cep' => ['nullable', 'string'],
            'genero' => ['nullable', 'string'],
            'outroGenero' => ['nullable', 'string', 'max:255'],
            'raca' => ['nullable', 'string'],
            'outraRaca' => ['nullable', 'required_if:raca,outra_raca', 'string', 'max:255'],
            'comunidadeTradicional' => ['nullable', 'string'],
            'nomeComunidadeTradicional' => ['nullable', 'string', 'max:255'],
            'lgbtqia' => ['nullable'],
            'deficienciaIdoso' => ['nullable'],
            'participacaoOrganizacao' => ['nullable'],
            'nomeOrganizacao' => ['nullable', 'string', 'max:255'],
            'necessidadesEspeciais' => ['nullable'],
            'outraNecessidadeEspecial' => ['nullable', 'string', 'max:255'],
            'vinculoInstitucional' => ['nullable', 'string', 'max:255'],
        ];
    }

     public function payload()
    {
        $data = $this->validated();

        $user = [
            'name' => $data['name'],
            'email' => $data['email'],
            'data_nascimento' => $data['data_nascimento'],
            'cpf' => $data['cpf'] ?? null,
            'cnpj' => $data['cnpj'] ?? null,
            'passaporte' => $data['passaporte'] ?? null,
            'celular' => $data['celular'],
            'instituicao' => $data['instituicao'],
            'password' => bcrypt($data['password']),
        ];

        $endereco = [
            'rua' => $data['rua'],
            'numero' => $data['numero'],
            'bairro' => $data['bairro'],
            'cidade' => $data['cidade'],
            'complemento' => $data['complemento'],
            'cep' => $data['cep'],
            'uf' => $data['uf'],
        ];

        $perfilIdentitario = [
            'genero' => $data['genero'] ?? null,
            'nomeSocial' => $data['nomeSocial'],
            'outroGenero' => $data['outroGenero'] ?? null,
            'raca' => $data['raca'] ?? null,
            'outraRaca' => $data['outraRaca'] ?? null,
            'comunidadeTradicional' => $data['comunidadeTradicional'] ?? null,
            'nomeComunidadeTradicional' => $data['nomeComunidadeTradicional'] ?? null,
            'lgbtqia' => $data['lgbtqia'] ?? null,
            'deficienciaIdoso' => $data['deficienciaIdoso'] ?? null,
            'participacaoOrganizacao' => $data['participacaoOrganizacao'] ?? null,
            'nomeOrganizacao' => $data['nomeOrganizacao'] ?? null,
            'necessidadesEspeciais' => $data['necessidadesEspeciais'] ?? null,
            'outraNecessidadeEspecial' => $data['outraNecessidadeEspecial'] ?? null,
            'vinculoInstitucional' => $data['vinculoInstitucional'] ?? null,
        ];
        return [
            'user' => $user,
            'perfilIdentitario' => $perfilIdentitario,
            'endereco' => $endereco
            ];

    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe seu nome completo.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.max' => 'O nome não pode ultrapassar 255 caracteres.',

            'nomeSocial.string' => 'O nome social deve ser um texto válido.',
            'nomeSocial.max' => 'O nome social não pode ultrapassar 255 caracteres.',

            'email.required' => 'Informe seu endereço de e-mail.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado na plataforma.',

            'data_nascimento.required' => 'Informe sua data de nascimento.',
            'data_nascimento.date' => 'Informe uma data de nascimento válida.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior à data atual.',

            'documento_tipo.required' => 'Selecione o tipo de documento.',
            'documento_tipo.in' => 'Selecione um tipo de documento válido.',

            'cpf.required_if' => 'Informe seu CPF.',
            'cpf.cpf' => 'Informe um CPF válido.',
            'cpf.unique' => 'Este CPF já está cadastrado na plataforma.',

            'cnpj.required_if' => 'Informe seu CNPJ.',
            'cnpj.cnpj' => 'Informe um CNPJ válido.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado na plataforma.',

            'passaporte.required_if' => 'Informe o número do passaporte.',
            'passaporte.min' => 'O passaporte deve conter pelo menos 6 caracteres.',
            'passaporte.max' => 'O passaporte não pode ultrapassar 9 caracteres.',
            'passaporte.regex' => 'O passaporte deve conter apenas letras e números, entre 6 e 9 caracteres.',
            'passaporte.unique' => 'Este passaporte já está cadastrado na plataforma.',

            'celular.string' => 'Informe um número de celular válido.',
            'celular.max' => 'O celular não pode ultrapassar 16 caracteres.',

            'instituicao.string' => 'O nome da instituição deve ser um texto válido.',
            'instituicao.max' => 'O nome da instituição não pode ultrapassar 255 caracteres.',
            'instituicao.regex' => 'O nome da instituição contém caracteres não permitidos.',

            'especialidade.string' => 'A especialidade deve ser um texto válido.',

            'vinculoInstitucional.string' => 'O vínculo institucional deve ser um texto válido.',
            'vinculoInstitucional.max' => 'O vínculo institucional não pode ultrapassar 255 caracteres.',

            'password.string' => 'A senha deve ser um texto válido.',
            'password.min' => 'A senha deve conter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde à senha informada.',

            'rua.string' => 'O nome da rua deve ser um texto válido.',
            'rua.max' => 'O nome da rua não pode ultrapassar 255 caracteres.',

            'numero.string' => 'O número do endereço deve ser um texto válido.',

            'bairro.string' => 'O nome do bairro deve ser um texto válido.',
            'bairro.max' => 'O nome do bairro não pode ultrapassar 255 caracteres.',

            'cidade.string' => 'O nome da cidade deve ser um texto válido.',
            'cidade.max' => 'O nome da cidade não pode ultrapassar 255 caracteres.',

            'complemento.string' => 'O complemento deve ser um texto válido.',
            'complemento.max' => 'O complemento não pode ultrapassar 255 caracteres.',

            'uf.string' => 'Informe uma unidade federativa válida.',

            'cep.string' => 'Informe um CEP válido.',

            'outraRaca.required_if' => 'Informe sua raça ou cor quando selecionar a opção "Outra raça".',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome completo',
            'nomeSocial' => 'nome social',
            'email' => 'e-mail',
            'data_nascimento' => 'data de nascimento',
            'documento_tipo' => 'tipo de documento',
            'cpf' => 'CPF',
            'cnpj' => 'CNPJ',
            'passaporte' => 'passaporte',
            'celular' => 'celular',
            'instituicao' => 'instituição',
            'especialidade' => 'especialidade',
            'password' => 'senha',
            'rua' => 'rua',
            'numero' => 'número',
            'bairro' => 'bairro',
            'cidade' => 'cidade',
            'complemento' => 'complemento',
            'uf' => 'estado',
            'cep' => 'CEP',
            'genero' => 'gênero',
            'outroGenero' => 'outro gênero',
            'raca' => 'raça ou cor',
            'outraRaca' => 'outra raça ou cor',
            'comunidadeTradicional' => 'comunidade tradicional',
            'nomeComunidadeTradicional' => 'nome da comunidade tradicional',
            'lgbtqia' => 'LGBTQIA+',
            'deficienciaIdoso' => 'deficiência ou condição de pessoa idosa',
            'participacaoOrganizacao' => 'participação em organização',
            'nomeOrganizacao' => 'nome da organização',
            'necessidadesEspeciais' => 'necessidades especiais',
            'outraNecessidadeEspecial' => 'outra necessidade especial',
            'vinculoInstitucional' => 'vínculo institucional',
        ];
    }
}
