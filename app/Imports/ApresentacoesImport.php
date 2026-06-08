<?php

namespace App\Imports;

use App\Models\Submissao\Trabalho;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ApresentacoesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;

    protected $eventoId;
    protected $processados = 0;
    protected $erros = [];

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $valores = array_values($row);
        $idTrabalho = $valores[0] ?? null;
        $tituloTrabalho = $valores[1] ?? null;
        $autor = $valores[2] ?? null;

        if (empty($idTrabalho) || empty($tituloTrabalho) || empty($autor)) {
            $this->erros[] = "Linha " . ($this->processados + 1) . ": Valores obrigatórios não podem estar vazios";
            return null;
        }

        $trabalho = Trabalho::where('id', (int)$idTrabalho)
            ->where('eventoId', (int)$this->eventoId)
            ->first();

        if (!$trabalho) {
            $trabalhoSemFiltro = Trabalho::where('id', $idTrabalho)->first();
            if ($trabalhoSemFiltro) {
                $this->erros[] = "Linha " . ($this->processados + 1) . ": Trabalho com ID {$idTrabalho} existe mas pertence ao evento {$trabalhoSemFiltro->eventoId}, não ao evento {$this->eventoId}";
            } else {
                $this->erros[] = "Linha " . ($this->processados + 1) . ": Trabalho com ID {$idTrabalho} não encontrado no banco de dados";
            }
            return null;
        }

        $trabalho->update(['apresentado' => true]);
        $this->processados++;

        return null;
    }
    public function rules(): array
    {

        return [
            '*' => 'required',
        ];
    }

    public function getProcessados()
    {
        return $this->processados;
    }

    public function getErros()
    {
        return $this->erros;
    }
}
