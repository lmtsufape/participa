<?php

namespace App\Models\Submissao;

use App\Models\Users\User;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo', 'vagas', 'valor', 'descricao', 'local',
        'carga_horaria', 'palavras_chave', 'visibilidade_participante',
        'eventoId', 'tipo_id',
    ];

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'eventoId');
    }

    public function tipoAtividade()
    {
        return $this->belongsTo('App\Models\Submissao\TipoAtividade', 'tipo_id');
    }

    public function convidados()
    {
        return $this->hasMany('App\Models\Users\Convidado', 'atividade_id');
    }

    public function datasAtividade()
    {
        return $this->hasMany('App\Models\Submissao\DatasAtividade', 'atividade_id');
    }

    public function promocoes()
    {
        return $this->belongsToMany('App\Models\Inscricao\Promocao', 'atividades_promocaos', 'atividade_id', 'promocao_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'atividades_user', 'atividade_id', 'user_id');
    }

    public function atividadeInscricoesEncerradas()
    {
        $primeiraAtividade = $this->datasAtividade()->orderBy('data', 'ASC')->orderBy('hora_inicio', 'ASC')->first();
        $dataPrimeiraAtividade = new DateTime($primeiraAtividade->data.$primeiraAtividade->hora_inicio);
        if ($dataPrimeiraAtividade < now()) {
            $encerrada = true;
        } else {
            $encerrada = false;
        }

        return $encerrada;
    }
}
