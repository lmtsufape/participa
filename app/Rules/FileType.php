<?php

namespace App\Rules;

use App\Models\Submissao\MidiaExtra;
use App\Models\Submissao\Modalidade;
use Illuminate\Contracts\Validation\Rule;

class FileType implements Rule
{
    private $acceptedTypes;

    private $arquivo;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Modalidade $modalidade, MidiaExtra $midia, $arquivo, $eh_modalidade)
    {
        if ($eh_modalidade) {
            $this->acceptedTypes = $modalidade->tiposAceitos();
        } else {
            $this->acceptedTypes = $midia->tiposAceitos();
        }
        $this->arquivo = $arquivo;
    }

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
        $texto = 'Arquivo inválido. Os tipos válidos são: '.$types;
        $diff = false;
        if ($this->array_any(['mp3', 'ogg', 'wav'], $this->acceptedTypes)) {
            $texto .= '. O tamanho máximo para arquivos de áudio é de 20 MB';
            $diff = true;
        }
        if ($this->array_any(['ogv', 'mpg', 'mpeg', 'mkv', 'avi', 'mp4'], $this->acceptedTypes)) {
            $texto .= '. O tamanho máximo para arquivos de vídeo é de 50 MB';
            $diff = true;
        }

        if ($diff && ($types != 'mp4, mp3' && count($this->acceptedTypes) > 1)) {
            $texto .= '. Os demais tipos possuem tamanho máximo de 2 MB.';
        } elseif (! $diff) {
            $texto .= '. O tamanho máximo é de 2 MB.';
        }

        return $texto;
    }

    private function array_any($needle, $haystack)
    {
        foreach ($needle as $value) {
            if (in_array($value, $haystack)) {
                return true;
            }
        }

        return false;
    }

    public function checkTamanhoTipo(string $type, float $tamanhoMB): bool
    {
        if (in_array($type, ['mp3', 'ogg', 'wav'])) {
            if ($tamanhoMB > 20) {
                return false;
            }

            return true;
        } elseif (in_array($type, ['ogv', 'mpg', 'mpeg', 'mkv', 'avi', 'mp4'])) {
            if ($tamanhoMB > 50) {
                return false;
            }

            return true;
        } else {
            if ($tamanhoMB > 2) {
                return false;
            }

            return true;
        }
    }
}
