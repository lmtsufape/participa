<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoComissaoRequest;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use App\Models\Submissao\Evento;
use App\Models\Submissao\TipoComissao;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TipoComissaoController extends Controller
{
    public function show(Evento $evento, TipoComissao $comissao) {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $evento = $comissao->evento;
        return view('coordenador.tipocomissao.show', compact('comissao', 'evento'));
    }

    public function create(Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        return view('coordenador.tipocomissao.create', compact('evento'));
    }

    public function store(TipoComissaoRequest $request, Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $validated = $request->validated();
        TipoComissao::create(['nome' => $validated['nome'], 'evento_id' => $evento->id]);
        return redirect()->route('coord.tipocomissao.create', compact('evento'))->with('success', 'Comissão criada com sucesso!');
    }

    public function update(TipoComissaoRequest $request, Evento $evento, TipoComissao $comissao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $validated = $request->validated();
        $comissao->nome = $validated['nome'];
        $comissao->save();
        return redirect()->route('coord.tipocomissao.show', compact('evento', 'comissao'))->with('success', 'Comissão atualizada com sucesso!');
    }

    public function destroy(Evento $evento, TipoComissao $comissao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $comissao->delete();
        return redirect()->route('coord.tipocomissao.create', compact('evento'))->with('success', 'Comissão deletada com sucesso!');
    }

    public function adicionarMembro(Request $request, Evento $evento, TipoComissao $comissao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $data = $request->validate(['email' => 'required|email']);
        $isCoordenador = $request->has('isCoordenador');
        $user = User::where('email', $data['email'])->first();
        if($user == null){
          $passwordTemporario = Str::random(8);
          $coord = User::find($evento->coordenadorId);
          Mail::to($data['email'])->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', "membro da comissão {$comissao->nome}", $evento->nome, $passwordTemporario, ' ', $coord));
          $user = User::create([
            'email' => $data['email'],
            'password' => bcrypt($passwordTemporario),
            'usuarioTemp' => true,
          ]);
        } else {
            $usuarioDaComissa = $comissao->membros()->where('user_id', $user->id)->first();
            if ($usuarioDaComissa != null) {
                return redirect()->back()->withErrors(['email' => 'Esse usuário já é membro da comissão.']);
            }
        }
        $comissao->membros()->save($user, ['isCoordenador' => $isCoordenador]);
        return redirect()->back()->with(['success' => 'Membro da comissão cadastrado com sucesso!']);
    }

    public function removerMembro(Request $request, Evento $evento, TipoComissao $comissao)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $data = $request->validate(['email' => 'required|email']);
        $user = User::where('email', $data['email'])->first();
        $comissao->membros()->detach($user);
        return redirect()->back()->with(['success' => 'Membro da comissão removido com sucesso!']);
    }

    public function editarMembro(Request $request, Evento $evento, TipoComissao $comissao, User $membro)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $data = $request->validate(['email' => 'required|email']);
        $isCoordenador = $request->has('isCoordenador');
        $user = User::where('email', $data['email'])->first();
        if($membro->email != $data['email'])
            $comissao->membros()->detach($user);
        if($user == null){
            $passwordTemporario = Str::random(8);
            $coord = User::find($evento->coordenadorId);
            Mail::to($data['email'])->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', "membro da comissão {$comissao->nome}", $evento->nome, $passwordTemporario, ' ', $coord));
            $user = User::create([
                'email' => $data['email'],
                'password' => bcrypt($passwordTemporario),
                'usuarioTemp' => true,
            ]);
        } else {
            $usuarioDaComissa = $comissao->membros()->where('user_id', $user->id)->first();
            if ($usuarioDaComissa != null) {
                $comissao->membros()->updateExistingPivot($user->id, ['isCoordenador' => $isCoordenador]);
                return redirect()->back()->with(['success' => 'Membro da comissão atualizado com sucesso!']);
            }
        }
        $comissao->membros()->save($user, ['isCoordenador' => $isCoordenador]);
        return redirect()->back()->with(['success' => 'Membro da comissão atualizado com sucesso!']);
    }
}
