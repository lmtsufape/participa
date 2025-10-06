<?php

namespace App\Http\Controllers;

use App\Models\Inscricao\Inscricao;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PlanilhaAlimentacaoController extends Controller
{
    public function index()
    {
        return view('coordenador.inscricoes.planilha-alimentacao');
    }

    public function processar(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            $arquivo = $request->file('arquivo');
            $caminhoArquivo = $arquivo->store('temp');
            $caminhoCompleto = storage_path('app/' . $caminhoArquivo);

            $spreadsheet = IOFactory::load($caminhoCompleto);
            $worksheet = $spreadsheet->getActiveSheet();
            $dados = $worksheet->toArray();

            $cabecalho = array_shift($dados);

            $usuariosNaoCadastrados = [];
            $usuariosComAlimentacaoOk = [];
            $usuariosComInscricaoPendente = [];

            foreach ($dados as $linha) {
                if (empty($linha[0])) {
                    continue; 
                }

                $nome = $linha[0];
                $cpf = !empty($linha[1]) ? $this->normalizarCpf($linha[1]) : null;
                $email = $linha[2] ?? '';

                $usuario = null;
                if ($cpf) {
                    $usuario = User::where('cpf', $cpf)->first();
                }
                
                if (!$usuario && $email) {
                    $usuario = User::where('email', $email)->first();
                }

                if (!$usuario) {
                    $status = 'Usuário não cadastrado';
                    if (!$cpf && !$email) {
                        $status = 'CPF e email não informados';
                    } elseif (!$cpf) {
                        $status = 'CPF não informado - Email não encontrado no sistema';
                    } elseif (!$email) {
                        $status = 'Email não informado - CPF não encontrado no sistema';
                    }
                    
                    $usuariosNaoCadastrados[] = [
                        'nome' => $nome,
                        'cpf' => $cpf ?? 'Não informado',
                        'email' => $email ?: 'Não informado',
                        'status' => $status
                    ];
                    continue;
                }

                $inscricao = Inscricao::where('user_id', $usuario->id)
                    ->where('finalizada', true)
                    ->first();

                if ($inscricao) {
                    $inscricao->update(['alimentacao' => true]);

                    $usuariosComAlimentacaoOk[] = [
                        'nome' => $nome,
                        'cpf' => $cpf,
                        'email' => $email,
                        'status' => 'Inscrição finalizada - Alimentação liberada'
                    ];
                } else {
                    $usuariosComInscricaoPendente[] = [
                        'nome' => $nome,
                        'cpf' => $cpf,
                        'email' => $email,
                        'status' => 'Inscrição pendente'
                    ];
                }
            }

            $planilhaResultado = $this->gerarPlanilhaResultado(
                $usuariosNaoCadastrados,
                $usuariosComAlimentacaoOk,
                $usuariosComInscricaoPendente
            );

            Storage::delete($caminhoArquivo);

            return response()->download($planilhaResultado, 'resultado_processamento.xlsx')
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao processar arquivo: ' . $e->getMessage());
        }
    }

    private function normalizarCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) === 11) {
            return substr($cpf, 0, 3) . '.' . 
                   substr($cpf, 3, 3) . '.' . 
                   substr($cpf, 6, 3) . '-' . 
                   substr($cpf, 9, 2);
        }
        return $cpf;
    }

    private function gerarPlanilhaResultado($usuariosNaoCadastrados, $usuariosComAlimentacaoOk, $usuariosComInscricaoPendente)
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->setCellValue('A1', 'Nome');
        $worksheet->setCellValue('B1', 'CPF');
        $worksheet->setCellValue('C1', 'Email');
        $worksheet->setCellValue('D1', 'Status');

        $linha = 2;

        foreach ($usuariosNaoCadastrados as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        foreach ($usuariosComAlimentacaoOk as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        foreach ($usuariosComInscricaoPendente as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        $caminhoArquivo = storage_path('app/temp/resultado_' . time() . '.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($caminhoArquivo);

        return $caminhoArquivo;
    }
}

