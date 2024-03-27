<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampoFormulario extends Model
{
    protected $fillable = [
        'titulo', 'tipo', 'obrigatorio', 'evento_id',
    ];

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'evento_id');
    }

    public function categorias()
    {
        return $this->belongsToMany('App\Models\Inscricao\CategoriaParticipante', 'campo_necessarios', 'campo_formulario_id', 'categoria_participante_id');
    }

    public function inscricoesFeitas()
    {
        return $this->belongsToMany('App\Models\Inscricao\Inscricao', 'valor_campo_extras', 'campo_formulario_id', 'inscricao_id')->withPivot('valor');
    }

    /**
     * Get all of the opcoes for the CampoFormulario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function opcoes(): HasMany
    {
        return $this->hasMany(CampoFormularioSelect::class);
    }
}
