<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\recuperacaoSenha;

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
        'usuarioTemp', 'user_id'
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

    public function trabalho(){
        return $this->hasMany('App\Trabalho', 'autorId');
    }


    public function parecer(){
        return $this->hasMany('App\Parecer', 'revisorId');
    }

    public function atribuicao(){
        return $this->hasMany('App\Atribuicao', 'revisorId');
    }

    public function pertence(){
        return $this->hasMany('App\Pertence', 'revisorId');
    }

    public function recurso(){
        return $this->hasMany('App\Recurso', 'comissaoId');
    }

    public function mensagem(){
        return $this->hasMany('App\Mensagem', 'comissaoId');
    }

    public function endereco(){
        return $this->belongsTo('App\Endereco', 'enderecoId');
    }

    public function evento(){
        return $this->hasMany('App\Evento', 'coordenadorId');
    }

    public function coautor(){
        return $this->hasOne('App\Coautor');
    }

    public function revisor(){
        return $this->hasOne('App\Revisor');
    }

    public function participante(){
        return $this->hasOne('App\Participante');
    }

    public function administradors(){
        return $this->hasOne('App\Administrador');
    }

    public function coordComissaoCientifica(){
        return $this->hasOne('App\CoordComissaoCientifica');
    }

    public function coordComissaoOrganizadora(){
        return $this->hasOne('App\CoordComissaoOrganizadora');
    }

    public function coordEvento(){
        return $this->hasOne('App\CoordenadorEvento');
    }

    public function sendPasswordResetNotification($token){
        $this->notify(new recuperacaoSenha($token));
    }

}
