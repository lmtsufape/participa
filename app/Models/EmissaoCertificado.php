<?php

namespace App\Models;

use App\Models\Submissao\Trabalho;
use App\Models\Submissao\Certificado;
use App\Models\Submissao\Palestra;
use App\Models\Submissao\TipoComissao;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class EmissaoCertificado extends Model
{
    protected $table = 'certificado_user';

    protected $fillable = [
        'certificado_id',
        'user_id',
        'valido',
        'validacao',
        'trabalho_id',
        'palestra_id',
        'comissao_id',
        'path',
    ];

    public function certificado()
    {
        return $this->belongsTo(Certificado::class)->withTrashed();;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trabalho()
    {
        return $this->belongsTo(Trabalho::class);
    }

    public function palestra()
    {
        return $this->belongsTo(Palestra::class);
    }

    public function tipoComissao()
    {
        return $this->belongsTo(TipoComissao::class, 'comissao_id');
    }
}
