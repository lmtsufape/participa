<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class TemplateSubmis extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'modalidadeId',
    ];

    public function modalidade()
    {
        return $this->belongsTo('App\Models\Submissao\Modalidade', 'modalidadeId');
    }
}
