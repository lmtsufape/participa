<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\AtribuicaoRevisorRequest;
use App\Mail\EmailConviteRevisor;
use App\Mail\EmailLembrete;
use App\Models\Submissao\Area;
use App\Models\Submissao\Atribuicao;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Trabalho;
use App\Models\Users\Revisor;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AtribuicaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'revisorId'      => ['required', 'integer'],
            'trabalhoId'     => ['required', 'integer'],
        ]);

        $trabalho = Trabalho::find($request->trabalhoId);
        $evento = $trabalho->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $revisor = Revisor::find($request->revisorId);

        $revisor->trabalhosAtribuidos()->attach($trabalho->id, ['confirmacao' => false, 'parecer' => 'processando']);

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    public function distribuicaoAutomatica(Request $request)
    {
        $validatedData = $request->validate([
            'eventoId' => ['required', 'integer'],
        ]);

        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $areas = Area::where('eventoId', $evento->id)->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
        $revisores = Revisor::where('eventoId', $evento->id)->get();
        $trabalhos = Trabalho::whereIn('areaId', $areasId)->get();

        foreach ($areas as $area) {
            $trabalhosArea = Trabalho::where('areaId', $area->id)->get();
            $revisoresArea = Revisor::where('areaId', $area->id)->get();
            $numRevisores = count($revisoresArea);
            $i = 0;
            foreach ($trabalhosArea as $trabalho) {
                $revisoresArea[$i]->trabalhosAtribuidos()->attach($trabalho->id, ['confirmacao' => false, 'parecer' => 'processando']);
                $i++;
                if ($i == $numRevisores) {
                    $i = 0;
                }
            }
        }

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function distribuicaoPorArea(Request $request)
    {
        $validatedData = $request->validate([
            'eventoId'                     => ['required', 'integer'],
            'área'                       => ['required', 'integer', 'min:1'],
            'numeroDeRevisoresPorTrabalho' => ['required', 'integer'],
        ]);

        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $evento = Evento::find($request->eventoId);
        $area = Area::find($request->input('área'));
        $revisores = Revisor::where('areaId', $area->id)->get();
        $trabalhos = Trabalho::where('areaId', $area->id)->get();
        $trabalhosArea = Trabalho::where('areaId', $area->id)->get();
        $revisoresArea = Revisor::where('areaId', $area->id)->get();
        $numRevisores = count($revisores);
        $i = 0;
        foreach ($trabalhosArea as $trabalho) {
            for ($j = 0; $j < $request->numeroDeRevisoresPorTrabalho; $j++) {
                //checar se ja existe atribuicao para esse revisor se sim entao vai pro proximo
                $atribuicao = $revisoresArea[$i]->trabalhosAtribuidos()->where('trabalho_id', $trabalho->id)->get();
                if ($atribuicao != null && count($atribuicao) > 0) {
                    $i++;
                    if ($i == $numRevisores) {
                        $i = 0;
                    }
                    continue;
                }
                // atribui para um revisor
                $revisoresArea[$i]->trabalhosAtribuidos()->attach($trabalho->id, ['confirmacao' => false, 'parecer' => 'processando']);
                $aux = Revisor::find($revisoresArea[$i]->id);
                $aux->correcoesEmAndamento = $aux->correcoesEmAndamento + 1;
                $aux->save();

                $trabalho = Trabalho::find($trabalho->id);
                $trabalho->avaliado = 'processando';
                $trabalho->save();

                $i++;
                if ($i == $numRevisores) {
                    $i = 0;
                }
            }
        }

        return redirect()->back()->with(['mensagem' => 'Trabalhos da área '.$area->nome.' distribuidos!']);
    }

    public function distribuicaoManual(Request $request)
    {
        $validatedData = $request->validate([
            'eventoId'  => ['required', 'integer'],
            'trabalhoId'=> ['required', 'integer'],
            'revisorId' => ['required', 'integer'],
        ]);

        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $trabalho = Trabalho::find($request->trabalhoId);
        $trabalho->avaliado = 'processando';
        $trabalho->save();

        $revisor = Revisor::find($request->revisorId);

        if ($trabalho->atribuicoes->contains($revisor)) {
            return redirect()->back()->with(['error' => 'Revisor já atribuído ao trabalho.'])->withInput($validatedData);
        }

        if (! is_null($trabalho->coautors->where('autorId', $revisor->user_id)->first()) || $trabalho->autorId == $revisor->user_id) {
            return redirect()->back()->with(['error' => $revisor->user->name.' não pode ser revisor deste trabalho.'])->withInput($validatedData);
        }

        $revisor->trabalhosAtribuidos()->attach($trabalho->id, ['confirmacao' => false, 'parecer' => 'processando']);
        $revisor->correcoesEmAndamento = $revisor->correcoesEmAndamento + 1;
        $revisor->save();

        $subject = 'Sistema Participa - Atribuição como avaliador(a) e/ou parecerista';
        $informacoes = $trabalho->titulo;
        //   Mail::to($revisor->user->email)
        //         ->send(new EmailLembrete($revisor->user, $subject, $informacoes));
        Mail::to($revisor->user->email)
        ->send(new EmailConviteRevisor($revisor->user, $evento, $subject, Auth::user()));

        $mensagem = $trabalho->titulo.' atribuído ao revisor '.$revisor->user->name.' com sucesso!';

        return redirect()->back()->with(['success' => $mensagem])->withInput($validatedData);
    }

    public function deletePorRevisores(Request $request, $id)
    {
        // dd($id);
        $validatedData = $request->validate([
            'eventoId'      => ['required', 'integer'],
            'trabalhoId'   => ['required', 'integer'],
        ]);

        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $revisor = Revisor::find($id);
        $trabalho = Trabalho::find($request->trabalhoId);

        if ($trabalho->avaliado($revisor->user)) {
            return redirect()->back()->with(['error' => 'O revisor já deu início à avaliação do trabalho, ele não pode ser removido.'])->withInput($validatedData);
        }
        $revisor->correcoesEmAndamento -= 1;
        $revisor->update();

        $trabalho->atribuicoes()->detach($id);

        $mensagem = $trabalho->titulo.' foi retirado de '.$revisor->user->name.' com sucesso!';

        return redirect()->back()->with(['success' => $mensagem])->withInput($validatedData);
    }

    public function atribuirCheck(AtribuicaoRevisorRequest $request)
    {
        // $data = $request->all();

        // $evento = Evento::find($request->eventoId);
        // $revisores = Revisor::where('evento_id', $evento->id)->get();
        // $areasId = Trabalho::whereIn('id', $data['id'])->select('areaId')->get();
        // $trabalhos = Trabalho::whereIn('id', $data['id'])->get();
        // $areasTrabalhos = Area::whereIn('id', $areasId)->get();

        // return view('coordenador.trabalhos.distribuirLote',compact(
        //                                                             'evento',
        //                                                             'revisores',
        //                                                             'areasTrabalhos',
        //                                                             'trabalhos',
        //                                                           ) );
    }

    public function atribuirRevisorLote(Request $request)
    {
        $data = $request->all();

        try {
            $max = count($data['trabalho']);
            $revisor = Revisor::find($data['revisor_id']);
            $evento = Evento::find($request->evento_id);

            for ($i = 0; $i < $max; $i++) {
                $trabalho = Trabalho::find($data['trabalho'][$i]);
                $trabalho->avaliado = 'processando';
                $trabalho->save();

                $revisor->trabalhosAtribuidos()->attach($trabalho->id, ['confirmacao' => false, 'parecer' => 'processando']);
                $revisor->correcoesEmAndamento = $revisor->correcoesEmAndamento + 1;
                $revisor->save();
            }

            return response()->json([
                'data' => [
                    'status' => 'ok',
                    'revisor' => $revisor->user->email,
                ],
            ]);
        } catch (\Exception $e) {
            $message = env('APP_DEBUG') ? $e->getMessage() : 'Erro ao processar atribuição!';

            return response()->json([
                'data' => [
                    'status' => false,
                    'message' => $message,
                ],
            ], 401);
        }

        // $subject = "Trabalho atribuido";
      // $informacoes = $trabalho->titulo;
      // Mail::to($revisor->user->email)
      //       ->send(new EmailLembrete($revisor->user, $subject, $informacoes));
    }
}
