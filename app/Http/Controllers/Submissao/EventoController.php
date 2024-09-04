<?php

namespace App\Http\Controllers\Submissao;

use App\Exports\AvaliacoesExport;
use App\Exports\InscritosExport;
use App\Exports\TrabalhosExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Mail\AvisoPeriodoCorrecao;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use App\Mail\EventoCriado;
use App\Models\Inscricao\Inscricao;
use App\Models\Submissao\Area;
use App\Models\Submissao\AreaModalidade;
use App\Models\Submissao\Atividade;
use App\Models\Submissao\Criterio;
use App\Models\Submissao\Endereco;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Form;
use App\Models\Submissao\FormEvento;
use App\Models\Submissao\FormSubmTraba;
use App\Models\Submissao\Modalidade;
use App\Models\Submissao\Paragrafo;
use App\Models\Submissao\Pergunta;
use App\Models\Submissao\Resposta;
use App\Models\Submissao\Trabalho;
use App\Models\Users\Coautor;
use App\Models\Users\ComissaoEvento;
use App\Models\Users\CoordenadorEvento;
use App\Models\Users\Revisor;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Barryvdh\DomPDF\Facade\Pdf;

// dd($request->all());
class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::all();
        // $comissaoEvento = ComissaoEvento::all();
        // $eventos = Evento::where('coordenadorId', Auth::user()->id)->get();

        return view('coordenador.home', ['eventos' => $eventos]);
    }

    public function areaComissao()
    {
        $user = User::find(auth()->user()->id);
        $eventos = $user->membroComissaoEvento;

        return view('comissao.home')->with(['eventos' => $eventos]);
    }

    public function informacoes(Request $request)
    {
        $evento = Evento::with([
            'modalidades' => function ($query) {
                $query->withCount([
                    'trabalho as enviados_count' => fn ($query) => $query->where('status', 'rascunho'),
                    'trabalho as arquivados_count' => fn ($query) => $query->where('status', 'arquivado'),
                    'trabalho as avaliados_count' => fn ($query) => $query->whereHas('atribuicoes', fn ($query) => $query->where('parecer', '!=', 'processando')),
                    'trabalho as pendentes_count' => fn ($query) => $query->where('avaliado', 'processando')->where('status', '!=', 'arquivado'),
                ]);
            },
            'atividade' => function ($query) {
                $query->withCount([
                    'users as inscritos_count',
                ]);
            },
            ])
            ->find($request->eventoId);

        $this->authorize('isCoordenadorOrCoordenadorDasComissoesOrIsCoordenadorDeOutrasComissoes', $evento);

        $evento->loadCount([
            'inscricaos',
            'inscricaos as inscricoes_validadas_count' => fn ($query) => $query->where('finalizada', true),
            'trabalhos as enviados_count' => fn ($query) => $query->where('status', 'rascunho'),
            'trabalhos as arquivados_count' => fn ($query) => $query->where('status', 'arquivado'),
            'trabalhos as avaliados_count' => fn ($query) => $query->whereHas('atribuicoes', fn ($query) => $query->where('parecer', '!=', 'processando')),
            'trabalhos as pendentes_count' => fn ($query) => $query->where('avaliado', 'processando')->where('status', '!=', 'arquivado'),
            'revisors as revisores_count' => fn ($query) => $query->select(DB::raw('count(distinct user_id)')),
            'usuariosDaComissao as comissao_cientifica_count',
            'usuariosDaComissaoOrganizadora as comissao_organizadora_count',
        ]);

        return view('coordenador.informacoes', [
            'evento' => $evento,
        ]);
    }

    public function definirSubmissoes(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();

        return view('coordenador.trabalhos.definirSubmissoes', [
            'evento' => $evento,
        ]);
    }

    public function listarTrabalhos(Request $request, $column = 'titulo', $direction = 'asc', $status = 'rascunho')
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        // $users = $evento->usuariosDaComissao;

        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();

        $trabalhos = null;

        if ($column == 'autor') {
            //Pela logica da implementacao de status, rascunho eh o parametro para encontrar todos os trabalhos diferentes de arquivado
            if ($status == 'rascunho') {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    ));
                }
            } elseif ($status == 'with_revisor') {
                $trabalhos_id = DB::table('trabalhos')->join('atribuicaos', 'atribuicaos.trabalho_id', '=', 'trabalhos.id')
                    ->where('trabalhos.eventoId', $evento->id)
                    ->get('trabalhos.id');

                $trabalhos = Trabalho::whereIn('id', $trabalhos_id->pluck('id'))
                    ->where('status', '!=', 'arquivado')
                    ->get();

                $trabalhos = $trabalhos->groupBy('modalidadeId');
                foreach ($trabalhos as $i => $modalidade) {
                    $modalidade = $modalidade->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    );

                    $trabalhos[$i] = $modalidade;
                }
                $trabalhos = $trabalhos->sortBy(function ($modalidade) {
                    return $modalidade->first()->modalidade->nome;
                });
            } elseif ($status == 'no_revisor') {
                $trabalhos_com_revisor_id = DB::table('trabalhos')->join('atribuicaos', 'atribuicaos.trabalho_id', '=', 'trabalhos.id')
                    ->where('trabalhos.eventoId', $evento->id)
                    ->get('trabalhos.id');

                $trabalhos_id = DB::table('trabalhos')
                    ->where('trabalhos.eventoId', $evento->id)
                    ->get('trabalhos.id');

                $trabalhos_sem_revisores_collection = collect();

                foreach ($trabalhos_id as $trabalho) {
                    if (!$trabalhos_com_revisor_id->contains($trabalho)) {
                        $trabalhos_sem_revisores_collection->push($trabalho);
                    }
                }

                $trabalhos = Trabalho::whereIn('id', $trabalhos_sem_revisores_collection->pluck('id'))
                    ->where('status', '!=', 'arquivado')
                    ->get();

                $trabalhos = $trabalhos->groupBy('modalidadeId');
                foreach ($trabalhos as $i => $modalidade) {
                    $modalidade = $modalidade->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    );

                    $trabalhos[$i] = $modalidade;
                }
                $trabalhos = $trabalhos->sortBy(function ($modalidade) {
                    return $modalidade->first()->modalidade->nome;
                });
            } else {
                // Não tem como ordenar os trabalhos por nome do autor automaticamente
                // Já que na tabale a de trabalhos não existe o nome do autor
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    ));
                }
            }
        } else {
            if ($status == 'rascunho') {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->orderBy($column, $direction)->get());
                }
            } elseif ($status == 'with_revisor') {
                $trabalhos_id = DB::table('trabalhos')->join('atribuicaos', 'atribuicaos.trabalho_id', '=', 'trabalhos.id')
                    ->where('trabalhos.eventoId', $evento->id)
                    ->get('trabalhos.id');

                $trabalhos = Trabalho::whereIn('id', $trabalhos_id->pluck('id'))
                    ->where('status', '!=', 'arquivado')
                    ->get();

                $trabalhos = $trabalhos->groupBy('modalidadeId');

                if ($column == 'titulo') {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(
                            function ($trabalho) {
                                return $trabalho->titulo;
                            },
                            SORT_REGULAR,
                            $direction == 'desc'
                        );
                        $trabalhos[$i] = $modalidade;
                    }
                } elseif ($column == 'areaId') {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(
                            function ($trabalho) {
                                return $trabalho->area->nome;
                            },
                            SORT_REGULAR,
                            $direction == 'desc'
                        );
                        $trabalhos[$i] = $modalidade;
                    }
                } elseif ($column == 'created_at') {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(
                            function ($trabalho) {
                                return $trabalho->created_at;
                            },
                            SORT_REGULAR,
                            $direction == 'desc'
                        );
                        $trabalhos[$i] = $modalidade;
                    }
                }

                $trabalhos = $trabalhos->sortBy(function ($modalidade) {
                    return $modalidade->first()->modalidade->nome;
                });
            } elseif ($status == 'no_revisor') {
                $trabalhos_com_revisor_id = DB::table('trabalhos')->join('atribuicaos', 'atribuicaos.trabalho_id', '=', 'trabalhos.id')
                    ->where('trabalhos.eventoId', $evento->id)
                    ->get('trabalhos.id');

                $trabalhos_id = DB::table('trabalhos')
                    ->where('trabalhos.eventoId', $evento->id)
                    ->get('trabalhos.id');

                $trabalhos_sem_revisores_collection = collect();

                foreach ($trabalhos_id as $trabalho) {
                    if (!$trabalhos_com_revisor_id->contains($trabalho)) {
                        $trabalhos_sem_revisores_collection->push($trabalho);
                    }
                }

                $trabalhos = Trabalho::whereIn('id', $trabalhos_sem_revisores_collection->pluck('id'))
                    ->where('status', '!=', 'arquivado')
                    ->get();

                $trabalhos = $trabalhos->groupBy('modalidadeId');

                if ($column == 'titulo') {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(
                            function ($trabalho) {
                                return $trabalho->titulo;
                            },
                            SORT_REGULAR,
                            $direction == 'desc'
                        );
                        $trabalhos[$i] = $modalidade;
                    }
                } elseif ($column == 'areaId') {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(
                            function ($trabalho) {
                                return $trabalho->area->nome;
                            },
                            SORT_REGULAR,
                            $direction == 'desc'
                        );
                        $trabalhos[$i] = $modalidade;
                    }
                } elseif ($column == 'created_at') {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(
                            function ($trabalho) {
                                return $trabalho->created_at;
                            },
                            SORT_REGULAR,
                            $direction == 'desc'
                        );
                        $trabalhos[$i] = $modalidade;
                    }
                }

                $trabalhos = $trabalhos->sortBy(function ($modalidade) {
                    return $modalidade->first()->modalidade->nome;
                });
            } else {
                // Como aqui é um else, então $trabalhos nunca vai ser null
                // Busca os trabalhos da forma como era feita antes
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->orderBy($column, $direction)->get());
                }
                //
            }
        }

        return view('coordenador.trabalhos.listarTrabalhos', [
            'evento' => $evento,
            'areas' => $areas,
            'trabalhosPorModalidade' => $trabalhos,
            'agora' => now(),
            'status' => $status,
        ]);
    }

    public function listarAvaliacoes(Request $request, $column = 'titulo', $direction = 'asc', $status = 'rascunho')
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();
        $trabalhos = null;
        if ($column == 'autor') {
            if ($status == 'rascunho') {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    ));
                }
            } else {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    ));
                }
            }
        } elseif ($column == 'area') {
            if ($status == 'rascunho') {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->area->nome;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    ));
                }
            } else {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->area->nome;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    ));
                }
            }
        } else {
            if ($status == 'rascunho') {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    //dd($modalidadeId->id);
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->orderBy($column, $direction)->get());
                }
            } else {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->orderBy($column, $direction)->get());
                }
            }
        }

        return view(
            'coordenador.trabalhos.listarRespostas',
            [
                'evento' => $evento,
                'trabalhosPorModalidade' => $trabalhos,
            ]
        );
    }

    public function listarTrabalhosModalidades(Request $request, $column = 'titulo', $direction = 'asc', $status = 'arquivado')
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $modalidade = Modalidade::find($request->modalidadeId);
        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->orderBy('nome')->get();

        $trabalhos = null;

        if ($column == 'autor') {
            //Pela logica da implementacao de status, rascunho eh o parametro para encontrar todos os trabalhos diferentes de arquivado
            if ($status == 'rascunho') {
                $trabalhos = Trabalho::whereIn('areaId', $areasId)->where([['status', '!=', 'arquivado'], ['modalidadeId', $request->modalidadeId]])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->autor->name;
                    },
                    SORT_REGULAR,
                    $direction == 'desc'
                );
            } else {
                // Não tem como ordenar os trabalhos por nome do autor automaticamente
                // Já que na tabale a de trabalhos não existe o nome do autor
                $trabalhos = Trabalho::whereIn('areaId', $areasId)->where([['status', '=', $status], ['modalidadeId', $request->modalidadeId]])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->autor->name; // Ordena o pelo valor do nome do autor
                    },
                    SORT_REGULAR, // Usa o método padrão de ordenação
                    $direction == 'desc'
                ); // Se true, então ordena decrescente
            }
        } else {
            if ($status == 'rascunho') {
                $trabalhos = Trabalho::whereIn('areaId', $areasId)->where([['status', '!=', 'arquivado'], ['modalidadeId', $request->modalidadeId]])->orderBy($column, $direction)->get();
            } else {
                // Como aqui é um else, então $trabalhos nunca vai ser null
                // Busca os trabalhos da forma como era feita antes
                $trabalhos = Trabalho::whereIn('areaId', $areasId)->where([['status', '=', $status], ['modalidadeId', $request->modalidadeId]])->orderBy($column, $direction)->get();
            }
        }

        return view('coordenador.trabalhos.listarTrabalhosModalidades', [
            'evento' => $evento,
            'areas' => $areas,
            'trabalhos' => $trabalhos,
            'agora' => now(),
            'modalidade' => $modalidade,
        ]);
    }

    public function cadastrarComissao(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();

        return view('coordenador.comissao.cadastrarComissao', [
            'evento' => $evento,
        ]);
    }

    public function cadastrarAreas(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();

        return view('coordenador.areas.cadastrarAreas', [
            'evento' => $evento,
        ]);
    }

    public function listarAreas(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $areas = $areas->sortBy('nome', SORT_NATURAL)->values()->all();

        return view('coordenador.areas.listarAreas', [
            'evento' => $evento,
            'areas' => $areas,
        ]);
    }

    public function cadastrarRevisores(Request $request)
    {
        // return view('coordenador.revisores.cadastrarRevisores', [
        //             'evento'                  => $evento,
        //             'areas'                   => $areas,
        //             'modalidades'             => $modalidades,

        //           ]);
    }

    public function listarRevisores(Request $request)
    {
        $evento = Evento::find($request->eventoId);

        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $revisores = User::whereHas('revisor', function (Builder $query) use ($evento) {
            $query->where('evento_id', $evento->id);
        })->orderBy('name')->get();
        $contadores = $evento->revisors()->withCount([
            'trabalhosAtribuidos as avaliados_count' => function (Builder $query) {
                $query->where('parecer', 'avaliado')->orWhere('parecer', 'encaminhado');
            },
            'trabalhosAtribuidos as processando_count' => function (Builder $query) {
                $query->where('parecer', 'processando');
            },
        ])->get();
        $areas = $evento->areas;
        $modalidades = $evento->modalidades;

        return view('coordenador.revisores.listarRevisores', [
            'evento' => $evento,
            'revisores' => $revisores,
            // 'revs'                    => $revisores,
            'areas' => $areas,
            'modalidades' => $modalidades,
            'contadores' => $contadores,
        ]);
    }

    public function listarUsuarios(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenador', $evento);
        $usuarios = User::doesntHave('administradors')->get();

        return view('coordenador.revisores.listarUsuarios', compact('usuarios', 'evento'));
    }

    public function definirCoordComissao(Request $request)
    {

        $evento = Evento::find($request->eventoId);

        $users = $evento->usuariosDaComissao;
        $coordenadores = $evento->coordComissaoCientifica->pluck('id')->all();
        return view('coordenador.comissao.definirCoordComissao', compact('evento', 'users', 'coordenadores'));
    }

    public function listarComissao(Request $request)
    {
        $evento = Evento::find($request->eventoId);

        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $users = $evento->usuariosDaComissao;

        return view('coordenador.comissao.listarComissao', [
            'evento' => $evento,
            'users' => $users,
        ]);
    }

    public function exportInscritos(Evento $evento, Request $request)
    {
        $nome = $this->somenteLetrasNumeros($evento->nome);

        return (new InscritosExport($evento))->download($nome . '.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportTrabalhos(Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $trabalhos = Trabalho::where('eventoId', $evento->id)
            ->get()->map(function ($trabalho) {
                return [
                    $trabalho->area->nome,
                    $trabalho->modalidade->nome,
                    $trabalho->titulo,
                    $trabalho->autor->name,
                    $trabalho->autor->cpf,
                    $trabalho->autor->email,
                    $trabalho->autor->celular,
                    $this->coautoresToString($trabalho, 'nome'),
                    $this->coautoresToString($trabalho, 'cpf'),
                    $this->coautoresToString($trabalho, 'email'),
                    $this->coautoresToString($trabalho, 'celular'),
                ];
            })->collect();

        $nome = $this->somenteLetrasNumeros($evento->nome);

        return (new TrabalhosExport($trabalhos))->download($nome . '- Trabalhos.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function somenteLetrasNumeros($string)
    {
        return preg_replace('/[^A-Za-z0-9\_ ]/', '', $string);
    }

    private function coautoresToString(Trabalho $trabalho, $campo)
    {
        $stringRetorno = '';

        if ($campo == 'nome') {
            foreach ($trabalho->coautors as $coautor) {
                if ($coautor->user->id != $trabalho->autorId) {
                    $stringRetorno .= $coautor->user->name . ', ';
                }
            }
        } elseif ($campo == 'email') {
            foreach ($trabalho->coautors as $coautor) {
                if ($coautor->user->id != $trabalho->autorId) {
                    $stringRetorno .= $coautor->user->email . ', ';
                }
            }
        } elseif ($campo == 'celular') {
            foreach ($trabalho->coautors as $coautor) {
                if ($coautor->user->id != $trabalho->autorId) {
                    $stringRetorno .= $coautor->user->celular . ', ';
                }
            }
        }

        return substr($stringRetorno, 0, strlen($stringRetorno) - 2);
    }

    public function exportAvaliacoes(Evento $evento, Modalidade $modalidade, Form $form)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $trabalhosCollect = collect();
        Trabalho::where([['eventoId', $evento->id], ['modalidadeId', $modalidade->id]])
            ->get()
            ->map(function ($trabalho) use ($form, $trabalhosCollect) {
                if ($trabalho->atribuicoes->first() != null) {
                    $trabalho->atribuicoes->map(function ($avaliacao) use ($trabalho, $form, $trabalhosCollect) {
                        $trabalhosCollect->push($this->makeRepostasExportAvaliacoes($trabalho, $form, $avaliacao));
                    });
                } else {
                    $trabalhosCollect->push($this->makeRepostasExportAvaliacoes($trabalho, $form, null));
                }
            })->collect();
        $trabalhosCollect = $trabalhosCollect->filter();

        $nome = $this->somenteLetrasNumeros($evento->nome);

        return (new AvaliacoesExport($trabalhosCollect, $this->makeHeadingsExportAvaliacoes($form)))->download($nome . ' - Avaliacões - ' . $modalidade->nome . ' - ' . $form->titulo . '.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function makeHeadingsExportAvaliacoes($form)
    {
        $retorno = [];
        array_push($retorno, 'Área/Eixo');
        array_push($retorno, 'Modalidade');
        array_push($retorno, 'Avaliador(a)');
        array_push($retorno, 'Título do trabalho');

        foreach ($form->perguntas as $pergunta) {
            array_push($retorno, $pergunta->pergunta);
        }

        return $retorno;
    }

    private function makeRepostasExportAvaliacoes($trabalho, $form, $revisor)
    {
        $retorno = [];
        array_push($retorno, $trabalho->area->nome);
        array_push($retorno, $trabalho->modalidade->nome);
        if ($revisor != null) {
            array_push($retorno, $revisor->user->name);
        } else {
            array_push($retorno, 'Sem avaliador');
        }
        array_push($retorno, $trabalho->titulo);

        $respostas = collect();
        if ($revisor != null) {
            foreach ($form->perguntas as $pergunta) {
                $respostas->push($pergunta->respostas->where('trabalho_id', $trabalho->id)->where('revisor_id', $revisor->id)->first());
            }
        }

        $vazio = false;

        foreach ($form->perguntas as $index => $pergunta) {
            $achou = false;
            if ($pergunta->respostas->first()->opcoes->count()) {
                foreach ($pergunta->respostas->first()->opcoes as $opcao) {
                    if (count($respostas) > $index && $respostas[$index] != null && $respostas[$index]->opcoes != null && $respostas[$index]->opcoes->pluck('titulo')->contains($opcao->titulo)) {
                        array_push($retorno, $respostas[$index]->opcoes[0]->titulo);
                        $achou = true;
                    }
                }
            } elseif ($pergunta->respostas->first()->paragrafo != null) {
                foreach ($pergunta->respostas as $resposta) {
                    if ($resposta->revisor != null && $resposta->trabalho != null && $resposta->paragrafo != null) {
                        if ($revisor != null) {
                            if ($resposta->revisor->user_id == $revisor->user->id && $resposta->trabalho->id == $trabalho->id) {
                                array_push($retorno, $resposta->paragrafo->resposta);
                                $achou = true;
                            }
                        } else {
                            array_push($retorno, 'Sem resposta');
                        }
                    }
                }
                if ($pergunta->respostas->first() == null) {
                    array_push($retorno, 'Sem resposta');
                }
            }
            $vazio = $vazio || $achou;
        }
        if ($vazio == false) {
            return [];
        }

        return $retorno;
    }

    public function cadastrarModalidade(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $areas = Area::where('eventoId', $evento->id)->get();
        $modalidades = Modalidade::where('evento_id', $evento->id)->get();

        return view('coordenador.modalidade.cadastrarModalidade', [
            'evento' => $evento,
            'areas' => $areas,
            'modalidades' => $modalidades,
        ]);
    }

    public function listarModalidade(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
        // $areaModalidades = AreaModalidade::whereIn('areaId', $areasId)->get();

        return view('coordenador.modalidade.listarModalidade', [
            'evento' => $evento,
            'modalidades' => $modalidades,
            // 'areaModalidades'         => $areaModalidades,
        ]);
    }

    public function listarCorrecoes(Request $request, $column = 'titulo', $direction = 'asc')
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();
        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $trabalhos = collect();
        if ($column == 'titulo') {
            foreach ($modalidades as $modalidade) {
                $trabalhosArea = collect();
                foreach ($areas as $area) {
                    $trabalhosArea->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado'], ['areaId', $area->id]])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR
                    ));
                }
                $trabalhos->push($trabalhosArea);
                //dd($trabalhosArea);
            }
        } elseif ($column == 'data') {
            foreach ($modalidades as $modalidade) {
                $trabalhosArea = collect();
                foreach ($areas as $area) {
                    $trabalhosArea->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado'], ['areaId', $area->id]])->get()->sortBy(
                        function ($trabalho) {
                            if ($trabalho->arquivoCorrecao) {
                                return $trabalho->arquivoCorrecao->created_at;
                            } else {
                                return date('1900-01-30');
                            }
                        },
                        SORT_REGULAR,
                        $direction == 'asc'
                    ));
                }
                $trabalhos->push($trabalhosArea);
            }
        }

        //dd($trabalhos);

        return view('coordenador.trabalhos.listarTrabalhosCorrecoes', [
            'evento' => $evento,
            'trabalhosPorModalidade' => $trabalhos,
            'agora' => now(),
        ]);
    }

    public function cadastrarCriterio(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $modalidades = Modalidade::where('evento_id', $evento->id)->get();

        return view('coordenador.modalidade.cadastrarCriterio', [
            'evento' => $evento,
            'modalidades' => $modalidades,
        ]);
    }

    public function listarCriterios(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();
        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();

        // Criterios por modalidades
        $criteriosModalidade = [];
        foreach ($modalidades as $indice) {
            $criterios = Criterio::where('modalidadeId', $indice->id)->orderBy('nome')->get();
            for ($i = 0; $i < count($criterios); $i++) {
                if (!in_array($criterios[$i], $criteriosModalidade)) {
                    array_push($criteriosModalidade, $criterios[$i]);
                }
            }
        }

        return view('coordenador.modalidade.listarCriterio', [
            'evento' => $evento,
            'modalidades' => $modalidades,
            'criterios' => $criteriosModalidade,
        ]);
    }

    public function forms(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();

        return view('coordenador.modalidade.formulario', compact(
            'evento',
            'modalidades'
        ));
    }

    public function atribuirForm(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $modalidade = Modalidade::find($request->modalidade_id);

        return view('coordenador.modalidade.atribuirFormulario', compact('evento', 'modalidade'));
    }

    public function salvarForm(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $modalidade = Modalidade::find($request->modalidade_id);
        $data = $request->all();
        $form = $modalidade->forms()->create([
            'titulo' => $data['titulo'],
            'instrucoes' => $data['instrucoes'],
        ]);
        foreach ($data['perguntas'] as $index => $value) {
            $pergunta = $form->perguntas()->create([
                'pergunta' => $value,
                'visibilidade' => $request->has('visibilidades') && array_key_exists($index, $request['visibilidades']),
            ]);

            $resposta = new Resposta();
            $resposta->pergunta_id = $pergunta->id;
            $resposta->save();

            if ($data['tipos'][$index] == 'paragrafo') {
                $paragrafo = new Paragrafo();
                $resposta->paragrafo()->save($paragrafo);
            } elseif ($data['tipos'][$index] == 'radio') {
                foreach ($data['opcoes'][$index] as $titulo) {
                    $resposta->opcoes()->create([
                        'titulo' => $titulo,
                        'tipo' => 'radio',
                    ]);
                }
            }
        }

        return view('coordenador.modalidade.atribuirFormulario', compact('evento', 'modalidade'))->with('message', 'Formulário cadastrado com sucesso');
    }

    public function updateForm(Request $request)
    {
        $form = Form::find($request->formEditId);
        $evento = $form->modalidade->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $data = $request->all();
        //dd($data);
        $perguntasMantidas = [];

        if (!isset($request->pergunta_id)) {
            return redirect()->back()->withErrors(['excluirFormulario' => 'Não é possivel apagar todas as perguntas!!']);
        }

        if (isset($request->pergunta_id)) {
            foreach ($request->pergunta_id as $key => $pergunta_id) {
                $pergunta = Pergunta::find($pergunta_id);
                $pergunta->pergunta = $request->pergunta[$key];

                $opcoes = $pergunta->respostas->first()->opcoes->sortBy('id');

                if (isset($data['checkboxVisibilidade_' . $pergunta->id])) {
                    $pergunta->visibilidade = true;
                } else {
                    $pergunta->visibilidade = false;
                }

                //Verificação de alteração em multipla escolha já existente
                if ($data['tipo'][$key] == 'radio') {
                    //dd($request->tituloRadio);
                    foreach ($request->tituloRadio['row' . $key] as $i => $titulo) {
                        $opcoes->first()->titulo = $titulo;
                        //Verificação de marcação da resposta da multipla escolha
                        if (isset($request->checkbox[$opcoes->first()->id])) {
                            $opcoes->first()->check = true;
                        } else {
                            $opcoes->first()->check = false;
                        }

                        $opcoes->first()->update();
                        $opcoes->shift();
                    }
                }

                $pergunta->update();

                array_push($perguntasMantidas, $pergunta->id);
            }
        }

        $perguntas = Pergunta::where('form_id', $data['formEditId'])->get();

        foreach ($perguntas as $pergunta) {
            if (!in_array($pergunta->id, $perguntasMantidas)) {
                $pergunta->delete();
            }
        }

        $perguntasView = $request->pergunta;
        $perguntasIdView = $request->pergunta_id;
        if (isset($perguntasView) && isset($perguntasIdView)) {
            if (count($perguntasView) > count($perguntasIdView)) {
                for ($i = count($perguntasIdView); $i < count($perguntasView); $i++) {
                    $pergunta = new Pergunta();
                    $pergunta->form_id = $data['formEditId'];
                    $pergunta->pergunta = $request->pergunta[$i];
                    $pergunta->visibilidade = false;
                    $pergunta->save();

                    $resposta = new Resposta();
                    $resposta->pergunta_id = $pergunta->id;
                    $resposta->save();

                    if ($data['tipo'][$i] == 'paragrafo') {
                        $paragrafo = new Paragrafo();
                        $resposta->paragrafo()->save($paragrafo);
                    } elseif ($data['tipo'][$i] == 'checkbox') {
                        $listResposta = array_shift($data['tituloCheckoxMarc']);
                        foreach (array_shift($data['tituloCheckox']) as $key => $titulo) {
                            $resposta->opcoes()->create([
                                'titulo' => $titulo,
                                'tipo' => 'radio',
                                'check' => $listResposta[$key],
                            ]);
                        }
                    }
                }
            }
        }

        $form->titulo = $data['titulo' . $form->id];
        $form->instrucoes = $data['instrucoes' . $form->id];
        $form->update();

        return redirect()->back()->with(['mensagem' => 'Formulário editado com sucesso!']);
    }

    public function destroyForm($id)
    {
        $form = Form::find($id);
        $evento = $form->modalidade->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $temRespostas = false;
        foreach ($form->perguntas as $pergunta) {
            $primeira = $pergunta->respostas->first();
            if ($primeira && $primeira->opcoes && $primeira->opcoes->count()) {
                //Resposta com Multipla escolha:
            } elseif ($primeira && $primeira->paragrafo && $primeira->paragrafo->count()) {
                foreach ($pergunta->respostas as $resposta) {
                    if ($resposta->revisor != null || $resposta->trabalho != null) {
                        $temRespostas = true;
                        break;
                    }
                }
            } elseif ($temRespostas) {
                break;
            }
        }
        //dd($temRespostas);

        if (!$temRespostas) {
            $form->delete();

            return redirect()->back()->with(['mensagem' => 'Formulário excluído com sucesso!']);
        } else {
            return redirect()->back()->withErrors(['excluirFormulario' => 'Não é possível excluir. Existem respostas submetidas ligadas a este formulário.']);
        }
    }

    public function visualizarForm(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $modalidade = Modalidade::find($request->modalidade_id);
        // $form = $modalidade->forms;
        $data = $request->all();

        return view('coordenador.modalidade.visualizarFormulario', compact('evento', 'modalidade'));
    }

    public function respostas(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $modalidade = Modalidade::find($request->modalidade_id);
        // $form = $modalidade->forms;
        $data = $request->all();

        return view('coordenador.modalidade.visualizarRespostas', compact('evento', 'modalidade'));
    }

    public function respostasToPdf(Modalidade $modalidade)
    {
        $evento = $modalidade->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $pdf = Pdf::loadView('coordenador.modalidade.respostasPdf', ['modalidade' => $modalidade])->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->stream("respostas-{$modalidade->nome}.pdf");
    }

    public function resumosToPdf(Evento $evento, Request $request, $column = 'titulo', $direction = 'asc', $status = 'rascunho')
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();
        $trabalhos = null;

        if ($column == 'autor') {
            //Pela logica da implementacao de status, rascunho eh o parametro para encontrar todos os trabalhos diferentes de arquivado
            if ($status == 'rascunho') {
                //dd($modalidadesId);
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    ));
                }
            } else {
                // Não tem como ordenar os trabalhos por nome do autor automaticamente
                // Já que na tabale a de trabalhos não existe o nome do autor
                $trabalhos = collect();
                //dd($modalidadesId);
                foreach ($modalidades as $modalidade) {
                    //dd($modalidadeId->id);
                    //dd(Trabalho::where([['modalidadeId', $modalidadeId->id], ['status', $status]])->get());
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == 'desc'
                    ));
                }
                //dd($trabalhos);
            }
        } else {
            if ($status == 'rascunho') {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    //dd($modalidadeId->id);
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->orderBy($column, $direction)->get());
                }
            } else {
                // Como aqui é um else, então $trabalhos nunca vai ser null
                // Busca os trabalhos da forma como era feita antes
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->orderBy($column, $direction)->get());
                }
                //
            }
        }

        $pdf = Pdf::loadView('coordenador.trabalhos.resumosPdf', ['trabalhosPorModalidade' => $trabalhos, 'evento' => $evento])->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download("resumos - {$evento->nome}.pdf");
    }

    public function listarRespostasTrabalhos(Request $request, $column = 'titulo', $direction = 'asc', $status = 'rascunho')
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        // $users = $evento->usuariosDaComissao;

        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->orderBy('nome')->get();

        $trabalhos = null;

        if ($column == 'autor') {
            if ($status == 'rascunho') {
                $trabalhos = Trabalho::where([['modalidadeId', $request->modalidadeId], ['status', '!=', 'arquivado']])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->autor->name;
                    },
                    SORT_REGULAR,
                    $direction == 'desc'
                );
            } else {
                $trabalhos = Trabalho::where([['modalidadeId', $request->modalidadeId], ['status', '=', 'arquivado']])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->autor->name;
                    },
                    SORT_REGULAR,
                    $direction == 'desc'
                );
            }
        } else {
            if ($status == 'rascunho') {
                $trabalhos = Trabalho::where([['modalidadeId', $request->modalidadeId], ['status', '!=', 'arquivado']])->orderBy($column, $direction)->get();
            } else {
                $trabalhos = Trabalho::where([['modalidadeId', $request->modalidadeId], ['status', '=', $status]])->orderBy($column, $direction)->get();
            }
        }

        return view('coordenador.trabalhos.listarRespostasTrabalhos', [
            'evento' => $evento,
            'areas' => $areas,
            'trabalhos' => $trabalhos,
            'agora' => now(),
        ]);
    }

    public function visualizarRespostaFormulario(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $modalidade = Modalidade::find($request->modalidadeId);
        $trabalho = Trabalho::find($request->trabalhoId);
        $revisor = Revisor::find($request->revisorId);
        $revisorUser = User::find($revisor->user_id);
        $respostas = [];

        $arquivoAvaliacao = $trabalho->arquivoAvaliacao()->where('revisorId', $revisor->id)->first();
        if ($arquivoAvaliacao == null) {
            $permissoes_revisao = Revisor::where([['user_id', $revisor->user_id], ['evento_id', $evento->id]])->get()->map->only(['id']);
            $arquivoAvaliacao = $trabalho->arquivoAvaliacao()->whereIn('revisorId', $permissoes_revisao)->first();
        }

        foreach ($modalidade->forms as $form) {
            foreach ($form->perguntas as $pergunta) {
                $respostas[$pergunta->id] = $pergunta->respostas->where('trabalho_id', $trabalho->id)->where('revisor_id', $revisor->id)->first();
            }
        }

        return view('coordenador.trabalhos.visualizarRespostaFormulario', compact('evento', 'modalidade', 'trabalho', 'revisorUser', 'revisor', 'respostas', 'arquivoAvaliacao'));
    }

    public function editarEtiqueta(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();
        $modalidades = Modalidade::all();
        // Criterios por modalidades
        $criteriosModalidade = [];
        foreach ($modalidades as $indice) {
            $criterios = Criterio::where('modalidadeId', $indice->id)->get();
            for ($i = 0; $i < count($criterios); $i++) {
                if (!in_array($criterios[$i], $criteriosModalidade)) {
                    array_push($criteriosModalidade, $criterios[$i]);
                }
            }
        }
        // dd($request);
        return view('coordenador.evento.editarEtiqueta', [
            'evento' => $evento,
            'etiquetas' => $etiquetas,
            'etiquetasSubTrab' => $etiquetasSubTrab,
            'criterios' => $criteriosModalidade,
        ]);
    }

    public function etiquetasTrabalhos(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();
        $modalidades = Modalidade::all();
        // Criterios por modalidades
        $criteriosModalidade = [];
        foreach ($modalidades as $indice) {
            $criterios = Criterio::where('modalidadeId', $indice->id)->get();
            for ($i = 0; $i < count($criterios); $i++) {
                if (!in_array($criterios[$i], $criteriosModalidade)) {
                    array_push($criteriosModalidade, $criterios[$i]);
                }
            }
        }


        return view('coordenador.evento.etiquetasTrabalhos', [
            'evento' => $evento,
            'etiquetas' => $etiquetas,
            'etiquetasSubTrab' => $etiquetasSubTrab,
            'criterios' => $criteriosModalidade,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->administradors()->exists()) {
            abort(403);
        }
        return view('evento.criarEvento');
    }

    public function createSubEvento($id)
    {
        $eventoPai = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $eventoPai);

        return view('evento.criarEvento', compact('eventoPai'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventoRequest $request)
    {
        $data = $request->all();
        $endereco = Endereco::create($data);
        $data['enderecoId'] = $endereco->id;
        $data['coordenadorId'] = Auth::user()->id;
        $data['data_limite_inscricao'] = $request->dataLimiteInscricao;
        $evento = Evento::create($data);

        $user = Auth::user();
        $validated = $request->validated();
        if (array_key_exists('email_coordenador', $validated)) {
            $coordenador = User::where('email', $validated['email_coordenador'])->first();
            $coordenador = $this->criarUsuarioDoCoordenador($coordenador, $evento, $validated['email_coordenador'], $user);
            CoordenadorEvento::create(['user_id' => $coordenador->id, 'eventos_id' => $evento->id]);
        }

        $evento->coordenadorId = auth()->user()->id;
        $evento->deletado = false;
        if ($request->eventoPai != null) {
            $evento->coordenadorId = Evento::find($request->eventoPai)->coordenadorId;
            $evento->evento_pai_id = $request->eventoPai;
        }
        $evento->save();
        // Se o evento tem foto
        if ($request->fotoEvento != null) {
            $evento->fotoEvento = $this->uploadFile($request, $evento);
            $evento->save();
        }
        if ($request->fotoEvento_en != null) {
            $evento->fotoEvento_en = $this->uploadFile($request, $evento);
            $evento->save();
        }

        if ($request->icone != null) {
            $evento->icone = $this->uploadIconeFile($request, $evento);
            $evento->save();
        }
        if ($request->icone_en != null) {
            $evento->icone_en = $this->uploadIconeFile($request, $evento);
            $evento->save();
        }

        FormEvento::create([
            'eventoId' => $evento->id,
        ]);
        FormSubmTraba::create([
            'eventoId' => $evento->id,
        ]);

        $subject = 'Evento Criado';
        Mail::to($user->email)->send(new EventoCriado($user, $subject, $evento));

        return redirect()->route('home')->with(['message' => 'Evento criado com sucesso!']);
    }

    public function uploadFile($request, $evento)
    {
        if ($request->hasFile('fotoEvento')) {
            $file = $request->fotoEvento;
            $path = 'eventos/' . $evento->id;
            $nome = $request->file('fotoEvento')->getClientOriginalName();
            Storage::disk('public')->putFileAs($path, $file, $nome);

            return 'eventos/'.$evento->id.'/'.$nome;
        }

        if ($request->hasFile('fotoEvento_en')) {
            $file = $request->fotoEvento_en;
            $path = 'eventos/'.$evento->id;
            $extensao = $request->file('fotoEvento_en')->getClientOriginalExtension();
            $nome = 'banner-en.'.$extensao;
            Storage::disk('public')->putFileAs($path, $file, $nome);

            return 'eventos/' . $evento->id . '/' . $nome;
        }

        return null;
    }

    public function uploadIconeFile($request, $evento)
    {
        if ($request->hasFile('icone')) {
            $file = $request->icone;
            $path = 'eventos/' . $evento->id;
            $extensao = $request->file('icone')->getClientOriginalExtension();
            $nome = 'icone.' . $extensao;
            $image = Image::make($file)->resize(600, 600)->encode();
            Storage::disk('public')->put($path . '/' . $nome, $image);

            return $path.'/'.$nome;
        }

        if ($request->hasFile('icone_en')) {
            $file = $request->icone_en;
            $path = 'eventos/'.$evento->id;
            $extensao= $request->file('icone_en')->getClientOriginalExtension();
            $nome = 'icone-en.'.$extensao;
            $image = Image::make($file)->resize(600, 600)->encode();
            Storage::disk('public')->put($path.'/'.$nome, $image);

            return $path . '/' . $nome;
        }

        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $date = date('Y-m-d');

        $evento = Evento::find($id);
        if (!$evento) {
            return abort(404);
        }
        $encerrada = $evento->eventoInscricoesEncerradas();
        if (auth()->user()) {
            $subeventos = Evento::where('deletado', false)->where('publicado', true)->where('evento_pai_id', $id)->get();
            $hasTrabalho = false;
            $hasTrabalhoCoautor = false;
            $hasFile = false;
            // $trabalhos = Trabalho::where('autorId', Auth::user()->id)->get();
            // $trabalhosCount = Trabalho::where('autorId', Auth::user()->id)->count();
            // $trabalhosId = Trabalho::where('eventoId', $evento->id)->select('id')->get();
            // $trabalhosIdCoautor = Coautor::whereIn('trabalhoId', $trabalhosId)->where('autorId', Auth::user()->id)->select('trabalhoId')->get();
            // $coautorCount = Coautor::whereIn('trabalhoId', $trabalhosId)->where('autorId', Auth::user()->id)->count();
            // $trabalhosCoautor = Trabalho::whereIn('id', $trabalhosIdCoautor)->get();
            $modalidades = Modalidade::where('evento_id', $evento->id)->get();
            $modalidades = $modalidades->sortBy('nome', SORT_NATURAL)->values()->all();
            $atividades = Atividade::where('eventoId', $id)->get();
            $dataInicial = DB::table('atividades')->join('datas_atividades', 'atividades.id', 'datas_atividades.atividade_id')->select('data')->orderBy('data')->where('eventoId', '=', $id)->first();

            $qry = Inscricao::where('user_id', Auth()->user()->id)->where('evento_id', $evento->id);
            $inscricao = $qry->first();
            $isInscrito = $qry->count();

            // if($trabalhosCount != 0){
            //   $hasTrabalho = true;
            //   $hasFile = true;
            // }
            // if($coautorCount != 0){
            //   $hasTrabalhoCoautor = true;
            //   $hasFile = true;
            // }

            $mytime = Carbon::now('America/Recife');
            $etiquetas = FormEvento::where('eventoId', $evento->id)->first();
            $formSubTraba = FormSubmTraba::all();

            if ($dataInicial == null) {
                $dataInicial = '';
            }

            $links = DB::table('links_pagamentos')
                ->join('categoria_participantes', 'links_pagamentos.categoria_id', '=', 'categoria_participantes.id')
                ->select('categoria_participantes.nome', 'links_pagamentos.*', 'categoria_participantes.id as c_id')
                ->where('links_pagamentos.dataInicio', '<=', $mytime)
                ->where('links_pagamentos.dataFim', '>', $mytime)
                ->get();
            // dd($links);
            // dd($evento->categoriasParticipantes()->where('permite_inscricao', true)->get());
            // dd($etiquetas);

            return view('evento.visualizarEvento', compact('evento', 'hasFile', 'mytime', 'etiquetas', 'modalidades', 'formSubTraba', 'atividades', 'dataInicial', 'isInscrito', 'inscricao', 'subeventos', 'encerrada', 'links'));
        } else {
            $subeventos = Evento::where('deletado', false)->where('publicado', true)->where('evento_pai_id', $id)->get();
            $hasTrabalho = false;
            $hasTrabalhoCoautor = false;
            $hasFile = false;
            $trabalhos = null;
            $trabalhosCoautor = null;
            $etiquetas = FormEvento::where('eventoId', $evento->id)->first();
            $formSubTraba = FormSubmTraba::all();
            $atividades = Atividade::where([['eventoId', $id], ['visibilidade_participante', true]])->get();
            $dataInicial = DB::table('atividades')->join('datas_atividades', 'atividades.id', 'datas_atividades.atividade_id')->select('data')->orderBy('data')->where([['eventoId', '=', $id], ['visibilidade_participante', '=', true]])->first();
            $modalidades = Modalidade::where('evento_id', $id)->get();
            $modalidades = $modalidades->sortBy('nome', SORT_NATURAL)->values()->all();
            $mytime = Carbon::now('America/Recife');
            // dd(false);
            $isInscrito = false;
            if ($dataInicial == null) {
                $dataInicial = '';
            }


            return view('evento.visualizarEvento', compact('evento', 'trabalhos', 'trabalhosCoautor', 'hasTrabalho', 'hasTrabalhoCoautor', 'hasFile', 'mytime', 'etiquetas', 'formSubTraba', 'atividades', 'dataInicial', 'modalidades', 'isInscrito', 'subeventos', 'encerrada'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $endereco = Endereco::find($evento->enderecoId);

        return view('evento.editarEvento', ['evento' => $evento, 'endereco' => $endereco]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return RedirectResponse
     *
     * @throws AuthorizationException
     */
    public function update(UpdateEventoRequest $request, $id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $data = $request->all();
        $evento->update($data);

        $validated = $request->validated();
        $user = auth()->user();
        if ($evento->eventoPai()->exists()) {
            $coordenador = User::where('email', $data['email_coordenador'])->first();
            $coordenador = $this->criarUsuarioDoCoordenador($coordenador, $evento, $validated['email_coordenador'], $user);
            if ($evento->coordenadoresEvento()->exists()) {
                CoordenadorEvento::where('eventos_id', $evento->id)->update(['user_id' => $coordenador->id]);
            } else {
                $evento->coordenadoresEvento()->save($coordenador);
            }
        }

        $evento->recolhimento = $request->recolhimento;
        $evento->update();

        $endereco = Endereco::find($evento->enderecoId);
        $evento->enderecoId = $endereco->id;
        $endereco->update($data);

        if ($request->fotoEvento != null) {
            if (Storage::disk('public')->exists($evento->fotoEvento)) {
                Storage::delete('storage/' . $evento->fotoEvento);
            }
            $file = $request->fotoEvento;
            $path = 'eventos/' . $evento->id;
            $nome = $request->file('fotoEvento')->getClientOriginalName();
            Storage::disk('public')->putFileAs($path, $file, $nome);
            $evento->fotoEvento = 'eventos/' . $evento->id . '/' . $nome;
        }

        if ($request->icone != null) {
            if (Storage::disk('public')->exists($evento->icone)) {
                Storage::disk('public')->delete($evento->icone);
            }
            $file = $request->icone;
            $path = 'eventos/' . $evento->id;
            $extensao = $request->file('icone')->getClientOriginalExtension();
            $nome = 'icone.' . $extensao;
            $evento->icone = $path . '/' . $nome;

            $evento->update();

            $image = Image::make($file)->resize(600, 600)->encode();
            Storage::disk('public')->put($path . '/' . $nome, $image);
        }

        if ($request->dataLimiteInscricao != null) {
            $request->validate([
                'dataLimiteInscricao' => ['required', 'date'],
            ]);
            $evento->data_limite_inscricao = $request->dataLimiteInscricao;
        }

        $evento->update();

        return redirect()->route('home')->with(['message' => 'Evento editado com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCriador', $evento);

        $evento->deletado = true;
        $evento->update();

        return redirect()->back()->with(['message' => 'Evento deletado com sucesso']);
    }

    public function detalhes(Request $request)
    {
        $evento = Evento::find($request->eventoId);

        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $users = $evento->usuariosDaComissao;

        $areas = Area::where('eventoId', $evento->id)->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
        $trabalhosId = Trabalho::whereIn('areaId', $areasId)->select('id')->get();
        $revisores = Revisor::where('evento_id', $evento->id)->get();
        $modalidades = Modalidade::where('evento_id', $evento->id)->get();

        $trabalhos = Trabalho::whereIn('areaId', $areasId)->orderBy('id')->get();
        $trabalhosEnviados = Trabalho::whereIn('areaId', $areasId)->count();
        $trabalhosPendentes = Trabalho::whereIn('areaId', $areasId)->where('avaliado', 'processando')->count();
        $trabalhosAvaliados = 0;
        foreach ($trabalhosId as $trabalho) {
            $trabalhosAvaliados += $trabalho->atribuicoes()->where('parecer', '!=', 'processando')->count();
        }

        $numeroRevisores = Revisor::where('evento_id', $evento->id)->count();
        $numeroComissao = count($evento->usuariosDaComissao);

        $revs = Revisor::where('evento_id', $evento->id)->with('user')->get();
        $etiquetas = FormEvento::where('eventoId', $evento->id)->first();
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();

        $criteriosModalidade = [];
        foreach ($modalidades as $indice) {
            $criterios = Criterio::where('modalidadeId', $indice->id)->get();
            for ($i = 0; $i < count($criterios); $i++) {
                if (!in_array($criterios[$i], $criteriosModalidade)) {
                    array_push($criteriosModalidade, $criterios[$i]);
                }
            }
        }

        return view('coordenador.detalhesEvento', [
            'evento' => $evento,
            'areas' => $areas,
            'revisores' => $revisores,
            'revs' => $revs,
            'users' => $users,
            'modalidades' => $modalidades,
            'trabalhos' => $trabalhos,
            'trabalhosEnviados' => $trabalhosEnviados,
            'trabalhosAvaliados' => $trabalhosAvaliados,
            'trabalhosPendentes' => $trabalhosPendentes,
            'numeroRevisores' => $numeroRevisores,
            'numeroComissao' => $numeroComissao,
            'etiquetas' => $etiquetas,
            'etiquetasSubTrab' => $etiquetasSubTrab,
            'criterios' => $criteriosModalidade,
        ]);
    }

    public function numTrabalhos(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $validatedData = $request->validate([
            'eventoId' => ['required', 'integer'],
            'trabalhosPorAutor' => ['required', 'integer'],
            'numCoautor' => ['required', 'integer'],
        ]);

        $evento->numMaxTrabalhos = $request->trabalhosPorAutor;
        $evento->numMaxCoautores = $request->numCoautor;
        $evento->update();

        return redirect()->back()->with(['mensagem' => 'Restrições de submissão salvas com sucesso!']);
    }

    public function setResumo(Request $request)
    {
        $evento = Evento::find($request->eventoId);

        $validatedData = $request->validate([
            'eventoId' => ['required', 'integer'],
            'hasResumo' => ['required', 'string'],
        ]);
        if ($request->hasResumo == 'true') {
            $evento->hasResumo = true;
        } else {
            $evento->hasResumo = false;
        }

        $evento->save();

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function setFotoEvento(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        // dd($request);
        $validatedData = $request->validate([
            'eventoId' => ['required', 'integer'],
            'fotoEvento' => ['required', 'file', 'mimes:png'],
        ]);
        $evento->fotoEvento = $this->uploadFile($request, $evento);
        $evento->save();

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function habilitar($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $evento->publicado = true;
        $evento->update();

        return redirect()->back()->with('mensagem', 'O evento foi exposto ao público.');
    }

    public function desabilitar($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $evento->publicado = false;
        $evento->update();

        return redirect()->back()->with('mensagem', 'O evento foi ocultado ao público.');
    }

    public function downloadFotoEvento($id)
    {
        $evento = Evento::find($id);
        if (Storage::disk('public')->exists($evento->fotoEvento)) {
            return Storage::disk('public')->download($evento->fotoEvento);
        }

        return abort(404);
    }

    public function pdfProgramacao(Request $request, $id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $request->validate([
            'pdf_programacao' => ['file', 'mimetypes:application/pdf'],
        ]);

        $formEvento = FormEvento::where('eventoId', $id)->first();

        if ($evento->pdf_programacao != null) {
            Storage::disk('public')->delete($evento->pdf_programacao);
        }

        if ($request->pdf_programacao != null) {
            $file = $request->pdf_programacao;
            $path = 'eventos/' . $evento->id;
            $nome = '/pdf-programacao.pdf';
            Storage::disk('public')->putFileAs($path, $file, $nome);
            $evento->pdf_programacao = 'eventos/' . $evento->id . $nome;
            $evento->exibir_calendario_programacao = false;
            $evento->save();

            $formEvento->modprogramacao = true;
            $formEvento->update();
        }

        return redirect()->back()->with(['mensagem' => 'PDF salvo com sucesso!']);
    }

    public function pdfAdicional(Request $request, $id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $request->validate([
            'pdf_arquivo' => ['file', 'mimetypes:application/pdf'],
        ]);

        if ($evento->pdf_arquivo != null) {
            Storage::disk('public')->delete($evento->pdf_arquivo);
        }

        if ($request->pdf_arquivo != null) {
            $file = $request->pdf_arquivo;
            $path = 'eventos/' . $evento->id;
            $nome = '/pdf-arquivo.pdf';
            Storage::disk('public')->putFileAs($path, $file, $nome);
            $evento->pdf_arquivo = 'eventos/' . $evento->id . $nome;
            $evento->save();
        }

        return redirect()->back()->with(['mensagem' => 'PDF salvo com sucesso!']);
    }

    public function buscaLivre()
    {
        return view('evento.busca_eventos');
    }

    public function buscaLivreAjax(Request $request)
    {
        $query = Evento::where('publicado', true)->where('deletado', false)->with('endereco');
        $nome = strtolower($request->nome);
        $tipo = $request->tipo;
        $data_inicio = $request->data_inicio;
        $data_fim = $request->data_fim;
        if ($nome != null) $query = $query->whereRaw('LOWER(nome) like ?', ['%'.$nome.'%']);
        if ($tipo != null) $query = $query->where('tipo', $tipo);
        if ($data_inicio != null) $query = $query->where('dataInicio', $data_inicio);
        if ($data_fim != null) $query = $query->where('dataFim', $data_fim);

        return response()->json($query->get());
    }

    public function avisoCorrecao(Evento $evento, Request $request)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $request->validate([
            'trabalhosSelecionados' => 'array|min:1|required',
        ]);

        $trabalhos = Trabalho::whereIn('id', $request['trabalhosSelecionados'])->get();

        foreach ($trabalhos as $trabalho) {
            Mail::to($trabalho->autor)
                ->cc($trabalho->coautors()->with('user')->get()->map(fn($coautor) => $coautor->user))
                ->send(new AvisoPeriodoCorrecao($trabalho->autor, $trabalho));
        }

        return redirect()->back()->with('success', 'Avisos enviados');
    }

    /**
     * Cria um usuário para o coordenador, caso o coordenador não tenha um usuário
     *
     * @return mixed
     */
    private function criarUsuarioDoCoordenador($coordenador, $evento, $email_coordenador, $user)
    {
        if ($coordenador == null) {
            $passwordTemporario = Str::random(8);
            $coord = User::find($evento->coordenadorId);
            Mail::to($email_coordenador)->send(new EmailParaUsuarioNaoCadastrado($user->name, '  ', 'Coordenador do evento', $evento->nome, $passwordTemporario, $email_coordenador, $coord));
            $coordenador = User::create([
                'email' => $email_coordenador,
                'password' => bcrypt($passwordTemporario),
                'usuarioTemp' => true,
            ]);
        }

        return $coordenador;
    }

    public function eventosPassados()
    {

        $eventosPassados = Evento::where([['publicado', '=', true], ['deletado', '=', false], ['dataFim', '<', today()]])->whereNull('evento_pai_id')->get()->sortDesc();

        return view('coordenador.evento.eventosPassados', compact('eventosPassados'));
    }

    public function eventosProximos()
    {

        $proximosEventos = Evento::where([['publicado', '=', true], ['deletado', '=', false], ['dataFim', '>=', today()]])->whereNull('evento_pai_id')->get();

        return view('coordenador.evento.eventosProximos', compact('proximosEventos'));
    }
}
