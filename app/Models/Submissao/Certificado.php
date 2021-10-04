<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Certificado extends Model
{
    protected $fillable = ['caminho', 'nome'];

    public function assinaturas()
    {
        return $this->belongsToMany(Assinatura::class, 'assinatura_certificado', 'certificado_id', 'assinatura_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function setAtributes(Request $request)
    {
        $this->nome = $request->nome;
    }
}
