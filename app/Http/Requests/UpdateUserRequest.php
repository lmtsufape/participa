<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'nomeSocial' => 'nullable|string|max:255',
            'email' => ['required', 'email'],
            'data_nascimento' => ['required', 'date'],
            'documento_tipo' => ['required', 'in:cpf,cnpj,passaporte'],
            'cpf' => ['nullable','required_if:documento_tipo,cpf', 'cpf'],
            'cnpj' => ['nullable','required_if:documento_tipo,cnpj', 'cnpj'],
            'passaporte' => ['nullable','required_if:documento_tipo,passaporte', 'min:6', 'max:9', 'regex:/^[A-Za-z0-9]{6,9}$/'],
            'celular' => 'nullable|string|max:16',
            'instituicao' => ['nullable','string','max:255','regex:~^[\p{L}\p{M}0-9 .\-(){}\[\],;&@%*+=/\\\\|<>!?`\'"]*$~u'],
            'especialidade' => 'nullable|string',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'uf' => 'nullable|string',
            'cep' => 'nullable|string',
            'genero' => ['nullable'],
            'outroGenero' => ['nullable'],
            'raca' => ['nullable'],
            'outraRaca' => ['required_if:raca,outra_raca'],
            'comunidadeTradicional' => ['nullable'],
            'nomeComunidadeTradicional' => ['nullable'],
            'lgbtqia' => ['nullable'],
            'deficienciaIdoso' => ['nullable'],
            'participacaoOrganizacao' => ['nullable'],
            'nomeOrganizacao' => ['nullable'],
            'necessidadesEspeciais' => ['nullable'],
            'outraNecessidadeEspecial' => ['nullable'],
            'vinculoInstitucional' => ['nullable'],
            ];
    }

    public function payload()
    {
        $data = $this->validated();

        $user = [
            'name' => $data['name'],
            'email' => $data['email'],
            'data_nascimento' => $data['data_nascimento'],
            'cpf' => $data['cpf'],
            'cnpj' => $data['cnpj'],
            'passaporte' => $data['passaporte'],
            'celular' => $data['celular'],
            'instituicao' => $data['instituicao'],
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
            'data_nascimento' => $data['data_nascimento'],
            'genero' => $data['genero'] ?? null,
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
}
