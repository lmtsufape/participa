<?php

namespace App\Models\Submissao;

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
        'nome', 'inicioSubmissao', 'fimSubmissao', 'inicioRevisao', 'fimRevisao',
        'inicioResultado', 'eventoId', 'texto', 'arquivo', 'caracteres', 'mincaracteres',
        'maxcaracteres', 'palavras', 'minpalavras', 'maxpalavras', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt', 'zip', 'svg',
        'mp4', 'mp3',
        'regra', 'template', 'modelo_apresentacao',
        'instrucoes',
    ];

    public function trabalho()
    {
        return $this->hasMany('App\Models\Submissao\Trabalho', 'modalidadeId');
    }

    public function criterios()
    {
        return $this->hasMany('App\Models\Submissao\Criterio', 'modalidadeId');
    }

    public function revisores()
    {
        return $this->hasMany('App\Models\Users\Revisor', 'modalidadeId');
    }

    public function forms()
    {
        return $this->hasMany('App\Models\Submissao\Form', 'modalidadeId');
    }

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'evento_id');
    }

    public function mensagensParecer()
    {
        return $this->hasMany('App\Models\Submissao\MensagemParecer');
    }

    public function tiposApresentacao()
    {
        return $this->hasMany('App\Models\Submissao\TipoApresentacao');
    }

    /**
     * Get all of the datasExtras for the Modalidade
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function datasExtras(): HasMany
    {
        return $this->hasMany(DataExtra::class);
    }

    /**
     * Pega todas as datas extras com que permitem submissão
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
        return $this->datasExtrasComSubmissao()->where('inicio', '<=', $agora)->where('fim', '>=', $agora)->exists();
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

    public function midiasExtra()
    {
        return $this->hasMany('App\Models\Submissao\MidiaExtra');
    }

    public function tiposAceitos()
    {
        $tiposcadastrados = [];
        if ($this->arquivo == true) {
            if ($this->pdf == true) {
                array_push($tiposcadastrados, 'pdf');
            }
            if ($this->jpg == true) {
                array_push($tiposcadastrados, 'jpg');
            }
            if ($this->jpeg == true) {
                array_push($tiposcadastrados, 'jpeg');
            }
            if ($this->png == true) {
                array_push($tiposcadastrados, 'png');
            }
            if ($this->docx == true) {
                array_push($tiposcadastrados, 'docx');
            }
            if ($this->odt == true) {
                array_push($tiposcadastrados, 'odt');
            }
            if ($this->zip == true) {
                array_push($tiposcadastrados, 'zip');
            }
            if ($this->svg == true) {
                array_push($tiposcadastrados, 'svg');
            }
            if ($this->mp4 == true) {
                array_push($tiposcadastrados, 'mp4');
            }
            if ($this->mp3 == true) {
                array_push($tiposcadastrados, 'mp3');
            }

        }
        return $tiposcadastrados;
    }
}

