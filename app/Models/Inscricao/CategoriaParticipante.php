<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class CategoriaParticipante extends Model
{
    protected $fillable = [
        'nome', 'valor_total', 'evento_id', 'descricao',
    ];

    protected $casts = [
        'limite_inscricao' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'evento_id');
    }

    public function valores()
    {
        return $this->hasMany('App\Models\Inscricao\ValorCategoria', 'categoria_participante_id');
    }

    public function camposNecessarios()
    {
        return $this->belongsToMany('App\Models\Inscricao\CampoFormulario', 'campo_necessarios', 'categoria_participante_id', 'campo_formulario_id');
    }

    public function promocoes()
    {
        return $this->belongsToMany('App\Models\Inscricao\Promocao', 'exibir_promocaos', 'categoria_participante_id', 'promocao_id');
    }

    public function inscricoes()
    {
        return $this->hasMany('App\Models\Inscricao\Inscricao', 'categoria_participante_id');
    }
}
