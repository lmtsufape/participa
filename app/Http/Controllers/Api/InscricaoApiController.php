<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\Inscricao;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
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
        $nome = $request->input('nome');

        if (!$cpf && !$cnpj && !$passaporte && !$nome) {
            return response()->json([
                'status' => 'error',
                'mensagem' => 'É obrigatório informar pelo menos um parâmetro: CPF, CNPJ, Passaporte ou Nome.'
            ], 422);
        }

        $parametrosFornecidos = array_filter([$cpf, $cnpj, $passaporte, $nome], function ($value) {
            return !empty($value);
        });
        if (count($parametrosFornecidos) > 1) {
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Informe apenas um parâmetro: CPF, CNPJ, Passaporte ou Nome.'
            ], 422);
        }

        if (!empty($nome)) {
            return $this->buscarPorNome($nome);
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
                'nome_social' => $inscricao->user->perfilIdentitario?->nomeSocial,
                'documento' => $this->obterDocumentoUsuario($inscricao->user, $tipoDocumento),
                'tipo_documento' => $tipoDocumento,
                'email' => $inscricao->user->email,
                'telefone' => $user->celular,
                'cidade' => $inscricao->user->endereco?->cidade,
                'uf' => $inscricao->user->endereco?->uf,
                'organizacao' => $inscricao->user->instituicao,
                'tipo_inscricao' => $inscricao->categoria->nome,
                'alimentacao' => $inscricao->alimentacao,
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
                $cnpj = preg_replace('/\D/', '', $documentoOriginal);

                if (strlen($cnpj) === 14) {
                    $cnpj = preg_replace(
                        '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
                        '$1.$2.$3/$4-$5',
                        $cnpj
                    );
                }

                return User::where('cnpj', $cnpj)->first();
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

    private function buscarPorNome(string $nome): JsonResponse
    {
        if (empty($nome) || strlen(trim($nome)) < 3) {
            return response()->json([
                'status' => 'error',
                'mensagem' => 'O nome deve ter pelo menos 3 caracteres.'
            ], 422);
        }

        $nomeNormalizado = $this->normalizarNome($nome);

        try{
            $users = User::whereNotNull('name')
            ->whereRaw('unaccent(lower(name)) ILIKE unaccent(lower(?))', ['%' . $nome . '%'])
            ->get();
        } catch(\Exception $e) {
            Log::error('Erro ao usar unaccent: ' . $e->getMessage());
            $users = User::get()
            ->filter(function ($user) use ($nomeNormalizado) {
                if (empty($user->name)) {
                    return false;
                }
                $nomeCompletoNormalizado = $this->normalizarNome($user->name);
                return str_contains($nomeCompletoNormalizado, $nomeNormalizado);
            });
        }

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Nenhum usuário encontrado com o nome informado.'
            ], 404);
        }

        $resultados = [];
        foreach ($users as $user) {
            $inscricao = Inscricao::where('user_id', $user->id)
                ->where('finalizada', true)
                ->with(['categoria'])
                ->first();

            if ($inscricao) {
                $resultados[] = [
                    'nome_completo' => $user->name,
                    'nome_social' => $user->perfilIdentitario?->nomeSocial,
                    'documento' => $this->obterPrimeiroDocumento($user),
                    'tipo_documento' => $this->obterTipoDocumento($user),
                    'email' => $user->email,
                    'telefone' => $user->celular,
                    'cidade' => $user->endereco?->cidade,
                    'uf' => $user->endereco?->uf,
                    'organizacao' => $user->instituicao,
                    'tipo_inscricao' => $inscricao->categoria->nome,
                    'alimentacao' => $inscricao->alimentacao,
                ];
            }
        }

        if (empty($resultados)) {
            return response()->json([
                'status' => 'aviso',
                'mensagem' => 'Usuários encontrados, mas nenhum possui inscrição finalizada.'
            ], 400);
        }

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => count($resultados) . ' inscrito(s) encontrado(s) com o nome informado.',
            'dados' => $resultados
        ], 200);
    }

    private function normalizarNome(string $nome): string
    {
        if (empty($nome)) {
            return '';
        }

        // Remove acentos e converte para minúsculas
        $nome = strtolower($nome);

        $nome = str_replace(
            [
                'á', 'à', 'ã', 'â', 'ä', 'ā', 'ǎ', 'ă', 'ą',
                'é', 'è', 'ê', 'ë', 'ē', 'ě', 'ĕ', 'ė', 'ę',
                'í', 'ì', 'î', 'ï', 'ī', 'ǐ', 'ĭ', 'į',
                'ó', 'ò', 'õ', 'ô', 'ö', 'ō', 'ǒ', 'ŏ', 'ő', 'ø',
                'ú', 'ù', 'û', 'ü', 'ū', 'ǔ', 'ŭ', 'ů', 'ű',
                'ç', 'ć', 'č', 'ĉ', 'ċ', 'ç',
                'ñ', 'ń', 'ň', 'ņ', 'ŋ',
                'ý', 'ÿ', 'ŷ', 'ỳ', 'ỵ', 'ỷ', 'ỹ',
                'ß'
            ],
            [
                'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
                'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
                'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i',
                'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
                'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
                'c', 'c', 'c', 'c', 'c', 'c',
                'n', 'n', 'n', 'n', 'n',
                'y', 'y', 'y', 'y', 'y', 'y', 'y',
                'ss'
            ],
            $nome
        );

        // Remove espaços extras e caracteres especiais
        $nome = preg_replace('/\s+/', ' ', trim($nome));
        $nome = preg_replace('/[^\w\s]/', '', $nome);

        return $nome;
    }

    private function obterPrimeiroDocumento(User $user): string
    {
        if ($user->cpf) {
            return $user->cpf;
        }
        if ($user->cnpj) {
            return $user->cnpj;
        }
        if ($user->passaporte) {
            return $user->passaporte;
        }
        return '';
    }

    private function obterTipoDocumento(User $user): string
    {
        if ($user->cpf) {
            return 'cpf';
        }
        if ($user->cnpj) {
            return 'cnpj';
        }
        if ($user->passaporte) {
            return 'passaporte';
        }
        return '';
    }
}
