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
}
