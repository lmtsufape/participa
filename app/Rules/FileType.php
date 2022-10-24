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
        return 'Arquivo inválido. Os tipos válidos são: ' . $types. '. O tamanho para arquivos .mp3 é de 20 MB, para mp4 é de 50 MB, e os demais tipos apenas 2 MB.';
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
