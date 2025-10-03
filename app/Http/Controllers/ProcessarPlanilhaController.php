<?php

namespace App\Http\Controllers;

use App\Models\Inscricao\Inscricao;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProcessarPlanilhaController extends Controller
{
    public function index()
    {
        return view('coordenador.processar-planilha');
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

            // Ler a planilha
            $spreadsheet = IOFactory::load($caminhoCompleto);
            $worksheet = $spreadsheet->getActiveSheet();
            $dados = $worksheet->toArray();

            // Remover cabeçalho
            $cabecalho = array_shift($dados);

            $usuariosNaoCadastrados = [];
            $usuariosComAlimentacaoOk = [];
            $usuariosComInscricaoPendente = [];

            foreach ($dados as $linha) {
                if (empty($linha[0]) || empty($linha[1])) {
                    continue; // Pular linhas vazias
                }

                $nome = $linha[0];
                $cpf = $this->normalizarCpf($linha[1]);
                $email = $linha[2] ?? '';

                // Verificar se o usuário existe pelo CPF
                $usuario = User::where('cpf', $cpf)->first();

                if (!$usuario) {
                    $usuariosNaoCadastrados[] = [
                        'nome' => $nome,
                        'cpf' => $cpf,
                        'email' => $email,
                        'status' => 'Usuário não cadastrado'
                    ];
                    continue;
                }

                // Verificar status da inscrição
                $inscricao = Inscricao::where('user_id', $usuario->id)
                    ->where('finalizada', true)
                    ->first();

                if ($inscricao) {
                    // Atualizar campo alimentação para true
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

            // Gerar planilha de resultado
            $planilhaResultado = $this->gerarPlanilhaResultado(
                $usuariosNaoCadastrados,
                $usuariosComAlimentacaoOk,
                $usuariosComInscricaoPendente
            );

            // Limpar arquivo temporário
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
        // Remover todos os caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Se tiver 11 dígitos, formatar com pontuação
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

        // Cabeçalho
        $worksheet->setCellValue('A1', 'Nome');
        $worksheet->setCellValue('B1', 'CPF');
        $worksheet->setCellValue('C1', 'Email');
        $worksheet->setCellValue('D1', 'Status');

        $linha = 2;

        // Usuários não cadastrados
        foreach ($usuariosNaoCadastrados as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        // Usuários com alimentação OK
        foreach ($usuariosComAlimentacaoOk as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        // Usuários com inscrição pendente
        foreach ($usuariosComInscricaoPendente as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        // Salvar arquivo temporário
        $caminhoArquivo = storage_path('app/temp/resultado_' . time() . '.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($caminhoArquivo);

        return $caminhoArquivo;
    }
}

