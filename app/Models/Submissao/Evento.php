<?php

namespace App\Models\Submissao;

use App\Models\Users\CoordEixoTematico;
use App\Models\Users\User;
use App\Models\CandidatoAvaliador;
use App\Models\Inscricao\CampoFormulario;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\CupomDeDesconto;
use App\Models\Inscricao\Inscricao;
use App\Models\Inscricao\Promocao;
use App\Models\Users\Revisor;
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
        return $this->belongsTo(Endereco::class, 'enderecoId');
    }

    public function atividade()
    {
        return $this->hasMany(Atividade::class, 'eventoId');
    }

    public function areas()
    {
        return $this->hasMany(Area::class, 'eventoId');
    }

    public function arquivoInfos()
    {
        return $this->hasMany(ArquivoInfo::class)->orderBy('order');;
    }

    public function modalidades()
    {
        return $this->hasMany(Modalidade::class, 'evento_id');
    }

    /**
     * Retorna o usuário criador do evento
     *
     * @return BelongsTo
     */
    public function coordenador()
    {
        return $this->belongsTo(User::class, 'coordenadorId');
    }

    /**
     * Retorna os usuários coordenadores atribuídos pelo usuário criador do evento
     *
     * @return BelongsToMany
     */
    public function coordenadoresEvento()
    {
        return $this->belongsToMany(User::class, 'coordenador_eventos', 'eventos_id', 'user_id')->using('App\Models\Users\CoordenadorEvento');
    }

    public function coordComissaoCientifica()
    {
        return $this->belongsToMany(User::class, 'coord_comissao_cientificas', 'eventos_id', 'user_id')->using('App\Models\Users\CoordComissaoCientifica');
    }

    public function userIsCoordComissaoCientifica(User $user)
    {
        return $this->coordComissaoCientifica->contains($user);
    }

    public function coordComissaoOrganizadora()
    {
        return $this->belongsToMany(User::class, 'coord_comissao_organizadoras', 'eventos_id', 'user_id')->using('App\Models\Users\CoordComissaoOrganizadora');
    }

    public function userIsCoordComissaoOrganizadora(User $user)
    {
        return $this->coordComissaoOrganizadora->contains($user);
    }

    public function revisors()
    {
        return $this->hasMany(Revisor::class, 'evento_id');
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
        return $this->belongsToMany(User::class, 'comissao_cientifica_eventos', 'evento_id', 'user_id');
    }

    public function formEvento()
    {
        return $this->hasOne(FormEvento::class, 'eventoId');
    }

    public function formSubTrab()
    {
        return $this->hasOne(FormSubmTraba::class, 'eventoId');
    }

    public function trabalhos()
    {
        return $this->hasMany(Trabalho::class, 'eventoId');
    }

    public function mensagensParecer()
    {
        return $this->hasMany(MensagemParecer::class);
    }

    public function usuariosDaComissaoOrganizadora()
    {
        return $this->belongsToMany(User::class, 'comissao_organizadora_eventos', 'evento_id', 'user_id');
    }

    public function promocoes()
    {
        return $this->hasMany(Promocao::class, 'evento_id');
    }

    public function cuponsDeDesconto()
    {
        return $this->hasMany(CupomDeDesconto::class, 'evento_id');
    }

    // public function revisores(){
    //   return $this->belongsToMany('App\Revisor', 'evento_revisors', 'evento_id', 'revisor_id')->withPivot('convite_aceito')->withTimestamps();
    // }

    public function categoriasParticipantes()
    {
        return $this->hasMany(CategoriaParticipante::class, 'evento_id')->orderBy('created_at');
    }

    public function categoriasQuePermitemInscricao()
    {
        return $this->hasMany(CategoriaParticipante::class, 'evento_id')
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
        return $this->hasMany(CampoFormulario::class, 'evento_id')->orderBy('created_at');
    }

    public function possuiFormularioDeInscricao()
    {
        return $this->camposFormulario()->count() > 0 && $this->categoriasParticipantes()->count() > 0;
    }

    public function inscricaos()
    {
        return $this->hasMany(Inscricao::class);
    }

    public function subeventos()
    {
        return $this->hasMany(Evento::class, 'evento_pai_id');
    }

    public function eventoPai()
    {
        return $this->belongsTo(Evento::class, 'evento_pai_id');
    }

    public function palestrantes()
    {
        return $this->hasManyThrough(Palestrante::class, 'App\Models\Submissao\Palestra');
    }

    public function outrasComissoes()
    {
        return $this->hasMany(TipoComissao::class);
    }

    public function palestras()
    {
        return $this->hasMany(Palestra::class);
    }

    public function memorias()
    {
        return $this->hasMany(Memoria::class)->orderBy('ordem');
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
