<?php

namespace App\Models\Submissao;

use App\Models\Users\CoordEixoTematico;
use App\Models\Users\User;
use App\Models\CandidatoAvaliador;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Evento extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'descricao', 'tipo', 'dataInicio', 'dataFim', 'fotoEvento', 'icone',
        'enderecoId', 'coordenadorId', 'numMaxTrabalhos', 'numMaxCoautores', 'hasResumo',
        'evento_pai_id', 'email', 'data_limite_inscricao',
        'nome_en', 'descricao_en','fotoEvento_en', 'icone_en',
        'nome_es', 'descricao_es','fotoEvento_es', 'icone_es',
        'is_multilingual', 'instagram', 'contato_suporte'
    ];

    public function endereco()
    {
        return $this->belongsTo('App\Models\Submissao\Endereco', 'enderecoId');
    }

    public function atividade()
    {
        return $this->hasMany('App\Models\Submissao\Atividade', 'eventoId');
    }

    public function areas()
    {
        return $this->hasMany('App\Models\Submissao\Area', 'eventoId');
    }

    public function arquivoInfos()
    {
        return $this->hasMany(ArquivoInfo::class)->orderBy('order');;
    }

    public function modalidades()
    {
        return $this->hasMany('App\Models\Submissao\Modalidade', 'evento_id');
    }

    /**
     * Retorna o usuário criador do evento
     *
     * @return BelongsTo
     */
    public function coordenador()
    {
        return $this->belongsTo('App\Models\Users\User', 'coordenadorId');
    }

    /**
     * Retorna os usuários coordenadores atribuídos pelo usuário criador do evento
     *
     * @return BelongsToMany
     */
    public function coordenadoresEvento()
    {
        return $this->belongsToMany('App\Models\Users\User', 'coordenador_eventos', 'eventos_id', 'user_id')->using('App\Models\Users\CoordenadorEvento');
    }

    public function coordComissaoCientifica()
    {
        return $this->belongsToMany('App\Models\Users\User', 'coord_comissao_cientificas', 'eventos_id', 'user_id')->using('App\Models\Users\CoordComissaoCientifica');
    }

    public function userIsCoordComissaoCientifica(User $user)
    {
        return $this->coordComissaoCientifica->contains($user);
    }

    public function coordComissaoOrganizadora()
    {
        return $this->belongsToMany('App\Models\Users\User', 'coord_comissao_organizadoras', 'eventos_id', 'user_id')->using('App\Models\Users\CoordComissaoOrganizadora');
    }

    public function userIsCoordComissaoOrganizadora(User $user)
    {
        return $this->coordComissaoOrganizadora->contains($user);
    }

    public function revisors()
    {
        return $this->hasMany('App\Models\Users\Revisor', 'evento_id');
    }

    public function revisoresDaAreaEModalidadeComContadorDeAtribuicoes($area_id, $modalidade_id)
    {
        $id = $this->id;

        return $this->revisors()
            ->withCount(['trabalhosAtribuidos' => function (Builder $query) use ($id) {
                $query->where('eventoId', $id)->where('parecer', 'processando');
            }])
            ->get()
            ->where('modalidadeId', $modalidade_id)
            ->where('areaId', $area_id);
    }

    public function usuariosDaComissao()
    {
        return $this->belongsToMany('App\Models\Users\User', 'comissao_cientifica_eventos', 'evento_id', 'user_id');
    }

    public function formEvento()
    {
        return $this->hasOne('App\Models\Submissao\FormEvento', 'eventoId');
    }

    public function formSubTrab()
    {
        return $this->hasOne('App\Models\Submissao\FormSubmTraba', 'eventoId');
    }

    public function trabalhos()
    {
        return $this->hasMany('App\Models\Submissao\Trabalho', 'eventoId');
    }

    public function mensagensParecer()
    {
        return $this->hasMany('App\Models\Submissao\MensagemParecer');
    }

    public function usuariosDaComissaoOrganizadora()
    {
        return $this->belongsToMany('App\Models\Users\User', 'comissao_organizadora_eventos', 'evento_id', 'user_id');
    }

    public function promocoes()
    {
        return $this->hasMany('App\Models\Inscricao\Promocao', 'evento_id');
    }

    public function cuponsDeDesconto()
    {
        return $this->hasMany('App\Models\Inscricao\CupomDeDesconto', 'evento_id');
    }

    // public function revisores(){
    //   return $this->belongsToMany('App\Revisor', 'evento_revisors', 'evento_id', 'revisor_id')->withPivot('convite_aceito')->withTimestamps();
    // }

    public function categoriasParticipantes()
    {
        return $this->hasMany('App\Models\Inscricao\CategoriaParticipante', 'evento_id')->orderBy('created_at');
    }

    public function categoriasQuePermitemInscricao()
    {
        return $this->hasMany('App\Models\Inscricao\CategoriaParticipante', 'evento_id')
                    ->where('permite_inscricao', true)
                    ->where(function ($q) {
                        $q->whereNull('limite_inscricao')
                          ->orWhere('limite_inscricao', '>', now());
                    })
                    ->orderBy('created_at');
    }
    public function categoriasPermitidasParaUsuario()
    {
        return $this->categoriasQuePermitemInscricao()->get();
    }

    public function camposFormulario()
    {
        return $this->hasMany('App\Models\Inscricao\CampoFormulario', 'evento_id')->orderBy('created_at');
    }

    public function possuiFormularioDeInscricao()
    {
        return $this->camposFormulario()->count() > 0 && $this->categoriasParticipantes()->count() > 0;
    }

    public function inscricaos()
    {
        return $this->hasMany('App\Models\Inscricao\Inscricao');
    }

    public function subeventos()
    {
        return $this->hasMany('App\Models\Submissao\Evento', 'evento_pai_id');
    }

    public function eventoPai()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'evento_pai_id');
    }

    public function palestrantes()
    {
        return $this->hasManyThrough('App\Models\Submissao\Palestrante', 'App\Models\Submissao\Palestra');
    }

    public function outrasComissoes()
    {
        return $this->hasMany('App\Models\Submissao\TipoComissao');
    }

    public function palestras()
    {
        return $this->hasMany('App\Models\Submissao\Palestra');
    }

    public function memorias()
    {
        return $this->hasMany('App\Models\Submissao\Memoria')->orderBy('ordem');
    }

    public function inscritos()
    {
        $users_inscricoes = $this->inscricaos;

        if ($this->subeventos->count() > 0) {
            foreach ($this->subeventos as $subevento) {
                $users_inscricoes_sub = $subevento->inscricaos;
                $users_inscricoes = $users_inscricoes->merge($users_inscricoes_sub);
            }
        }

        return $users_inscricoes;
    }

    /**
     * Tells if the event subscriptions are done.
     *
     * @param  \App\Evento  $evento
     * @return bool
     */
    public function eventoInscricoesEncerradas()
    {
        if ($this->data_limite_inscricao != null) {
            if ($this->data_limite_inscricao < now()) {
                $encerrada = true;
            } else {
                $encerrada = false;
            }
        } else {
            if ($this->dataFim <= date_format(Carbon::today(), 'Y-m-d')) {
                $encerrada = true;
            } else {
                $encerrada = false;
            }
        }

        return $encerrada;
    }

    public function coordEixosTematicos(){
        return $this->hasMany(CoordEixoTematico::class);
    }

    public function candidatosAvaliadores(){
        return $this->hasMany(CandidatoAvaliador::class, 'evento_id');
    }
}
