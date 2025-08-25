<?php

namespace App\Models\Submissao;

use App\Models\Users\Revisor;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Trabalho extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // status = ['rascunho','submetido', 'avaliado', 'corrigido','aprovado','reprovado', 'arquivado']
    protected $fillable = [
        'titulo', 'autores', 'data', 'modalidadeId', 'areaId', 'autorId', 'eventoId', 'resumo', 'avaliado',
        'campoextra1simples', 'campoextra2simples', 'campoextra3simples', 'campoextra4simples',
        'campoextra5simples', 'campoextra1grande', 'campoextra2grande', 'campoextra3grande',
        'campoextra4grande', 'campoextra5grande', 'status', 'aprovado'
    ];

    public function recurso()
    {
        return $this->hasMany('App\Models\Submissao\Recurso', 'trabalhoId');
    }

    public function arquivo()
    {
        return $this->hasMany('App\Models\Submissao\Arquivo', 'trabalhoId');
    }

    public function arquivoAvaliacao()
    {
        return $this->hasMany('App\Models\Submissao\ArquivoAvaliacao', 'trabalhoId');
    }

    public function arquivoCorrecao()
    {
        return $this->hasOne('App\Models\Submissao\ArquivoCorrecao', 'trabalhoId');
    }

    public function modalidade()
    {
        return $this->belongsTo('App\Models\Submissao\Modalidade', 'modalidadeId');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Submissao\Area', 'areaId');
    }

    public function autor()
    {
        return $this->belongsTo('App\Models\Users\User', 'autorId');
    }

    public function coautors()
    {
        return $this->belongsToMany('App\Models\Users\Coautor', 'coautor_trabalho', 'trabalho_id', 'coautor_id')->orderBy('ordem');
    }

    public function pareceres()
    {
        return $this->hasMany('App\Models\Submissao\Parecer', 'trabalhoId');
    }

    public function atribuicoes()
    {
        return $this->belongsToMany('App\Models\Users\Revisor', 'atribuicaos', 'trabalho_id', 'revisor_id')->withPivot('confirmacao', 'parecer','prazo_correcao', 'justificativa_recusa')->withTimestamps();
    }

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'eventoId');
    }

    public function userRevisorTrabalho(): ?Revisor
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        return $this->atribuicoes()
                    ->where('user_id', $user->id)
                    ->first();
    }

    public function avaliacoes()
    {
        return $this->hasMany('App\Models\Submissao\Avaliacao', 'trabalho_id');
    }

    public function respostas()
    {
        return $this->hasMany('App\Models\Submissao\Resposta');
    }

    public function midiasExtra()
    {
        return $this->belongsToMany(MidiaExtra::class, 'midia_extras_trabalho', 'trabalho_id', 'midia_extra_id')->withPivot('caminho');
    }

    public function arquivosExtras()
    {
        return $this->hasMany(Arquivoextra::class, 'trabalhoId');
    }

    public function avaliado(User $user)
    {
        $revisor = Revisor::where([['user_id', $user->id], ['areaId', $this->area->id],
            ['modalidadeId', $this->modalidade->id], ])->first();

        if ($revisor == null) {
            return false;
        }

        return Resposta::where([['trabalho_id', $this->id], ['revisor_id', $revisor->id]])->exists();
    }

    public function getParecerAtribuicao(User $user)
    {
        $revisor = Revisor::where([['user_id', $user->id], ['areaId', $this->area->id],
            ['modalidadeId', $this->modalidade->id], ])->first();

        return $this->atribuicoes()->where('revisor_id', $revisor->id)->first()->pivot->parecer;
    }

    public function getQuantidadeAvaliacoes()
    {
        return $this->atribuicoes->map(function ($revisor) {
            return $this->avaliado($revisor->user);
        })->filter()->count();
    }
}
