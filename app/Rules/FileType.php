<?php

namespace App\Rules;

use App\Models\Submissao\Modalidade;
use Illuminate\Contracts\Validation\Rule;
use Throwable;

class FileType implements Rule
{
    private $acceptedTypes;
    private $arquivo;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Modalidade $modalidade, $arquivo)
    {
        $this->acceptedTypes = $modalidade->tiposAceitos();
        $this->arquivo = $arquivo;
    }

    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            $type = $this->arquivo->getClientOriginalExtension();
            $tamanhoMB = $this->arquivo->getSize() / 1024 / 1024;
            return in_array($type, $this->acceptedTypes) && $this->checkTamanhoTipo($type, $tamanhoMB);
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function message(): string
    {
        $types = implode(', ', $this->acceptedTypes);
        $texto = 'Arquivo inválido. Os tipos válidos são: ' . $types;
        $diff = false;
        if (in_array('mp3', $this->acceptedTypes)) {
            $texto .= ". O tamanho máximo para arquivos .mp3 é de 20 MB";
            $diff = true;
        }
        if (in_array('mp4', $this->acceptedTypes)) {
            $texto .= ". O tamanho máximo para arquivos .mp4 é de 50 MB";
            $diff = true;
        }

        if ($diff && ($types != "mp4, mp3" && count($this->acceptedTypes) > 1)) {
            $texto .= ". Os demais tipos possuem tamanho máximo de  2 MB.";
        } elseif (! $diff) {
            $texto .= ". O tamanho máximo é de 2 MB.";
        }

        return $texto;
    }

    public function checkTamanhoTipo(string $type, float $tamanhoMB): bool
    {
        if ($type == "mp3") {
            if ($tamanhoMB > 20) {
                return false;
            }
            return  true;
        } elseif ($type == "mp4") {
            if ($tamanhoMB > 50) {
                return false;
            }
            return  true;
        } else {
            if ($tamanhoMB > 2) {
                return false;
            }
            return true;
        }
    }
}
