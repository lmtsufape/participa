<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FormatFileNames
{
    /**
     * Formata um nome de arquivo sem caracteres especiais, salva no diretório e caso o mesmo nome estiver presente no diretório, um novo nome é definido.
     *
     * @param string $diretorio diretório da pasta onde o arquivo será salvo
     * @param bool $public indicativo se o diretório está em pasta pública
     * @param $arquivo arquivo salvo
     * @return string $path caminho com o nome do arquivo salvo
     */
    public function uploadArquivo(string $diretorio, bool $public, $arquivo)
    {
        if ($public) {
            $path = Storage::putFile('public/'.$diretorio, $arquivo);
            $path = substr($path, 7);
        } else {
            $path = Storage::putFile($diretorio, $arquivo);
        }

        return $path;
    }
}
