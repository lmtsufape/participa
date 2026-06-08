<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MidiaExtra extends Model
{
    protected $fillable = [
        'nome', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt', 'zip', 'svg', 'mp4', 'mp3', 'modalidade_id',
        'ogg', 'wav', 'ogv', 'mpg', 'mpeg', 'mkv', 'avi', 'odp', 'pptx', 'csv', 'ods', 'xlsx',
    ];

    public function modalidade()
    {
        return $this->belongsTo('App\Models\Submissao\Modalidade', 'modalidade_id');
    }

    public function trabalhos()
    {
        return $this->belongsToMany(Trabalho::class, 'midia_extras_trabalho', 'midia_extra_id', 'trabalho_id')->withPivot('caminho');
    }

    public function tiposAceitos()
    {
        $extensoes = ['ogg', 'wav', 'ogv', 'mpg', 'mpeg', 'mkv', 'avi', 'odp', 'pptx', 'csv', 'ods', 'xlsx', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt', 'zip', 'svg', 'mp4', 'mp3'];
        $tiposcadastrados = array_filter($this->getAttributes(), function ($value, $key) use ($extensoes) {
            if ($value == true && in_array($key, $extensoes)) {
                return $key;
            }
        }, ARRAY_FILTER_USE_BOTH);
        if ($tiposcadastrados != null) {
            $tiposcadastrados = array_keys($tiposcadastrados);
        }

        return $tiposcadastrados;
    }

    protected function hyphenizeNome(): Attribute
    {
        return Attribute::get(fn () =>
            Str::slug($this->nome) . '-' . $this->id
        );
    }
}
