<?php

namespace App\Models\Submissao;

use App\Models\Users\User;
use App\Traits\FormatFileNames;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificado extends Model
{
    use SoftDeletes, FormatFileNames;

    protected $fillable = ['caminho', 'data', 'local', 'nome', 'texto', 'tipo', 'tipo_comissao_id', 'atividade_id', 'has_imagem_verso'];

    protected $casts = [
        'mostrar_assinaturas' => 'boolean',
    ];

    public const TIPO_ENUM = [
        'apresentador'          => 1,
        'comissao_cientifica'   => 2,
        'comissao_organizadora' => 3,
        'revisor'               => 4,
        'participante'          => 5,
        'expositor'             => 6,
        'coordenador_comissao_cientifica' => 7,
        'outras_comissoes' => 8,
        'inscrito_atividade' => 9,
    ];

    public function assinaturas()
    {
        return $this->belongsToMany(Assinatura::class, 'assinatura_certificado', 'certificado_id', 'assinatura_id')->orderBy('nome');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'certificado_user')->withPivot('id', 'valido', 'validacao', 'trabalho_id', 'palestra_id', 'comissao_id', 'atividade_id')->withTimestamps();
    }

    public function usuariosPalestrantes()
    {
        return $this->belongsToMany(Palestrante::class, 'certificado_user', 'certificado_id', 'user_id')->withPivot('id', 'valido', 'validacao', 'trabalho_id', 'palestra_id', 'comissao_id', 'atividade_id')->withTimestamps();
    }

    public function medidas()
    {
        return $this->hasMany(Medida::class);
    }

    public function atividade()
    {
        return $this->hasOne(Atividade::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function tipoComissao()
    {
        return $this->belongsTo(TipoComissao::class);
    }

    public function setAtributes($request)
    {
        $this->local = $request['local'];
        $this->verso = $request['verso'];
        if ($this->verso) {
            $this->has_imagem_verso = $request['has_imagem_verso'];
        }
        $this->nome = $request['nome'];
        $this->texto = $request['texto'];
        $this->tipo = $request['tipo'];
        $this->data = $request['data'];
        $this->imagem_assinada = $request['imagem_assinada'];
        if (array_key_exists('tipo_comissao_id', $request)) {
            $this->tipo_comissao_id = $request['tipo_comissao_id'];
        }
        if (array_key_exists('atividade_id', $request)) {
            if ($request['atividade_id'] == 0) {
                $this->atividade_id = null;
            } else {
                $this->atividade_id = $request['atividade_id'];
            }
        }
    }
}
