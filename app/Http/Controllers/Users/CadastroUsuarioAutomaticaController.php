<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Endereco;
use App\Models\Users\User;
use App\Models\PerfilIdentitario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;

class CadastroUsuarioAutomaticaController extends Controller
{
    // Mapeamento das colunas da sua planilha para os campos do sistema.
    const COLUMNS = [
        'nome'                          => 1,  // Coluna B: Nome Completo *
        'cpf'                           => 2,  // Coluna C: CPF *
        'email'                         => 3,  // Coluna D: E-mail *
        'nomeSocial'                    => 4,  // Coluna E: Nome Social
        'instituicao'                   => 5,  // Coluna F: Instituição *
        'celular'                       => 6,  // Coluna G: Celular *
        'dataNascimento'                => 7,  // Coluna H: Data de Nascimento *
        'cidade'                        => 8,  // Coluna I: Cidade*
        'logradouro_complemento'        => 9,  // Coluna J: Comunidade/ Aldeia/ Quilombo
        'uf'                            => 10, // Coluna K: Estado *
        'genero'                        => 11, // Coluna L: Perfil Social e Identitário *
        'raca'                          => 12, // Coluna M: Raça *
        'comunidadeTradicionalRaw'      => 13, // Coluna N: Você pertence ou atua em alguma comunidade ou povo tradicional? *
        'lgbtqiaRaw'                    => 14, // Coluna O: Você se identifica como Pessoa LGBTQIA+? *
        'necessidadesEspeciaisRaw'      => 15, // Coluna P: Informações sobre necessidades *
        'deficienciaIdosoRaw'           => 16, // Coluna Q: Você é uma pessoa idosa ou com deficiência? *
        'associadoAbaRaw'               => 17, // Coluna R: Você é uma pessoa associada à Associação Brasileira de Agroecologia (ABA-Agroecologia)? *
        'receberInfoAbaRaw'             => 18, // Coluna S: Se não, gostaria de receber mais informações sobre a ABA-Agroecologia? *
        'participacaoOrganizacaoRaw'    => 19, // Coluna T: Você participa de alguma organização, rede ou movimento? *
    ];

    public function index()
    {
        $this->authorize('cadastrarUsuario');
        return view('administrador.cadastro-automatica');
    }

