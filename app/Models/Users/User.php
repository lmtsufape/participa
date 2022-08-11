<?php

namespace App\Models\Users;

use App\Models\Submissao\Atividade;
use App\Models\Submissao\Certificado;
use App\Notifications\recuperacaoSenha;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'cpf', 'instituicao', 'celular',
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

    public function comissaoEvento()
    {
        return $this->hasMany('App\Models\Users\ComissaoEvento');
    }

    public function coautor()
    {
        return $this->hasOne('App\Models\Users\Coautor', 'autorId');
    }

    public function revisor()
    {
        return $this->hasMany('App\Models\Users\Revisor');
    }

    public function participante()
    {
        return $this->hasOne('App\Models\Users\Participante');
    }

    public function administradors()
    {
        return $this->hasOne('App\Models\Users\Administrador');
    }

    public function coordComissaoCientifica()
    {
        return $this->belongsToMany('App\Models\Submissao\Evento', 'coord_comissao_cientificas', 'user_id', 'eventos_id')->using('App\Models\Users\CoordComissaoCientifica');
    }

    public function coordComissaoOrganizadora()
    {
        return $this->belongsToMany('App\Models\Submissao\Evento', 'coord_comissao_organizadoras', 'user_id', 'eventos_id')->using('App\Models\Users\CoordComissaoOrganizadora');
    }

    public function membroComissaoEvento()
    {
        return $this->belongsToMany('App\Models\Submissao\Evento', 'comissao_cientifica_eventos', 'user_id', 'evento_id');
    }

    public function coordEvento()
    {
        return $this->hasOne('App\Models\Users\CoordenadorEvento');
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
