<?php

namespace App\Http\Controllers\Submissao;

use App\Exports\AvaliacoesExport;
use App\Exports\InscritosExport;
use App\Exports\TrabalhosExport;
use App\Models\Submissao\Area;
use App\Models\Submissao\Atividade;
use App\Models\Submissao\Evento;
use App\Models\Users\Coautor;
use App\Models\Submissao\Criterio;
use App\Models\Users\Revisor;
use App\Models\Submissao\Atribuicao;
use App\Models\Submissao\Modalidade;
use App\Models\Users\ComissaoEvento;
use App\Models\Users\User;
use App\Models\Submissao\Trabalho;
use App\Models\Submissao\AreaModalidade;
use App\Models\Submissao\FormEvento;
use App\Models\Submissao\FormSubmTraba;
use App\Models\Submissao\RegraSubmis;
use App\Models\Submissao\TemplateSubmis;
use App\Models\Inscricao\Inscricao;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Submissao\Endereco;
use App\Mail\EventoCriado;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Submissao\Form;
use App\Models\Submissao\Opcao;
use App\Models\Submissao\Pergunta;
use App\Models\Submissao\Resposta;
use App\Models\Submissao\Paragrafo;
use Illuminate\Http\File;
use Illuminate\Http\Response;
use PDF;
use PhpParser\Node\Expr\AssignOp\Mod;
use Svg\Gradient\Stop;

// dd($request->all());
class EventoController extends Controller
{
    public function index()
    {
        //
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
        $evento = Evento::find($request->eventoId);

        $this->authorize('isCoordenadorOrCoordenadorDasComissoesOrIsCoordenadorDeOutrasComissoes', $evento);

        $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
        $trabalhosId = Trabalho::whereIn('areaId', $areasId)->select('id')->get();
        $numeroRevisores = Revisor::where('evento_id', $evento->id)->select('user_id')->distinct()->get()->count();
        $trabalhosEnviados = Trabalho::whereIn('areaId', $areasId)->count();
        $trabalhosPendentes = Trabalho::whereIn('areaId', $areasId)->where('avaliado', 'processando')->count();

        $trabalhosAvaliados = 0;
        foreach ($trabalhosId as $trabalho) {
            $trabalhosAvaliados += $trabalho->atribuicoes()->where('parecer', '!=', 'processando')->count();
        }

        $numeroComissao = count($evento->usuariosDaComissao);


        return view('coordenador.informacoes', [
            'evento' => $evento,
            'trabalhosEnviados' => $trabalhosEnviados,
            'trabalhosAvaliados' => $trabalhosAvaliados,
            'trabalhosPendentes' => $trabalhosPendentes,
            'numeroRevisores' => $numeroRevisores,
            'numeroComissao' => $numeroComissao,

        ]);

    }

    public function definirSubmissoes(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();

        return view('coordenador.trabalhos.definirSubmissoes', [
            'evento' => $evento,
        ]);

    }

