<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ComissaoEvento extends Pivot
{
    /**
 * Indicates if the IDs are auto-incrementing.
 *
 * @var bool
 */
    public $incrementing = true;

    protected $fillable = [
        'evento_id',
        'user_id',
    ];

}
