<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Opcao extends Model
{
    protected $fillable = ['titulo', 'tipo', 'check', 'visibilidade', 'ordem', 'resposta_id', 'parent_id'];

    public function resposta()
    {
        return $this->belongsTo(Resposta::class, 'resposta_id');
    }

    public function parent()
    {
        return $this->belongsTo(Self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Self::class, 'parent_id');
    }
}
