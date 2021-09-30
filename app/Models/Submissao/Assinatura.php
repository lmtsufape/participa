<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    protected $fillable = ['caminho', 'cargo', 'nome'];

    public function certificados()
    {
        return $this->belongsToMany(Certificado::class, 'assinatura_certificado', 'assinatura_id', 'certificado_id');
    }
}
