<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{
    public const TIPO_ENUM = [
        'texto' => 1,
        'data' => 2,
        'nome_assinatura' => 3,
        'cargo_assinatura' => 4,
        'imagem_assinatura' => 5,
        'linha_assinatura' => 6,
        'qrcode' => 7,
        'hash' => 8,
        'logo' => 9,
        'emissao' => 10,
    ];

    protected $fillable = [
        'x', 'y', 'largura', 'altura', 'fontSize', 'certificado_id', 'assinatura_id', 'tipo',
    ];

    /**
     * Get the certificado that owns the Medida
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function certificado()
    {
        return $this->belongsTo('App\Models\Submissao\Certificado');
    }

    /**
     * Get the assinatura that owns the Medida
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assinatura()
    {
        return $this->belongsTo('App\Models\Submissao\Assinatura');
    }
}
