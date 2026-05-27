<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class CategoriaParticipante extends Model
{
    protected $fillable = [
        'nome', 'valor_total', 'evento_id', 'descricao','porcentagem_desconto_associado',
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

    public function valorComDescontoDeAssociado()
    {
        $valorOriginal = $this->valor_total;
        if (auth()->check() && auth()->user()->ehAssociado() && $this->porcentagem_desconto_associado > 0) {
            $desconto = ($valorOriginal * $this->porcentagem_desconto_associado) / 100;
            return $valorOriginal - $desconto;
        }

        return $valorOriginal;
    }
}
