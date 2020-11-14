<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ComissaoOrganizadoraEvento extends Pivot
{
    public $incrementing = true;

    protected $fillable = [
        'evento_id',
        'user_id',
    ];
}
