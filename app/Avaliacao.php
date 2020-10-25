<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $fillable = [
        'opcao_criterio_id','revisor_id','trabalho_id',
    ];

    public function opcaoCriterio() {
        return $this->belongsTo('App\OpcoesCriterio', 'opcao_criterio_id');
    }

    public function revisor() {
        return $this->belongsTo('App\Revisor', 'revisor_id');
    }

    public function trabalho() {
        return $this->belongsTo('App\Trabalho', 'trabalho_id');
    }
}
