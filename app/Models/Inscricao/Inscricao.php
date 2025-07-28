<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inscricao extends Model
{
    use softDeletes;
    protected $fillable = [
        'user_id', 'evento_id', 'pagamento_id', 'promocao_id', 'cupom_desconto_id', 'finalizada',
    ];


    protected array $dates = ['deleted_at'];

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'evento_id');
    }

    public function promocao()
    {
        return $this->belongsTo('App\Models\Inscricao\Promocao', 'promocao_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User', 'user_id');
    }

    public function pagamento()
    {
        return $this->belongsTo('App\Models\Inscricao\Pagamento', 'pagamento_id');
    }

    public function cupomDesconto()
    {
        return $this->belongsTo('App\Models\Inscricao\CupomDeDesconto', 'cupom_desconto_id');
    }

    public function camposPreenchidos()
    {
        return $this->belongsToMany('App\Models\Inscricao\CampoFormulario', 'valor_campo_extras', 'inscricao_id', 'campo_formulario_id')->orderBy('campo_formulario_id')->withPivot('valor');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Models\Inscricao\CategoriaParticipante', 'categoria_participante_id');
    }

    public function podeSubmeterTrabalho()
    {
        if ($this->categoria()->exists()) {
            return $this->categoria->permite_submissao;
        }
        return false;
    }
}
