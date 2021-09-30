<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    protected $fillable = ['caminho', 'nome'];

    public function assinaturas()
    {
        return $this->belongsToMany(Assinatura::class, 'assinatura_certificado', 'certificado_id', 'assinatura_id');
    }
}
