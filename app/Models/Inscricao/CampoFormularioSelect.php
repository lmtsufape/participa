<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampoFormularioSelect extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
    ];

    /**
     * Get the campoFormulario that owns the CampoFormularioSelect
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campoFormulario(): BelongsTo
    {
        return $this->belongsTo(CampoFormulario::class);
    }
}
