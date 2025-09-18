<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\Inscricao;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InscricaoApiController extends Controller
{
    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarInscritoPorDocumento(Request $request): JsonResponse
    {
        $cpf = $request->input('cpf');
        $cnpj = $request->input('cnpj');
        $passaporte = $request->input('passaporte');

        if (!$cpf && !$cnpj && !$passaporte) {
            return response()->json([
                'status' => 'error',
                'mensagem' => 'É obrigatório informar pelo menos um documento: CPF, CNPJ ou Passaporte.'
            ], 422);
        }

        $documentosFornecidos = array_filter([$cpf, $cnpj, $passaporte]);
        if (count($documentosFornecidos) > 1) {
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Informe apenas um documento: CPF, CNPJ ou Passaporte.'
            ], 422);
        }

        if ($cpf) {
            $documento = $cpf;
            $tipoDocumento = 'cpf';
        } elseif ($cnpj) {
            $documento = $cnpj;
            $tipoDocumento = 'cnpj';
        } else {
            $documento = $passaporte;
            $tipoDocumento = 'passaporte';
        }

        $validacao = $this->validarDocumento($documento, $tipoDocumento);
        if ($validacao !== true) {
            return response()->json([
                'status' => 'error',
                'mensagem' => $validacao
            ], 422);
        }

        $user = $this->buscarUsuarioPorDocumento($documento, $tipoDocumento);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Usuário não encontrado com o documento informado.'
            ], 404);
        }

        $inscricao = Inscricao::where('user_id', $user->id)
            ->where('finalizada', true)
            ->with(['categoria', 'user', 'user.perfilIdentitario', 'user.endereco'])
            ->first();

        if (!$inscricao) {
            if (Inscricao::where('user_id', $user->id)->exists()) {
                return response()->json([
                    'status' => 'aviso',
                    'mensagem' => 'O usuário está cadastrado, mas a inscrição ainda não foi finalizada. O credenciamento não pode ser realizado.'
                ], 400);
            }
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Nenhuma inscrição finalizada encontrada para o usuário.'
            ], 404);
        }

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => 'Inscrito encontrado e qualificado para credenciamento.',
            'dados' => [
                'nome_completo' => $inscricao->user->name,
                'nome_social'   => $inscricao->user->perfilIdentitario->nomeSocial,
                'documento' => $this->obterDocumentoUsuario($inscricao->user, $tipoDocumento),
                'tipo_documento' => $tipoDocumento,
                'email' => $inscricao->user->email,
                'telefone'  => $inscricao->celular,
                'cidade'    => $inscricao->user->endereco->cidade,
                'uf'    => $inscricao->user->endereco->uf,
                'organizacao' => $inscricao->user->instituicao,
                'tipo_inscricao' => $inscricao->categoria->nome,
            ]
        ], 200);
    }

    private function validarDocumento(string $documento, string $tipoDocumento): string|bool
    {
        $rules = [];
        $messages = [];

        switch ($tipoDocumento) {
            case 'cpf':
                $rules['documento'] = ['required', 'cpf'];
                $messages = [
                    'documento.required' => 'O campo CPF é obrigatório.',
                    'documento.cpf' => 'O CPF informado não é válido.'
                ];
                break;

            case 'cnpj':
                $rules['documento'] = ['required', 'string', 'min:14', 'max:18'];
                $messages = [
                    'documento.required' => 'O campo CNPJ é obrigatório.',
                    'documento.min' => 'O CNPJ deve ter pelo menos 14 dígitos.',
                    'documento.max' => 'O CNPJ deve ter no máximo 18 caracteres.'
                ];
                break;

            case 'passaporte':
                $rules['documento'] = ['required', 'string', 'min:6', 'max:10', 'regex:/^[A-Za-z0-9]{6,10}$/'];
                $messages = [
                    'documento.required' => 'O campo Passaporte é obrigatório.',
                    'documento.min' => 'O Passaporte deve ter pelo menos 6 caracteres.',
                    'documento.max' => 'O Passaporte deve ter no máximo 10 caracteres.',
                    'documento.regex' => 'O Passaporte deve conter apenas letras e números.'
                ];
                break;
        }

        $validator = Validator::make(['documento' => $documento], $rules, $messages);

        if ($validator->fails()) {
            return $validator->errors()->first('documento');
        }

        return true;
    }

    private function maskCpf($cpf)
    {
        // Remove tudo que não é número
        $cpf = preg_replace('/\D/', '', $cpf);

        // Aplica a máscara se tiver 11 dígitos
        if (strlen($cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
        }

        return $cpf; // retorna como está se não for válido
    }

    private function buscarUsuarioPorDocumento(string $documentoOriginal, string $tipoDocumento): ?User
    {
        switch ($tipoDocumento) {
            case 'cpf':
                $cpf = $this->maskCpf($documentoOriginal);
                return User::where('cpf', $documentoOriginal)->orWhere('cpf', $cpf)->first();
            case 'cnpj':
                return User::where('cnpj', $documentoOriginal)->first();
            case 'passaporte':
                return User::where('passaporte', $documentoOriginal)->first();
        }
        return null;
    }

    private function obterDocumentoUsuario(User $user, string $tipoDocumento): string
    {
        switch ($tipoDocumento) {
            case 'cpf':
                return $user->cpf;
            case 'cnpj':
                return $user->cnpj;
            case 'passaporte':
                return $user->passaporte;
            default:
                return '';
        }
    }
}
