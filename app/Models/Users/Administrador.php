<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }
}
