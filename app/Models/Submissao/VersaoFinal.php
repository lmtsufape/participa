<?php

namespace App\Models\Submissao;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class VersaoFinal extends Model
{
    protected $table = 'versoes_finais';

    protected $fillable = ['user_id', 'caminho', 'nome_original'];

    public function trabalho()
    {
        return $this->belongsTo(Trabalho::class);
    }

    public function autorEnvio()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
