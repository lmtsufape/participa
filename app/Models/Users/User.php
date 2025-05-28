<?php

namespace App\Models\Users;

use App\Models\Submissao\Atividade;
use App\Models\Submissao\Certificado;
use App\Notifications\recuperacaoSenha;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'cpf','cnpj', 'passaporte', 'instituicao', 'celular',
        'especProfissional', 'enderecoId',
        'usuarioTemp', 'user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comissaoEvento()
    {
        return $this->hasMany(ComissaoEvento::class);
    }

    public function coautor()
    {
        return $this->hasOne(Coautor::class, 'autorId');
    }

    public function revisor()
    {
        return $this->hasMany(Revisor::class);
    }

    public function participante()
    {
        return $this->hasOne(Participante::class);
    }

    public function administradors()
    {
        return $this->hasOne(Administrador::class);
    }

    public function coordComissaoCientifica()
    {
        return $this->belongsToMany('App\Models\Submissao\Evento', 'coord_comissao_cientificas', 'user_id', 'eventos_id')->using('App\Models\Users\CoordComissaoCientifica');
    }

    public function coordComissaoOrganizadora()
    {
        return $this->belongsToMany('App\Models\Submissao\Evento', 'coord_comissao_organizadoras', 'user_id', 'eventos_id')->using('App\Models\Users\CoordComissaoOrganizadora');
    }

    public function trabalho()
    {
        return $this->hasMany('App\Models\Submissao\Trabalho', 'autorId');
    }

    public function parecer()
    {
        return $this->hasMany('App\Models\Submissao\Parecer', 'revisorId');
    }

    // public function atribuicao(){
    //     return $this->hasMany('App\Atribuicao', 'revisorId');
    // }

    public function pertence()
    {
        return $this->hasMany('App\Models\Submissao\Pertence', 'revisorId');
    }

    public function recurso()
    {
        return $this->hasMany('App\Models\Submissao\Recurso', 'comissaoId');
    }

    public function mensagem()
    {
        return $this->hasMany('App\Models\Submissao\Mensagem', 'comissaoId');
    }

    public function endereco()
    {
        return $this->belongsTo('App\Models\Submissao\Endereco', 'enderecoId');
    }



    public function revisorWithCounts()
    {
        return $this->hasMany('App\Models\Users\Revisor')->withCount([
            'trabalhosAtribuidos as avaliados_count' => function (Builder $query) {
                $query->where('parecer', 'avaliado')->orWhere('parecer', 'encaminhado');
            },
            'trabalhosAtribuidos as processando_count' => function (Builder $query) {
                $query->where('parecer', 'processando');
            },
        ]);
    }



    public function membroComissaoEvento()
    {
        return $this->belongsToMany('App\Models\Submissao\Evento', 'comissao_cientifica_eventos', 'user_id', 'evento_id');
    }

    /**
     * Retorna os eventos que o usuário criou
     *
     * @return HasMany
     */
    public function eventos()
    {
        return $this->hasMany('App\Models\Submissao\Evento', 'coordenadorId');
    }

    /**
     * Retorna os eventos em que o usuário foi atribuído como coordenador pelo usuário criador do evento
     *
     * @return BelongsToMany
     */
    public function eventosCoordenador()
    {
        return $this->belongsToMany('App\Models\Submissao\Evento', 'coordenador_eventos', 'user_id', 'eventos_id')->using('App\Models\Users\CoordenadorEvento');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new recuperacaoSenha($token));
    }

    public function membroComissaoOrgaEvento()
    {
        return $this->belongsToMany('App\Models\Submissao\Evento', 'comissao_organizadora_eventos', 'user_id', 'evento_id');
    }

    public function inscricaos()
    {
        return $this->hasMany('App\Models\Inscricao\Inscricao');
    }

    public function outrasComissoes()
    {
        return $this->belongsToMany('App\Models\Submissao\TipoComissao');
    }

    public function certificados()
    {
        return $this->belongsToMany(Certificado::class, 'certificado_user')->withTrashed()->withPivot('id', 'valido', 'validacao', 'trabalho_id', 'palestra_id', 'comissao_id')->withTimestamps();
    }

    public function atividades()
    {
        return $this->belongsToMany(Atividade::class, 'atividades_user', 'user_id', 'atividade_id');
    }
}
