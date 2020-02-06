<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        $this->hasMany('App\Trabalho', 'autorId');
    }

    public function coautor(){
        $this->hasMany('App\Coautor', 'autorId');
    }

    public function parecer(){
        $this->hasMany('App\Parecer', 'revisorId');
    }

    public function atribuicao(){
        $this->hasMany('App\Atribuicao', 'revisorId');
    }

    public function pertence(){
        $this->hasMany('App\Pertence', 'revisorId');
    }

    public function recurso(){
        $this->hasMany('App\Recurso', 'comissaoId');
    }

    public function mensagem(){
        $this->hasMany('App\Mensagem', 'comissaoId');
    }

    public function endereco(){
        $this->belongsTo('App\Endereco', 'enderecoId');
    }

}