    public function listarTrabalhos(Request $request, $column = 'titulo', $direction = 'asc', $status = 'rascunho')
    {
        $evento = Evento::find($request->eventoId);
        //$this->authorize('isCoordenadorOrComissaoOrRevisorComAtribuicao', $evento);
        // $users = $evento->usuariosDaComissao;

        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();

        $trabalhos = NULL;

        if ($column == "autor") {
            //Pela logica da implementacao de status, rascunho eh o parametro para encontrar todos os trabalhos diferentes de arquivado
            if ($status == "rascunho") {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == "desc"));
                }


            } else if ($status == "with_revisor") {
                $trabalhos_id = DB::table('trabalhos')->join('atribuicaos', 'atribuicaos.trabalho_id', '=', 'trabalhos.id')
                    ->where('trabalhos.eventoId', $evento->id)
                    ->get('trabalhos.id');

                $trabalhos = Trabalho::whereIn('id', $trabalhos_id->pluck('id'))
                    ->where('status', '!=', 'arquivado')
                    ->get();

                $trabalhos = $trabalhos->groupBy('modalidadeId');
                foreach ($trabalhos as $i => $modalidade) {
                    $modalidade = $modalidade->sortBy(function ($trabalho) {
                        return $trabalho->autor->name;
                    },
                        SORT_REGULAR,
                        $direction == "desc");

                    $trabalhos[$i] = $modalidade;
                }
                $trabalhos = $trabalhos->sortBy(function ($modalidade) {
                    return $modalidade->first()->modalidade->nome;
                });
            } else if ($status == "no_revisor") {
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
                    $modalidade = $modalidade->sortBy(function ($trabalho) {
                        return $trabalho->autor->name;
                    },
                        SORT_REGULAR,
                        $direction == "desc");

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
                        $direction == "desc"));
                }
            }
        } else {
            if ($status == "rascunho") {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->orderBy($column, $direction)->get());
                }

            } else if ($status == "with_revisor") {
                $trabalhos_id = DB::table('trabalhos')->join('atribuicaos', 'atribuicaos.trabalho_id', '=', 'trabalhos.id')
                    ->where('trabalhos.eventoId', $evento->id)
                    ->get('trabalhos.id');

                $trabalhos = Trabalho::whereIn('id', $trabalhos_id->pluck('id'))
                    ->where('status', '!=', 'arquivado')
                    ->get();

                $trabalhos = $trabalhos->groupBy('modalidadeId');

                if ($column == "titulo") {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(function ($trabalho) {
                            return $trabalho->titulo;
                        },
                            SORT_REGULAR,
                            $direction == "desc");
                        $trabalhos[$i] = $modalidade;
                    }
                } else if ($column == "areaId") {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(function ($trabalho) {
                            return $trabalho->area->nome;
                        },
                            SORT_REGULAR,
                            $direction == "desc");
                        $trabalhos[$i] = $modalidade;
                    }
                }

                $trabalhos = $trabalhos->sortBy(function ($modalidade) {
                    return $modalidade->first()->modalidade->nome;
                });

            } else if ($status == "no_revisor") {
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

                if ($column == "titulo") {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(function ($trabalho) {
                            return $trabalho->titulo;
                        },
                            SORT_REGULAR,
                            $direction == "desc");
                        $trabalhos[$i] = $modalidade;
                    }
                } else if ($column == "areaId") {
                    foreach ($trabalhos as $i => $modalidade) {
                        $modalidade = $modalidade->sortBy(function ($trabalho) {
                            return $trabalho->area->nome;
                        },
                            SORT_REGULAR,
                            $direction == "desc");
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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();
        $trabalhos = NULL;
        if ($column == "autor") {
            if ($status == "rascunho") {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == "desc"));
                }
            } else {
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == "desc"));
                }
            }
        } else {
            if ($status == "rascunho") {
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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $modalidade = Modalidade::find($request->modalidadeId);
        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->orderBy('nome')->get();


        $trabalhos = NULL;

        if ($column == "autor") {
            //Pela logica da implementacao de status, rascunho eh o parametro para encontrar todos os trabalhos diferentes de arquivado
            if ($status == "rascunho") {
                $trabalhos = Trabalho::whereIn('areaId', $areasId)->where([['status', '!=', 'arquivado'], ['modalidadeId', $request->modalidadeId]])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->autor->name;
                    },
                    SORT_REGULAR,
                    $direction == "desc");
            } else {
                // Não tem como ordenar os trabalhos por nome do autor automaticamente
                // Já que na tabale a de trabalhos não existe o nome do autor
                $trabalhos = Trabalho::whereIn('areaId', $areasId)->where([['status', '=', $status], ['modalidadeId', $request->modalidadeId]])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->autor->name; // Ordena o pelo valor do nome do autor
                    },
                    SORT_REGULAR, // Usa o método padrão de ordenação
                    $direction == "desc"); // Se true, então ordena decrescente
            }
        } else {
            if ($status == "rascunho") {
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

        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $revisores = User::join('revisors', 'users.id', '=', 'revisors.user_id')->where('revisors.evento_id', '=', $evento->id)->selectRaw('DISTINCT users.*')->get()->sortBy(
            function ($revisor) {
                return $revisor->name;
            },
            SORT_REGULAR);
        // $revs = Revisor::where('evento_id', $evento->id)->with('user')->get();
        $contadores = DB::table('revisors AS r')
            ->select(array('r.user_id', 't.avaliado', DB::raw('COUNT(r.user_id) AS count')))
            ->join('atribuicaos AS a', 'r.id', 'a.revisor_id')
            ->join('trabalhos AS t', 't.id', 'a.trabalho_id')
            ->where('r.evento_id', $evento->id)
            ->groupBy('r.user_id', 't.avaliado')
            ->get();
        $areas = Area::where('eventoId', $evento->id)->get();
        $modalidades = Modalidade::where('evento_id', $evento->id)->get();

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

        $this->authorize('isCoordenador', $evento);
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
        return (new InscritosExport($evento))->download($evento->nome . '.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportTrabalhos(Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $trabalhos = Trabalho::where('eventoId', $evento->id)
            ->get()->map(function ($trabalho) {
                return [
                    $trabalho->area->nome,
                    $trabalho->modalidade->nome,
                    $trabalho->titulo,
                    $trabalho->autor->name,
                    $trabalho->autor->email,
                    $trabalho->autor->celular,
                    $this->coautoresToString($trabalho, 'nome'),
                    $this->coautoresToString($trabalho, 'email'),
                    $this->coautoresToString($trabalho, 'celular'),
                ];
            })->collect();
        return (new TrabalhosExport($trabalhos))->download($evento->nome . '- Trabalhos.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function coautoresToString(Trabalho $trabalho, $campo)
    {
        $stringRetorno = "";

        if ($campo == 'nome') {
            foreach ($trabalho->coautors as $coautor) {
                if ($coautor->user->id != $trabalho->autorId) {
                    $stringRetorno .= $coautor->user->name . ", ";
                }
            }
        } elseif ($campo == 'email') {
            foreach ($trabalho->coautors as $coautor) {
                if ($coautor->user->id != $trabalho->autorId) {
                    $stringRetorno .= $coautor->user->email . ", ";
                }
            }
        } elseif ($campo == 'celular') {
            foreach ($trabalho->coautors as $coautor) {
                if ($coautor->user->id != $trabalho->autorId) {
                    $stringRetorno .= $coautor->user->celular . ", ";
                }
            }
        }

        return substr($stringRetorno, 0, strlen($stringRetorno) - 2);
    }

    public function exportAvaliacoes(Evento $evento, Modalidade $modalidade, Form $form)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
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
        return (new AvaliacoesExport($trabalhosCollect, $this->makeHeadingsExportAvaliacoes($form)))->download($evento->nome . ' - Avaliacões - ' . $modalidade->nome . ' - ' . $form->titulo . '.csv', \Maatwebsite\Excel\Excel::CSV, [
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
            array_push($retorno, "Sem avaliador");
        }
        array_push($retorno, $trabalho->titulo);

        $respostas = collect();
        if ($revisor != null) {
            foreach ($form->perguntas as $pergunta) {
                $respostas->push($pergunta->respostas->where('trabalho_id', $trabalho->id)->where('revisor_id', $revisor->id)->first());
            }
        }

        $vazio = False;

        foreach ($form->perguntas as $index => $pergunta) {
            $achou = False;
            if ($pergunta->respostas->first()->opcoes->count()) {
                foreach ($pergunta->respostas->first()->opcoes as $opcao) {
                    if (count($respostas) > $index && $respostas[$index] != null && $respostas[$index]->opcoes != null && $respostas[$index]->opcoes->pluck('titulo')->contains($opcao->titulo)) {
                        array_push($retorno, $respostas[$index]->opcoes[0]->titulo);
                        $achou = True;
                    }
                }
            } elseif ($pergunta->respostas->first()->paragrafo != null) {
                foreach ($pergunta->respostas as $resposta) {
                    if ($resposta->revisor != null && $resposta->trabalho != null && $resposta->paragrafo != null) {
                        if ($revisor != null) {
                            if ($resposta->revisor->user_id == $revisor->user->id && $resposta->trabalho->id == $trabalho->id) {
                                array_push($retorno, $resposta->paragrafo->resposta);
                                $achou = True;
                            }
                        } else {
                            array_push($retorno, "Sem resposta");
                        }
                    }
                }
                if ($pergunta->respostas->first() == null) {
                    array_push($retorno, "Sem resposta");
                }
            }
            $vazio = $vazio || $achou;
        }
        if ($vazio == False) {
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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

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
                        SORT_REGULAR));
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
                        $direction == "asc"));
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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $modalidades = Modalidade::where('evento_id', $evento->id)->get();

        return view('coordenador.modalidade.cadastrarCriterio', [
            'evento' => $evento,
            'modalidades' => $modalidades,

        ]);

    }

    public function listarCriterios(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();
        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();

        // Criterios por modalidades
        $criteriosModalidade = [];
        foreach ($modalidades as $indice) {
            $criterios = Criterio::where("modalidadeId", $indice->id)->orderBy('nome')->get();
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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();

        return view('coordenador.modalidade.formulario', compact(
            'evento',
            'modalidades'
        ));

    }

    public function atribuirForm(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $modalidade = Modalidade::find($request->modalidade_id);

        return view('coordenador.modalidade.atribuirFormulario', compact('evento', 'modalidade'));

    }

    public function salvarForm(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $modalidade = Modalidade::find($request->modalidade_id);
        $data = $request->all();
        // dd($data);
        $form = $modalidade->forms()->create([
            'titulo' => $data['tituloForm']
        ]);

        $radioCont = 0;
        $checkRqst = $request->checkboxVisibilidade;
        $i = 0;
        $checks = [];
        while ($i < count($checkRqst)) {
            if ($i + 1 < count($checkRqst)) {
                if ($checkRqst[$i] == "false" && $checkRqst[$i + 1] == "false") {
                    array_push($checks, false);
                    $i++;
                } else {
                    array_push($checks, true);
                    $i += 2;
                }
            } else {
                array_push($checks, false);
                break;
            }
        }
        foreach ($data['pergunta'] as $index => $value) {
            $pergunta = $form->perguntas()->create([
                'pergunta' => $value,
                'visibilidade' => $checks[$index],
            ]);

            $resposta = new Resposta();
            $resposta->pergunta_id = $pergunta->id;
            $resposta->save();

            if ($data['tipo'][$index] == 'paragrafo') {
                $paragrafo = new Paragrafo();
                $resposta->paragrafo()->save($paragrafo);

            } else if ($data['tipo'][$index] == 'radio') {
                $keys = array_keys($data['tituloRadio']);
                foreach ($data['tituloRadio'][$keys[$radioCont++]] as $titulo) {
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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $data = $request->all();
        //dd($data);
        $perguntasMantidas = [];


        if(!isset($request->pergunta_id))
        {
            return redirect()->back()->withErrors(['excluirFormulario' => 'Não é possivel apagar todas as perguntas!!']);
        }

        if (isset($request->pergunta_id)) {
            foreach ($request->pergunta_id as $key => $pergunta_id) {
                $pergunta = Pergunta::find($pergunta_id);
                $pergunta->pergunta = $request->pergunta[$key];
                if (isset($data['checkboxVisibilidade_' . $pergunta->id])) {
                    $pergunta->visibilidade = true;
                } else {
                    $pergunta->visibilidade = false;
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
                    } else if ($data['tipo'][$i] == 'checkbox') {
                        $listResposta = array_shift($data['tituloCheckoxMarc']);
                        foreach (array_shift($data['tituloCheckox']) as $key => $titulo) {
                            $resposta->opcoes()->create([
                                'titulo' => $titulo,
                                'tipo' => 'radio',
                                'check' => $listResposta[$key]
                            ]);
                        }
                    }

                }
            }
        }

        $form->titulo = $data['titulo' . $form->id];
        $form->update();

        return redirect()->back()->with(['mensagem' => 'Formulário editado com sucesso!']);
    }

    public function destroyForm($id)
    {
        $form = Form::find($id);
        $evento = $form->modalidade->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $pdf = PDF::loadView('coordenador.modalidade.respostasPdf', ['modalidade' => $modalidade])->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream("respostas-{$modalidade->nome}.pdf");
    }

    public function resumosToPdf(Evento $evento, Request $request, $column = 'titulo', $direction = 'asc', $status = 'rascunho')
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();
        $trabalhos = NULL;

        if ($column == "autor") {
            //Pela logica da implementacao de status, rascunho eh o parametro para encontrar todos os trabalhos diferentes de arquivado
            if ($status == "rascunho") {
                //dd($modalidadesId);
                $trabalhos = collect();
                foreach ($modalidades as $modalidade) {
                    $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '!=', 'arquivado']])->get()->sortBy(
                        function ($trabalho) {
                            return $trabalho->autor->name;
                        },
                        SORT_REGULAR,
                        $direction == "desc"));
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
                        $direction == "desc"));
                }
                dd($trabalhos);
            }
        } else {
            if ($status == "rascunho") {
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

        $pdf = PDF::loadView('coordenador.trabalhos.resumosPdf', ['trabalhosPorModalidade' => $trabalhos, 'evento' => $evento])->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download("resumos - {$evento->nome}.pdf");
    }

    public function listarRespostasTrabalhos(Request $request, $column = 'titulo', $direction = 'asc', $status = 'rascunho')
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        // $users = $evento->usuariosDaComissao;

        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->orderBy('nome')->get();


        $trabalhos = NULL;

        if ($column == "autor") {
            if ($status == "rascunho") {
                $trabalhos = Trabalho::where([['modalidadeId', $request->modalidadeId], ['status', '!=', 'arquivado']])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->autor->name;
                    },
                    SORT_REGULAR,
                    $direction == "desc");
            } else {
                $trabalhos = Trabalho::where([['modalidadeId', $request->modalidadeId], ['status', '=', 'arquivado']])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->autor->name;
                    },
                    SORT_REGULAR,
                    $direction == "desc");
            }
        } else {
            if ($status == "rascunho") {
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
        $modalidade = Modalidade::find($request->modalidadeId);
        $trabalho = Trabalho::find($request->trabalhoId);
        $revisor = Revisor::find($request->revisorId);
        $revisorUser = User::find($revisor->user_id);
        $respostas = collect();
        foreach ($modalidade->forms as $form) {
            foreach ($form->perguntas as $pergunta) {
                $respostas->push($pergunta->respostas->where('trabalho_id', $trabalho->id)->where('revisor_id', $revisor->id)->first());
            }
        }
        //   dd($respostas, $trabalho->id, $revisor->id, $modalidade->id);
        return view('coordenador.trabalhos.visualizarRespostaFormulario', compact('evento', 'modalidade', 'trabalho', 'revisorUser', 'revisor', 'respostas'));

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
            $criterios = Criterio::where("modalidadeId", $indice->id)->get();
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
            $criterios = Criterio::where("modalidadeId", $indice->id)->get();
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
        return view('evento.criarEvento');
    }

    public function createSubEvento($id)
    {
        $eventoPai = Evento::find($id);
        $this->authorize('isCoordenador', $eventoPai);
        return view('evento.criarEvento', compact('eventoPai'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventoRequest $request)
    {
        $data = $request->all();
        $endereco = Endereco::create($data);
        $data['enderecoId'] = $endereco->id;
        $data['coordenadorId'] = Auth::user()->id;
        $evento = Evento::create($data);

        $evento->coordenadorId = auth()->user()->id;
        $evento->deletado = false;
        if ($request->eventoPai != null) {
            $evento->evento_pai_id = $request->eventoPai;
        }
        $evento->save();
        // Se o evento tem foto
        if ($request->fotoEvento != null) {
            $evento->fotoEvento = $this->uploadFile($request, $evento);
            $evento->save();
        }

        if ($request->icone != null) {
            $evento->icone = $this->uploadIconeFile($request, $evento);
            $evento->save();
        }

        $user = Auth::user();
        $subject = "Evento Criado";
        Mail::to($user->email)->send(new EventoCriado($user, $subject, $evento));

        $FormEvento = FormEvento::create([
            'eventoId' => $evento->id,
        ]);
        $FormSubmTraba = FormSubmTraba::create([
            'eventoId' => $evento->id,
        ]);

        return redirect()->route('home')->with(['message' => "Evento criado com sucesso!"]);
    }

    public function uploadFile($request, $evento)
    {
        if ($request->hasFile('fotoEvento')) {
            $file = $request->fotoEvento;
            $path = 'public/eventos/' . $evento->id;
            $nome = $request->file('fotoEvento')->getClientOriginalName();
            Storage::putFileAs($path, $file, $nome);
            return 'eventos/' . $evento->id . '/' . $nome;
        }
        return null;
    }

    public function uploadIconeFile($request, $evento)
    {
        if ($request->hasFile('icone')) {
            $file = $request->icone;
            $path = 'public/eventos/' . $evento->id;
            $nome = $request->file('icone')->getClientOriginalName();
            Storage::putFileAs($path, $file, $nome);
            return 'eventos/' . $evento->id . '/' . $nome;
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Evento $evento
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evento = Evento::find($id);
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

            $isInscrito = Inscricao::where('user_id', Auth()->user()->id)->where('evento_id', $evento->id)->count();


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
                $dataInicial = "";
            }
            return view('evento.visualizarEvento', compact('evento', 'hasFile', 'mytime', 'etiquetas', 'modalidades', 'formSubTraba', 'atividades', 'dataInicial', 'isInscrito', 'subeventos'));
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
                $dataInicial = "";
            }
            return view('evento.visualizarEvento', compact('evento', 'trabalhos', 'trabalhosCoautor', 'hasTrabalho', 'hasTrabalhoCoautor', 'hasFile', 'mytime', 'etiquetas', 'formSubTraba', 'atividades', 'dataInicial', 'modalidades', 'isInscrito', 'subeventos'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Evento $evento
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
     * @param \Illuminate\Http\Request $request
     * @param \App\Evento $evento
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventoRequest $request, $id)
    {
        // $mytime = Carbon::now('America/Recife');
        // $this->authorize('isCoordenador', $evento);
        Log::info("Final");
        $data = $request->all();
        $evento = Evento::find($id);
        $evento->update($data);

        $evento->recolhimento = $request->recolhimento;
        $evento->update();

        $endereco = Endereco::find($evento->enderecoId);
        $evento->enderecoId = $endereco->id;
        $endereco->update($data);

        if ($request->fotoEvento != null) {
            if (Storage::disk()->exists('public/' . $evento->fotoEvento)) {
                Storage::delete('storage/' . $evento->fotoEvento);
            }
            $file = $request->fotoEvento;
            $path = 'public/eventos/' . $evento->id;
            $nome = $request->file('fotoEvento')->getClientOriginalName();
            Storage::putFileAs($path, $file, $nome);
            $evento->fotoEvento = 'eventos/' . $evento->id . '/' . $nome;
        }

        if ($request->icone != null) {
            if (Storage::disk()->exists('public/' . $evento->icone)) {
                Storage::delete('storage/' . $evento->icone);
            }
            $file = $request->icone;
            $path = 'public/eventos/' . $evento->id;
            $nome = $request->file('icone')->getClientOriginalName();
            Storage::putFileAs($path, $file, $nome);
            $evento->icone = 'eventos/' . $evento->id . '/' . $nome;
        }

        $evento->update();


        return redirect()->route('home')->with(['message' => "Evento editado com sucesso!"]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Evento $evento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenador', $evento);

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
            $criterios = Criterio::where("modalidadeId", $indice->id)->get();
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
            'numCoautor' => ['required', 'integer']
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
            'hasResumo' => ['required', 'string']
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
            'fotoEvento' => ['required', 'file', 'mimes:png']
        ]);
        $evento->fotoEvento = $this->uploadFile($request, $evento);
        $evento->save();
        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function habilitar($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $evento->publicado = true;
        $evento->update();
        return redirect()->back()->with('mensagem', 'O evento foi exposto ao público.');
    }

    public function desabilitar($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $evento->publicado = false;
        $evento->update();
        return redirect()->back()->with('mensagem', 'O evento foi ocultado ao público.');
    }

    public function downloadFotoEvento($id)
    {
        $evento = Evento::find($id);
        if (Storage::disk()->exists('public/' . $evento->fotoEvento)) {
            return Storage::download('public/' . $evento->fotoEvento);
        }
        return abort(404);
    }

    public function pdfProgramacao(Request $request, $id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $request->validate([
            'pdf_programacao' => ['file', 'mimetypes:application/pdf']
        ]);

        $formEvento = FormEvento::where('eventoId', $id)->first();

        if ($evento->pdf_programacao != null) {
            Storage::delete('public/' . $evento->pdf_programacao);
        }

        if ($request->pdf_programacao != null) {
            $file = $request->pdf_programacao;
            $path = 'public/eventos/' . $evento->id;
            $nome = '/pdf-programacao.pdf';
            Storage::putFileAs($path, $file, $nome);
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
            'pdf_arquivo' => ['file', 'mimetypes:application/pdf']
        ]);

        if ($evento->pdf_arquivo != null) {
            Storage::delete('public/' . $evento->pdf_arquivo);
        }

        if ($request->pdf_arquivo != null) {
            $file = $request->pdf_arquivo;
            $path = 'public/eventos/' . $evento->id;
            $nome = '/pdf-arquivo.pdf';
            Storage::putFileAs($path, $file, $nome);
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
        $eventos = null;
        switch ($request->tipo_busca) {
            case "nome":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.nome", "ilike", "%" . $request->nome . "%"], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "tipo":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.tipo", "=", $request->tipo], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "data_inicio":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.dataInicio", "=", $request->data_inicio], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "data_fim":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.dataFim", "=", $request->data_fim], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "nome_tipo":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.nome", "ilike", "%" . $request->nome . "%"], ["eventos.tipo", "=", $request->tipo], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "nome_data_inicio":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.nome", "ilike", "%" . $request->nome . "%"], ["eventos.dataInicio", "=", $request->data_inicio], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "nome_data_fim":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.nome", "ilike", "%" . $request->nome . "%"], ["eventos.dataFim", "=", $request->data_fim], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "tipo_data_inicio":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.tipo", "=", $request->tipo], ["eventos.dataInicio", "=", $request->data_inicio], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "tipo_data_fim":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.tipo", "=", $request->tipo], ["eventos.dataFim", "=", $request->data_fim], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "nome_datas":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.nome", "ilike", "%" . $request->nome . "%"], ["eventos.dataInicio", "=", $request->data_inicio], ["eventos.dataFim", "=", $request->data_fim], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "tipo_datas":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.tipo", "=", $request->tipo], ["eventos.dataInicio", "=", $request->data_inicio], ["eventos.dataFim", "=", $request->data_fim], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "datas":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.dataInicio", "=", $request->data_inicio], ["eventos.dataFim", "=", $request->data_fim], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            case "todos":
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where([["eventos.nome", "ilike", "%" . $request->nome . "%"], ["eventos.tipo", "=", $request->tipo], ["eventos.dataInicio", "=", $request->data_inicio], ["eventos.dataFim", "=", $request->data_fim], ['eventos.publicado', '=', true], ['eventos.deletado', '=', false]])->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
            default:
                $eventos = Evento::join('enderecos', 'enderecos.id', '=', 'eventos.enderecoId')->where("eventos.nome", "ilike", "%" . $request->nome . "%")->select('eventos.id as id_evento', 'eventos.*', 'enderecos.*')->get();
                break;
        }
        return response()->json($eventos);
    }
}
