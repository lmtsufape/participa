<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Assinatura extends Model
{
    protected $fillable = ['caminho', 'cargo', 'nome'];

    public function certificados()
    {
        return $this->belongsToMany(Certificado::class, 'assinatura_certificado', 'assinatura_id', 'certificado_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function setAtributes(Request $request)
    {
        $this->nome = $request->nome;
        $this->cargo = $request->cargo;
    }
}
