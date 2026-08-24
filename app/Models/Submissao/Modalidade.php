<?php

namespace App\Models\Submissao;

use App\Enums\StatusForm;
use App\Models\Users\Revisor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modalidade extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'inicioSubmissao', 'fimSubmissao', 'inicioRevisao', 'fimRevisao', 'inicioCorrecao', 'fimCorrecao', 'inicioValidacao', 'fimValidacao', 'inicioResultado',
        'eventoId', 'texto', 'arquivo', 'caracteres', 'mincaracteres', 'evento_id',
        'maxcaracteres', 'palavras', 'minpalavras', 'maxpalavras',
        'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt', 'zip', 'svg', 'mp4', 'mp3', 'ogg', 'wav', 'ogv', 'mpg', 'mpeg', 'mkv', 'avi', 'odp', 'pptx', 'csv', 'ods', 'xlsx',
        'regra', 'template', 'modelo_apresentacao', 'instrucoes','numMaxCoautores', 'nome_en', 'nome_es','ordem'
    ];


    protected $casts = [
        'inicioSubmissao' => 'datetime',
        'fimSubmissao' => 'datetime',
        'inicioRevisao' => 'datetime',
        'fimRevisao' => 'datetime',
        'inicioCorrecao' => 'datetime',
        'fimCorrecao' => 'datetime',
        'inicioValidacao' => 'datetime',
        'fimValidacao' => 'datetime',
        'inicioResultado' => 'datetime',
    ];

    public function trabalho()
    {
        return $this->hasMany(Trabalho::class, 'modalidadeId');
    }

    public function criterios()
    {
        return $this->hasMany(Criterio::class, 'modalidadeId');
    }

    public function revisores()
    {
        return $this->hasMany(Revisor::class, 'modalidadeId');
    }

    public function forms()
    {
        return $this->hasMany(Form::class, 'modalidadeId');
    }

    public function formAtual()
    {
        return $this->hasOne(Form::class, 'modalidadeId')->where('status', StatusForm::Publicado)->latestOfMany('versao');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function mensagensParecer()
    {
        return $this->hasMany(MensagemParecer::class);
    }

    public function tiposApresentacao()
    {
        return $this->hasMany(TipoApresentacao::class);
    }

    /**
     * Get all of the datasExtras for the Modalidade
     */
    public function datasExtras(): HasMany
    {
        return $this->hasMany(DataExtra::class);
    }

    /**
     * Pega todas as datas extras com que permitem submissão
     */
    public function datasExtrasComSubmissao(): HasMany
    {
        return $this->hasMany(DataExtra::class)->where('permitir_submissao', true);
    }

    public function estaEmPeriodoDeSubmissao()
    {
        $agora = now();
        if ($this->inicioSubmissao <= $agora && $this->fimSubmissao >= $agora) {
            return true;
        }

        return false;
    }

    public function estaEmPeriodoExtraDeCorrecao()
    {
        $agora = now();

        return $this->datasExtrasComSubmissao()->where('inicio', '<=', $agora)->where('fim', '>=', $agora)->exists();
    }

    public function estaEmPeriodoDeCorrecao()
    {
        return $this->inicioCorrecao <= now() && now() <= $this->fimCorrecao;
    }

    public function getUltimaDataAttribute()
    {
        if ($this->datasExtras()->exists()) {
            $maiorDataExtra = $this->datasExtras()->max('fim');

            return max($maiorDataExtra, $this->inicioResultado);
        } else {
            return $this->inicioResultado;
        }
    }

    public function emPeriodoDeValidacao(){
        return $this->inicioValidacao <= now() && now() <= $this->fimValidacao;
    }

    public function midiasExtra()
    {
        return $this->hasMany(MidiaExtra::class);
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
}
