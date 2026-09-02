<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Area;
use App\Models\Submissao\Modalidade;
use App\Models\Submissao\Trabalho;
use App\Models\Submissao\Avaliacao;
use App\Models\Submissao\Resposta;

class Revisor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prazo', 'trabalhosCorrigidos', 'correcoesEmAndamento', 'user_id', 'evento_id', 'areaId', 'modalidadeId',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaId');
    }

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'modalidadeId');
    }

    public function trabalhosAtribuidos()
    {
        return $this->belongsToMany(Trabalho::class, 'atribuicaos', 'revisor_id', 'trabalho_id')->withPivot('confirmacao', 'parecer')->withTimestamps();
    }

    public function trabalhosAtribuidosPendentes()
    {
        return $this->belongsToMany(Trabalho::class, 'atribuicaos', 'revisor_id', 'trabalho_id')->withPivot('confirmacao', 'parecer')->withTimestamps()->where('parecer', 'processando');
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'trabalho_id');
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }
}
