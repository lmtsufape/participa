<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ComissaoOrganizadoraEvento extends Pivot
{
    public $incrementing = true;

    protected $fillable = [
        'evento_id',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User', 'user_id');
    }

    public function evento(){
        return $this->belongsTo('App\Models\Submissao\Evento', 'evento_id');
    }
}
