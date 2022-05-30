<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Models\Submissao\Evento;
use App\Models\Users\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use App\Http\Controllers\Controller;
use App\Models\Users\CoordComissaoOrganizadora;

class ComissaoOrganizadoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $usuariosDaComissao = $evento->usuariosDaComissaoOrganizadora;
        return view('coordenador.comissaoOrganizadora.listarComissao', ['evento' => $evento,
                                                                        'users' => $usuariosDaComissao]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        return view('coordenador.comissaoOrganizadora.cadastrarComissao', ['evento' => $evento]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $validationData = $request->validate([
            'emailMembroComissao' => 'required|email',
        ]);

        $user = User::where('email', $request->emailMembroComissao)->first();
        // dd($user);
        if ($user == null) {
            $passwordTemporario = Str::random(8);
            $coord = User::find($evento->coordenadorId);
            Mail::to($request->emailMembroComissao)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Comissao Organizadora', $evento->nome, $passwordTemporario, ' ', $coord));
            $user = User::create([
                'email' => $request->emailMembroComissao,
                'password' => bcrypt($passwordTemporario),
                'usuarioTemp' => true,
            ]);
        } else {
            $usuarioDaComissao = $evento->usuariosDaComissaoOrganizadora()->where('user_id', $user->id)->first();
            if ($usuarioDaComissao != null) {
                return redirect()->back()->withErrors(['cadastrarComissao' => 'Esse usuário já é membro da comissão organizadora.'])->withInput($validationData);
            }
        }

        $evento->usuariosDaComissaoOrganizadora()->save($user);

        return redirect()->back()->with(['mensagem' => 'Membro da comissão organizadora cadastrado com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        CoordComissaoOrganizadora::where([['user_id', '=', $id], ['eventos_id', '=', $evento->id]])->delete();
        $evento->usuariosDaComissaoOrganizadora()->detach($id);

        return redirect()->back()->with(['mensagem' => 'Membro da comissão organizadora removido com sucesso!']);
    }

    public function definirCoordenador($id) {
        $evento = Evento::find($id);
        $this->authorize('isCoordenador', $evento);

        $usuariosDaComissao = $evento->usuariosDaComissaoOrganizadora;
        return view('coordenador.comissaoOrganizadora.definirCoordComissao', ['evento' => $evento,
                                                                              'users' => $usuariosDaComissao]);
    }

    public function salvarCoordenador(Request $request) {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $validationData = $request->validate([
            'coordComissaoId' => 'required|array',
        ]);
        foreach ($validationData['coordComissaoId'] as $id) {
            CoordComissaoOrganizadora::firstOrCreate(['user_id' => $id, 'eventos_id' => $evento->id]);
        }
        $idsCoordenadores = $evento->coordComissaoOrganizadora->map(function($coord){
            return $coord->id;
        })->all();
        $removidos = array_diff($idsCoordenadores, $validationData['coordComissaoId']);
        CoordComissaoOrganizadora::whereIn('user_id', $removidos)->where('eventos_id', $evento->id)->delete();
        return redirect()->back()->with(['mensagem' => 'Coordenador da comissão organizadora salvo com sucesso!']);
    }
}
