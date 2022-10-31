<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class MidiaExtra extends Model
{
    protected $fillable = [
        'nome', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt', 'zip', 'svg', 'mp4', 'mp3', 'modalidade_id',
        'ogg', 'wav', 'ogv', 'mpg', 'mpeg', 'mkv', 'avi', 'odp', 'pptx', 'csv', 'ods', 'xlsx',
    ];

    public function modalidade()
    {
        return $this->belongsTo('App\Models\Submissao\Modalidade', 'modalidade_id');
    }

    public function trabalhos()
    {
        return $this->belongsToMany(Trabalho::class, 'midia_extras_trabalho', 'midia_extra_id', 'trabalho_id')->withPivot('caminho');
    }

    public function tiposAceitos()
    {
        $extensoes = ['ogg', 'wav', 'ogv', 'mpg', 'mpeg', 'mkv', 'avi', 'odp', 'pptx', 'csv', 'ods', 'xlsx', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt', 'zip', 'svg', 'mp4', 'mp3'];
        $tiposcadastrados = array_filter($this->getAttributes(), function ($attribute) use ($extensoes) {
            return $attribute == true && in_array($attribute, $extensoes);
        });

        return $tiposcadastrados;
    }

    public function hyphenizeNome()
    {
        $string = $this->nome;
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
        $string = strtolower(trim($string, '-'));

        return $string."-".$this->id;
    }
}
