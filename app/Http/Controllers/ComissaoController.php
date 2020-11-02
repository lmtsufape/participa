<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ComissaoEvento;
use App\Evento;
use App\Area;
use App\Revisor;
use App\Trabalho;
use App\Atribuicao;
use App\FormEvento;
use App\FormSubmTraba;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ComissaoController extends Controller
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

    public function informacoes(Request $request) {
        $evento = Evento::find($request->eventoId);

        $areas = Area::where('eventoId', $evento->id)->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
        $trabalhosId = Trabalho::whereIn('areaId', $areasId)->select('id')->get();
        $revisores = Revisor::where('evento_id', $evento->id)->get();
        $numeroRevisores = count($revisores);
        $trabalhosEnviados = Trabalho::whereIn('areaId', $areasId)->count();
        $trabalhosPendentes = Trabalho::whereIn('areaId', $areasId)->where('avaliado', 'processando')->count();
        $trabalhosAvaliados = 0;
        foreach ($trabalhosId as $trabalho) {
          $trabalhosAvaliados += $trabalho->atribuicoes()->where('parecer', '!=', 'processando')->count();
        }
        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();
        $numeroComissao = count($evento->usuariosDaComissao);



        return view('coordenador.informacoes', [
                                                    'evento'                  => $evento,
                                                    'trabalhosEnviados'       => $trabalhosEnviados,
                                                    'trabalhosAvaliados'      => $trabalhosAvaliados,
                                                    'trabalhosPendentes'      => $trabalhosPendentes,
                                                    'numeroRevisores'         => $numeroRevisores,
                                                    'numeroComissao'          => $numeroComissao,

                                                  ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationData = $request->validate([
            'emailMembroComissao' => 'required|string|email',
            // 'especProfissional'=>'required|string',
        ]);

        $user = User::where('email',$request->input('emailMembroComissao'))->first();
        $evento = Evento::find($request->eventoId);
        if($user == null){
          $passwordTemporario = Str::random(8);
          Mail::to($request->emailMembroComissao)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Comissao', $evento->nome, $passwordTemporario));
          $user = User::create([
            'email' => $request->emailMembroComissao,
            'password' => bcrypt($passwordTemporario),
            'usuarioTemp' => true,
          ]);
        } else {            
            $usuarioDaComissa = $evento->usuariosDaComissao()->where('user_id', $user->id)->first();
            if ($usuarioDaComissa != null) {
                return redirect()->back()->withErrors(['cadastrarComissao' => 'Esse usuário já é membro da comissão.'])->withInput($validationData);
            }
        }

        // dd($user->id);
        $evento->usuariosDaComissao()->save($user);

        // $comissaoEventos->eventosId = $request->input('eventoId');
        // $comissaoEventos->userId = $user->id;
        // // $comissaoEventos->especProfissional = $request->input('especProfissional');
        // $comissaoEventos->save();


        $evento = Evento::find($request->input('eventoId'));
        $areas = Area::where('eventoId', $evento->id)->get();
        $revisores = $evento->revisores;
        $users = $evento->usuariosDaComissao;

        return redirect()->back()->with(['mensagem' => 'Membro da comissão cadastrado com sucesso!']);
    }


    public function coordenadorComissao(Request $request){



        $evento = Evento::find($request->input('eventoId'));
        $evento->coord_comissao_cientifica_id = $request->input('coordComissaoId');
        $evento->save();

        $areas = Area::where('eventoId', $evento->id)->get();
        $revisores = $evento->revisores;
        // dd($ComissaoEventos);
        $users = $evento->usuariosDaComissao;
        // return view('coordenador.detalhesEvento', [
        //                                                 'evento'    => $evento,
        //                                                 'areas'     => $areas,
        //                                                 'revisores' => $revisores,
        //                                                 'users'     => $users,
        //                                             ]);
        return redirect()->back()->with(['mensagem' => 'Coordenador da comissão científica salvo com sucesso!']);
    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $evento = Evento::find($request->evento_id);
        
        $evento->usuariosDaComissao()->detach($id);

        return redirect()->back()->with(['mensagem' => 'Membro da comissão removido com sucesso!']);
    }
}
