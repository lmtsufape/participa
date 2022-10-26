<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataExtra extends Model
{
    protected $fillable = ['permitir_submissao', 'nome', 'inicio', 'fim'];

    /**
     * Get the modalidade that owns the DataExtra
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modalidade(): BelongsTo
    {
        return $this->belongsTo(Modalidade::class);
    }
}
