<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreRegistro extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'cpf', 'email', 'codigo', 'expiracao'];
    protected $dates = ['expiracao'];
}
