<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FormatFileNames
{

    /**
   * Caminho do diretorio com o nome do arquivo para checar a existencia
   *
   * @var string
   */
    private string $caminho;

    /**
   * Variável para guardar o estado do nome formatado
   *
   * @var string
   */
    private string $nome_formatado;

    /**
   * Variável para guardar se a pasta é pública
   *
   * @var string
   */
    private $public;

    /**
   * Variável para saber onde o arquivo será salvo
   *
   * @var string
   */
    private string $diretorio;
    
    /**
   * Variável para guardar a extensão original do arquivo
   *
   * @var string
   */
    private string $extensao;

    /**
     * Formata um nome de arquivo sem caracteres especiais, e caso o mesmo nome estiver presente no diretório, um novo nome é definido.
     *
     * @param string $diretorio diretório da pasta onde o arquivo será salvo
     * @param bool $public indicativo se o diretório está em pasta pública
     * @param string $nome_arquivo nome original do arquivo
     * @param string $extensao extensão original do arquivo
     * @return string $nome_formatado nome do arquivo formatado com extensão
     */

    public function formatarNomeArquivo(string $diretorio, bool $public, string $nome_arquivo, string $extensao){
        $this->setAtributos($diretorio, $public, $nome_arquivo, $extensao);
        $this->setNovoNome();
        return $this->nome_formatado.'.'.$extensao;
    }

    
    /**
     * Guarda os atributos passados.
     *
     * @param string $diretorio
     * @param bool $public
     * @param string $nome_arquivo
     * @param string $extensao
     * @return void
     */

    protected function setAtributos(string $diretorio, bool $public, string $nome_arquivo, string $extensao){
        $this->nome_formatado = $this->limparString($nome_arquivo);
        $this->caminho = $this->setCaminho($diretorio);
        $this->public = $this->setPublicDiretorio($public);
        $this->diretorio = $diretorio;
        $this->extensao = $extensao;
    }

    /**
     * Formata a string do nome do arquivo.
     *
     * @param string $string
     * @return string $string formatada
     */

    private function limparString(string $string){
        $string = str_replace(' ', '-', $string); 
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); 
    }

    /**
     * Define o caminho do arquivo
     *
     * @param string $diretorio
     * @return string $caminho 
     */

    private function setCaminho(string $diretorio){
        return $diretorio.$this->nome_formatado;
    }

    /**
     * Define na variavel $this->public se a pasta é pública
     *
     * @param $public
     * @return void
     */
    private function setPublicDiretorio($public){
        if($public){
            $this->public = 'public/';
        }else{
            $this->public = '';
        }
    }

    /**
     * Atualiza o nome formatado e cria o seu novo caminho. Caso já exista o nome do diretório, uma nova tentativa de nome é gerada até que seja único.
     * @return void
     */
    private function setNovoNome(){
        if(Storage::disk()->exists($this->public.$this->caminho.'.'.$this->extensao)) {
            $this->nome_formatado = $this->gerarNovoNome();
            $this->caminho = $this->setCaminho($this->diretorio);
            $this->setNovoNome();
        }
    }

    /**
     * Gera o novo nome do arquivo incrementando um valor númerico a ele
     * @return void
     */
    private function gerarNovoNome(){
        $nome_formatado = $this->nome_formatado;
        $array = explode("-", $nome_formatado);
        if(count($array) > 1){
            $numero = intval($array[1]);
            $numero += 1;
            return $array[0].'-'.strval($numero);
        }else{
            return $array[0].'-1';
        }
    }

}