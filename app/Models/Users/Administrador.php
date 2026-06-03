<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administradors';
    
    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }
}
