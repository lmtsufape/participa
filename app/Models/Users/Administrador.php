<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class Administrador extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
