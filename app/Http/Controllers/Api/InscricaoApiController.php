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
    public function buscarInscritoPorCpf(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cpf' => ['required', 'cpf'],
        ], [
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.cpf'      => 'O CPF informado não é válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'mensagem' => $validator->errors(),
            ], 422);
        }

        $cpfLimpo = preg_replace('/[^0-9]/', '', $request->cpf);

        $user = User::where('cpf', $request->cpf)->orWhere('cpf', $cpfLimpo)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Usuário não encontrado com o CPF informado.'
            ], 404);
        }

        $inscricao = Inscricao::where('user_id', $user->id)
            ->where('finalizada', true)
            ->with(['categoria', 'user'])
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
                'cpf' => $inscricao->user->cpf,
                'organizacao' => $inscricao->user->instituicao,
                'tipo_inscricao' => $inscricao->categoria->nome,
            ]
        ], 200);
    }
}