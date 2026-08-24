<?php

namespace App\Models\Submissao;

use App\Enums\StatusForm;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['titulo', 'modalidadeId', 'instrucoes', 'form_original_id', 'form_anterior_id', 'versao', 'status', 'publicado_em'];

    protected function casts(): array
    {
        return [
            'status' => StatusForm::class,
            'publicado_em' => 'datetime',
        ];
    }

    public function original()
    {
        return $this->belongsTo(
            self::class,
            'form_original_id'
        );
    }

    public function anterior()
    {
        return $this->belongsTo(
            self::class,
            'form_anterior_id'
        );
    }

    public function proxima()
    {
        return $this->hasOne(
            self::class,
            'form_anterior_id'
        );
    }

    public function versoes()
    {
        return $this->hasMany(
            self::class,
            'form_original_id'
        )->orderBy('versao');
    }

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'modalidadeId');
    }

    public function perguntas()
    {
        return $this->hasMany(Pergunta::class)->orderBy('ordem');
    }
}
