<?php

namespace App\Http\Controllers;

use App\Models\Inscricao\Inscricao;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Users\User;
use App\Models\Submissao\Evento;
use App\Notifications\InscricaoEvento;
use App\Jobs\ProcessarInscricaoAutomaticaJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InscricaoAutomaticaController extends Controller
{
    public function index(Request $request)
    {
        $eventoId = $request->get('evento_id');
        $evento = null;
        $categorias = collect();
        $eventos = collect();

        // Carregar eventos do usuário autenticado
        $user = auth()->user();
        if (auth()->user()->administradors()->exists()) {
            $eventos = Evento::all();
        } else {
            // Carregar eventos onde o usuário é coordenador
            $eventos = Evento::whereHas('coordenadores', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        }

        if ($eventoId) {
            $evento = Evento::find($eventoId);
            if ($evento) {
                $categorias = CategoriaParticipante::where('evento_id', $evento->id)->get();
            }
        }

        return view('coordenador.inscricao-automatica', compact('evento', 'categorias', 'eventos'));
    }

    public function processar(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
            'evento_id' => 'required|exists:eventos,id',
            'categoria_id' => 'required|exists:categoria_participantes,id',
        ]);

        try {
            $evento = Evento::find($request->evento_id);
            $categoria = CategoriaParticipante::find($request->categoria_id);

            $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

            $arquivo = $request->file('arquivo');
            $caminhoArquivo = $arquivo->store('temp');
            $caminhoCompleto = storage_path('app/' . $caminhoArquivo);

            // Ler a planilha
            $spreadsheet = IOFactory::load($caminhoCompleto);
            $worksheet = $spreadsheet->getActiveSheet();
            $dados = $worksheet->toArray();

            // Remover cabeçalho
            $cabecalho = array_shift($dados);

            // Filtrar linhas vazias
            $dados = array_filter($dados, function($linha) {
                return !empty($linha[0]) && !empty($linha[1]);
            });

            // Gerar ID único para o job
            $jobId = uniqid('inscricao_', true);

            // Dispatch do job
            ProcessarInscricaoAutomaticaJob::dispatch($dados, $evento->id, $categoria->id, $jobId);
            
            // Log para debug
            \Log::info("Job despachado com ID: {$jobId}, Total de dados: " . count($dados));

            // Limpar arquivo temporário
            Storage::delete($caminhoArquivo);

            return redirect()->route('inscricao-automatica.progresso', ['job_id' => $jobId])
                ->with('success', 'Processamento iniciado! Acompanhe o progresso abaixo.');

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

    private function cancelarPreInscricao($user_id, $evento_id)
    {
        $preInscricao = Inscricao::where('user_id', $user_id)
                                 ->where('evento_id', $evento_id)
                                 ->where('finalizada', false)
                                 ->first();

        if ($preInscricao) {
            $preInscricao->delete();
            return true;
        }

        return false;
    }

    private function gerarPlanilhaResultado($usuariosNaoCadastrados, $usuariosJaInscritos, $usuariosInscritosComSucesso, $erros)
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

        // Usuários já inscritos
        foreach ($usuariosJaInscritos as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        // Usuários inscritos com sucesso
        foreach ($usuariosInscritosComSucesso as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        // Erros
        foreach ($erros as $erro) {
            $worksheet->setCellValue('A' . $linha, $erro['nome']);
            $worksheet->setCellValue('B' . $linha, $erro['cpf']);
            $worksheet->setCellValue('C' . $linha, $erro['email']);
            $worksheet->setCellValue('D' . $linha, $erro['status']);
            $linha++;
        }

        // Salvar arquivo temporário
        $caminhoArquivo = storage_path('app/temp/resultado_inscricao_' . time() . '.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($caminhoArquivo);

        return $caminhoArquivo;
    }

    public function progresso(Request $request)
    {
        $jobId = $request->get('job_id');
        
        if (!$jobId) {
            return redirect()->route('inscricao-automatica.index')
                ->with('error', 'ID do processamento não encontrado.');
        }

        return view('coordenador.inscricao-automatica-progresso', compact('jobId'));
    }

    public function statusProgresso(Request $request)
    {
        $jobId = $request->get('job_id');
        
        $progresso = Cache::get("inscricao_progress_{$jobId}");
        $completado = Cache::get("inscricao_completed_{$jobId}");
        
        // Log para debug
        \Log::info("Status request para job {$jobId}: progresso=" . ($progresso ? 'encontrado' : 'não encontrado') . ", completado=" . ($completado ? 'sim' : 'não'));

        if ($completado) {
            return response()->json([
                'completado' => true,
                'progresso' => 100,
                'dados' => $progresso
            ]);
        }

        if ($progresso) {
            return response()->json([
                'completado' => false,
                'progresso' => $progresso['progresso'] ?? 0,
                'processados' => $progresso['processados'] ?? 0,
                'total' => $progresso['total'] ?? 0
            ]);
        }

        return response()->json([
            'completado' => false,
            'progresso' => 0,
            'processados' => 0,
            'total' => 0
        ]);
    }

    public function downloadResultado(Request $request)
    {
        $jobId = $request->get('job_id');
        
        $dados = Cache::get("inscricao_progress_{$jobId}");
        
        if (!$dados) {
            return redirect()->route('inscricao-automatica.index')
                ->with('error', 'Dados do processamento não encontrados.');
        }

        $planilhaResultado = $this->gerarPlanilhaResultado(
            $dados['usuariosNaoCadastrados'] ?? [],
            $dados['usuariosJaInscritos'] ?? [],
            $dados['usuariosInscritosComSucesso'] ?? [],
            $dados['erros'] ?? []
        );

        // Limpar cache
        Cache::forget("inscricao_progress_{$jobId}");
        Cache::forget("inscricao_completed_{$jobId}");

        return response()->download($planilhaResultado, 'resultado_inscricao_automatica.xlsx')
            ->deleteFileAfterSend(true);
    }
}
