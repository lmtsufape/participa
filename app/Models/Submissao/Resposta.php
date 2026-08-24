<?php

namespace App\Models\Submissao;

use App\Models\Users\Revisor;
use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    protected $fillable = ['pergunta_id', 'revisor_id', 'trabalho_id'];

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }

    public function opcoes()
    {
        return $this->hasMany(Opcao::class)->orderBy('ordem');
    }

    public function paragrafo()
    {
        return $this->hasOne(Paragrafo::class);
    }

    public function revisor()
    {
        return $this->belongsTo(Revisor::class);
    }

    public function trabalho()
    {
        return $this->belongsTo(Trabalho::class);
    }
}
