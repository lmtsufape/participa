<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class MidiaExtra extends Model
{
    protected $fillable = [
        'nome', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt', 'zip', 'svg', 'mp4', 'mp3', 'modalidade_id'
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
        $tiposcadastrados = [];
        if ($this->pdf == true) {
            array_push($tiposcadastrados, 'pdf');
        }
        if ($this->jpg == true) {
            array_push($tiposcadastrados, 'jpg');
        }
        if ($this->jpeg == true) {
            array_push($tiposcadastrados, 'jpeg');
        }
        if ($this->png == true) {
            array_push($tiposcadastrados, 'png');
        }
        if ($this->docx == true) {
            array_push($tiposcadastrados, 'docx');
        }
        if ($this->odt == true) {
            array_push($tiposcadastrados, 'odt');
        }
        if ($this->zip == true) {
            array_push($tiposcadastrados, 'zip');
        }
        if ($this->svg == true) {
            array_push($tiposcadastrados, 'svg');
        }
        if ($this->mp4 == true) {
            array_push($tiposcadastrados, 'mp4');
        }
        if ($this->mp3 == true) {
            array_push($tiposcadastrados, 'mp3');
        }

        return $tiposcadastrados;
    }
}