    public function processar(Request $request)
    {
        $this->authorize('cadastrarUsuario');

        $request->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls|max:10240', // 10mb
        ]);

        try {
            $arquivo = $request->file('arquivo');
            $caminhoArquivo = $arquivo->store('temp');
            $caminhoCompleto = storage_path('app/' . $caminhoArquivo);

            $spreadsheet = IOFactory::load($caminhoCompleto);
            $worksheet = $spreadsheet->getActiveSheet();
            $dados = $worksheet->toArray();
            
            // Remove as 3 linhas de cabeçalho
            array_shift($dados);
            array_shift($dados);
            array_shift($dados);

            $resultados = [];
            $sucessoContador = 0;

            foreach ($dados as $index => $linha) {
                // Linha na planilha é o índice do loop + 4 (3 cabeçalhos + índice 0)
                $numLinha = $index + 4; 

                $data = $this->extrairDadosDaLinha($linha);
                $data['linha_planilha'] = $numLinha;
                $senhaGerada = $this->gerarSenhaAleatoria(8);
                
                if (empty($data['nome']) && empty($data['cpf']) && empty($data['email'])) {
                    continue;
                }

                $status = 'Erro: Falha na validação';
                
                try {
                    $validator = $this->validarDados($data);

                    if ($validator->fails()) {
                        $status = 'Erro: ' . implode('; ', $validator->errors()->all());
                    } else {
                        $userExistente = User::where('cpf', $data['cpf'])->orWhere('email', $data['email'])->first();

                        if ($userExistente) {
                            $status = 'Usuário já cadastrado (CPF/Email encontrado)';
                        } else {
                            // 1. Criação do Endereço
                            $enderecoData = $this->buscarCepViaLogradouro($data['cidade'], $data['logradouro_complemento'], $data['uf']);
                            $enderecoData['numero'] = '1'; 
                            $enderecoData['complemento'] = $data['logradouro_complemento'];

                            $endereco = new Endereco($enderecoData);
                            $endereco->save();

                            // 2. Criação do Usuário
                            $user = new User();
                            $user->name = $data['nome'];
                            $user->email = $data['email'];
                            $user->password = Hash::make($senhaGerada);
                            $user->cpf = $data['cpf'];
                            // Celular está agora garantido como string limpa
                            $user->celular = $data['celular']; 
                            $user->instituicao = $data['instituicao'];
                            $user->email_verified_at = now();
                            $user->enderecoId = $endereco->id;
                            $user->save();
                            
                            // 3. Criação do Perfil Identitário
                            $perfilData = $this->formatarDadosPerfilIdentitario($data);
                            $perfilIdentitario = new PerfilIdentitario();
                            $perfilIdentitario->setAttributes($perfilData); 
                            $perfilIdentitario->userId = $user->id;
                            $perfilIdentitario->save();

                            $status = 'Cadastrado com sucesso';
                            $sucessoContador++;
                        }
                    }

                } catch (\Exception $e) {
                    $status = 'Erro interno no processamento: ' . $e->getMessage();
                    \Log::error("Erro no cadastro automático, linha {$numLinha}: " . $e->getMessage());
                }

                $resultados[] = [
                    'nome' => $data['nome'] ?? 'N/A',
                    'email' => $data['email'] ?? 'N/A',
                    'senha_gerada' => isset($userExistente) ? 'N/A (Existente)' : $senhaGerada,
                    'status' => $status
                ];
            }

            Storage::delete($caminhoArquivo);
            $caminhoPlanilhaResultado = $this->gerarPlanilhaResultado($resultados);

            $mensagem = "Planilha processada com sucesso! {$sucessoContador} cadastros. Baixe o relatório para ver todos os status.";
            session()->flash('success', $mensagem);
            
            // Mantém o download como ação principal
            return response()->download($caminhoPlanilhaResultado, 'resultado_cadastro_usuarios.xlsx')
                ->deleteFileAfterSend(true);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao processar arquivo: ' . $e->getMessage());
        }
    }

    /**
     * Extrai, limpa e mapeia os dados da linha da planilha.
     */
    private function extrairDadosDaLinha(array $linha): array
    {
        // Helper para garantir que o dado da célula seja sempre uma string
        $safeString = function($index) use ($linha) {
            $value = $linha[$index] ?? '';
            // Força a conversão para string, tratando tipos inesperados de células do PhpSpreadsheet
            if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
                return (string) $value;
            }
            return '';
        };

        $dados = [];

        // Mapeamento de campos básicos
        $dados['nome'] = trim($safeString(self::COLUMNS['nome']));
        $dados['email'] = strtolower(trim($safeString(self::COLUMNS['email'])));
        $dados['cpf'] = $this->normalizarCpf($safeString(self::COLUMNS['cpf']));
        $dados['instituicao'] = trim($safeString(self::COLUMNS['instituicao']));
        
        // CORREÇÃO CRÍTICA DO CELULAR: Mantém o formato original da planilha
        $dados['celular'] = trim($safeString(self::COLUMNS['celular']));
        
        $dados['dataNascimento'] = $this->normalizarData($safeString(self::COLUMNS['dataNascimento']));
        $dados['cidade'] = trim($safeString(self::COLUMNS['cidade']));
        $dados['uf'] = $this->normalizarUF($safeString(self::COLUMNS['uf']));
        $dados['nomeSocial'] = trim($safeString(self::COLUMNS['nomeSocial']));
        $dados['genero'] = strtolower(trim($safeString(self::COLUMNS['genero'])));
        $dados['logradouro_complemento'] = trim($safeString(self::COLUMNS['logradouro_complemento']));

        // Campos complexos (Perfil Identitário)
        $dados['raca'] = $this->normalizarRaca($safeString(self::COLUMNS['raca']));
        
        list($dados['comunidadeTradicional'], $dados['nomeComunidadeTradicional']) = $this->parseSimNaoCampo($safeString(self::COLUMNS['comunidadeTradicionalRaw']));
        
        list($dados['participacaoOrganizacao'], $dados['nomeOrganizacao']) = $this->parseSimNaoCampo($safeString(self::COLUMNS['participacaoOrganizacaoRaw']));
        
        $dados['necessidadesEspeciais'] = $this->parseNecessidadesEspeciais($safeString(self::COLUMNS['necessidadesEspeciaisRaw']));
        
        // Outros campos Booleanos
        $dados['lgbtqia'] = $this->stringToBoolean($safeString(self::COLUMNS['lgbtqiaRaw']));
        $dados['deficienciaIdoso'] = $this->stringToBoolean($safeString(self::COLUMNS['deficienciaIdosoRaw']));
        $dados['associadoAba'] = $this->stringToBoolean($safeString(self::COLUMNS['associadoAbaRaw']));
        $dados['receberInfoAba'] = $this->stringToBoolean($safeString(self::COLUMNS['receberInfoAbaRaw']));
        
        // Colocar valores default
        $dados['outroGenero'] = ''; $dados['outraRaca'] = ''; $dados['outraNecessidadeEspecial'] = '';
        $dados['vinculoInstitucional'] = ''; $dados['passaporte'] = null; $dados['cnpj'] = null; $dados['pais'] = 'brasil';
        
        return $dados;
    }

    /**
     * Formata os dados para o método setAttributes do PerfilIdentitario.
     */
    private function formatarDadosPerfilIdentitario(array $data): array
    {
        $perfilData = [
            'nomeSocial' => $data['nomeSocial'] ?? '',
            'dataNascimento' => $data['dataNascimento'],
            'genero' => $data['genero'] ?? 'não informado',
            'outroGenero' => $data['outroGenero'] ?? '',
            'raca' => is_array($data['raca']) ? $data['raca'] : [$data['raca']], 
            'outraRaca' => $data['outraRaca'] ?? '',
            
            // Comunidade Tradicional (booleano e nome)
            'comunidadeTradicional' => $data['comunidadeTradicional'] ? 'true' : 'false',
            // Garante NULL se não preenchido ou for 'Não'
            'nomeComunidadeTradicional' => $data['nomeComunidadeTradicional'] ?? null, 
            
            // Outros campos booleanos
            'lgbtqia' => $data['lgbtqia'] ? 'true' : 'false',
            'deficienciaIdoso' => $data['deficienciaIdoso'] ? 'true' : 'false',
            'associadoAba' => $data['associadoAba'] ? 'true' : 'false',
            'receberInfoAba' => $data['receberInfoAba'] ? 'true' : 'false',
            
            // Organização (booleano e nome)
            'participacaoOrganizacao' => $data['participacaoOrganizacao'] ? 'true' : 'false',
            // Garante NULL se não preenchido ou for 'Não'
            'nomeOrganizacao' => $data['nomeOrganizacao'] ?? null, 
            
            // Outros
            'necessidadesEspeciais' => $data['necessidadesEspeciais'] ?? ['nenhuma'], 
            'outraNecessidadeEspecial' => $data['outraNecessidadeEspecial'] ?? '',
            'vinculoInstitucional' => $data['vinculoInstitucional'] ?? '',
        ];

        return $perfilData;
    }

    /**
     * Lógica de validação de campos chave para a criação de usuários.
     */
    private function validarDados(array $data)
    {
        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'cpf' => 'O :attribute não é válido.',
            'email.unique' => 'O e-mail já possui um cadastro ativo.',
            'cpf.unique' => 'O CPF já possui um cadastro ativo.',
            'instituicao.regex' => 'O campo instituição contém caracteres não permitidos.',
            'date_format' => 'O formato da data de nascimento é inválido (esperado YYYY-MM-DD).',
            'uf.max' => 'O campo Estado deve ter no máximo 2 caracteres (sigla).',
            'celular.min' => 'O celular precisa de pelo menos 8 dígitos (após a limpeza de caracteres).',
        ];

        $regras = [
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'cpf' => ['required', 'cpf'],
            // Celular: aceita formatação como (87)99999-9999
            'celular' => ['required', 'string', 'max:20'], 
            'instituicao' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ0-9\s\-\.\(\)\[\]\{\}\/\\,;&@#$%*+=|<>!?~`\'"]+$/'],
            'dataNascimento' => ['required', 'date_format:Y-m-d'],
            'cidade' => ['required', 'string', 'max:255'],
            'uf' => ['required', 'string', 'max:2'],
            'logradouro_complemento' => ['required', 'string', 'max:255'],
            'genero' => ['required', 'string'],
            'raca' => ['required', 'array'], 
        ];
        
        // Adiciona a checagem de unicidade com o soft-deleted
        $userExistente = \App\Models\Users\User::withTrashed()->where('email', $data['email'])->orWhere('cpf', $data['cpf'])->first();
        
        if ($userExistente) {
            $regras['email'][] = 'unique:users,email,' . $userExistente->id; 
            $regras['cpf'][] = 'unique:users,cpf,' . $userExistente->id;
        } else {
            $regras['email'][] = 'unique:users,email';
            $regras['cpf'][] = 'unique:users,cpf';
        }


        return Validator::make($data, $regras, $messages);
    }
    
    // --- MÉTODOS AUXILIARES CORRIGIDOS ---
    
    private function normalizarUF(string $nomeEstado): string
    {
        $nomeEstado = trim(mb_strtoupper($nomeEstado));
        $estados = [
            'ACRE' => 'AC', 'ALAGOAS' => 'AL', 'AMAPA' => 'AP', 'AMAZONAS' => 'AM', 
            'BAHIA' => 'BA', 'CEARA' => 'CE', 'DISTRITO FEDERAL' => 'DF', 'ESPIRITO SANTO' => 'ES', 
            'GOIAS' => 'GO', 'MARANHAO' => 'MA', 'MATO GROSSO' => 'MT', 'MATO GROSSO DO SUL' => 'MS', 
            'MINAS GERAIS' => 'MG', 'PARA' => 'PA', 'PARAIBA' => 'PB', 'PARANA' => 'PR', 
            'PERNAMBUCO' => 'PE', 'PIAUI' => 'PI', 'RIO DE JANEIRO' => 'RJ', 'RIO GRANDE DO NORTE' => 'RN', 
            'RIO GRANDE DO SUL' => 'RS', 'RONDONIA' => 'RO', 'RORAIMA' => 'RR', 'SANTA CATARINA' => 'SC', 
            'SAO PAULO' => 'SP', 'SERGIPE' => 'SE', 'TOCANTINS' => 'TO'
        ];
        
        return $estados[$nomeEstado] ?? (strlen($nomeEstado) === 2 ? $nomeEstado : '');
    }

    private function normalizarData($data)
    {
        if (empty($data)) return null;

        if (\DateTime::createFromFormat('d/m/Y', $data)) {
            return \DateTime::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        } elseif (\DateTime::createFromFormat('Y-m-d', $data)) {
            return $data;
        }
        if (is_numeric($data)) {
            try {
                $unixTimestamp = Date::excelToTimestamp($data);
                return date('Y-m-d', $unixTimestamp);
            } catch (\Exception $e) {
                // Falha silenciosa
            }
        }
        
        return null;
    }

    private function normalizarCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) === 11) {
            return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        }
        return $cpf;
    }
    
    private function stringToBoolean(string $value): bool
    {
        $value = strtolower(trim($value));
        return in_array($value, ['sim', 's', 'true', '1']); 
    }

    /**
     * Lida com campos condicionais ("Sim ou Não. Se Sim, qual?").
     * Garante que se for 'NÃO' ou vazio, o campo complementar seja NULL.
     *
     * @return array [bool is_sim, string|null nome_complementar]
     */
    private function parseSimNaoCampo(string $value): array
    {
        $value = trim($value);
        $valueLower = strtolower($value);

        // 1. Caso explícito "NÃO" ou Vazio: Retorna FALSO e NULL. (CORREÇÃO PARA NÃO SALVAR O TEXTO 'NÃO')
        if (empty($value) || $valueLower === 'não' || $valueLower === 'nao') {
            return [false, null]; 
        }
        
        // 2. Tenta identificar se começa com "Sim" e tem vírgula
        if (str_starts_with($valueLower, 'sim,') || str_starts_with($valueLower, 's,')) {
             $parts = explode(',', $value, 2);
             $text = trim($parts[1] ?? '');
             
             if (!empty($text)) {
                 return [true, $text];
             }
        }

        // 3. Caso seja "Sim" puro ou um texto direto (nome de comunidade/organização)
        return [true, $value];
    }

    /**
     * CORREÇÃO FINAL: Mapeia 'PRETO' para 'NEGRO' (conforme solicitado).
     */
    private function normalizarRaca(string $value): array
    {
        if (empty($value)) return [];

        $racas = explode(',', $value);
        $racas = array_map(function($r) {
            $r = strtolower(trim($r));
            $r = str_replace(' ', '_', $r);
            
            // Mapeia 'preto' (da planilha) para 'negro' (conforme solicitado)
            if ($r === 'preto') {
                return 'negro'; 
            }
            
            return $r;
        }, $racas);

        // Garante que apenas valores não vazios sejam retornados
        return array_unique(array_filter($racas));
    }

    /**
     * Força o valor 'nenhuma' se a célula for 'Não' ou estiver vazia.
     */
    private function parseNecessidadesEspeciais(string $value): array
    {
        $value = trim($value);
        $valueLower = strtolower($value);
        
        // Garante que 'Não', 'NENHUMA' ou vazio resultem em ['nenhuma']
        if (empty($value) || $valueLower === 'não' || $valueLower === 'nao' || $valueLower === 'nenhuma') {
            return ['nenhuma'];
        }
        
        $necessidades = explode(',', $value);
        $necessidades = array_map(function($n) {
            $n = strtolower(trim($n));
            return str_replace(' ', '_', $n);
        }, $necessidades);

        // Remove vazios e 'nenhuma' da lista se houver outras necessidades
        $necessidades = array_unique(array_filter($necessidades, function($n) {
            return !empty($n) && $n !== 'nenhuma';
        }));

        // Se após o filtro não houver nada, retorna ['nenhuma']
        return empty($necessidades) ? ['nenhuma'] : $necessidades;
    }

    private function buscarCepViaLogradouro(string $cidade, string $logradouro_complemento, string $uf): array
    {
        $enderecoSimulado = [
            'cep'           => '40000-000', 
            'rua'           => 'Rua Principal ' . $logradouro_complemento,
            'bairro'        => 'Centro', 
            'cidade'        => $cidade,
            'uf'            => $uf,
            'pais'          => 'Brasil',
        ];

        return $enderecoSimulado;
    }

    private function gerarSenhaAleatoria(int $length = 8): string
    {
        $chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%&*';
        return Str::random($length, $chars);
    }

    private function gerarPlanilhaResultado(array $resultados)
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->setCellValue('A1', 'Nome');
        $worksheet->setCellValue('B1', 'Email');
        $worksheet->setCellValue('C1', 'Senha Gerada');
        $worksheet->setCellValue('D1', 'Status');

        $linha = 2;
        foreach ($resultados as $resultado) {
            $worksheet->setCellValue('A' . $linha, $resultado['nome']);
            $worksheet->setCellValue('B' . $linha, $resultado['email']);
            $worksheet->setCellValue('C' . $linha, $resultado['senha_gerada']);
            $worksheet->setCellValue('D' . $linha, $resultado['status']);
            $linha++;
        }

        $caminhoArquivo = storage_path('app/temp/resultado_cadastro_usuarios_' . time() . '.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($caminhoArquivo);

        return $caminhoArquivo;
    }
}