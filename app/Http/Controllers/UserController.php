<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Endereco;

class UserController extends Controller
{
    //
    function perfil(){
        $user = User::find(Auth::user()->id);
        $end = Endereco::find($user->id);
        return view('user.perfilUser',['user'=>$user,'end'=>$end]);
    }
    function editarPerfil(Request $request){
        // dd($request->name);

        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required',
            'celular' => 'required|integer',
            'instituicao' => 'required|string| max:255',
            'especProfissional' => 'nullable|string',
            'rua' => 'required|string|max:255',
            'numero' => 'required|integer',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string',
            'cep' => 'required|integer',
        ]);

        
        // User
        $user = User::find($request->id);
        $user->name = $request->input('name');
        $user->cpf = $request->input('cpf');
        $user->celular = $request->input('celular');
        $user->instituicao = $request->input('instituicao');
        $user->especProfissional = $request->input('especProfissional');
        $user->save();
        
        // endereço
        $end = Endereco::find($user->enderecoId);
        $end->rua = $request->input('rua');
        $end->numero = $request->input('numero');
        $end->bairro = $request->input('bairro');
        $end->cidade = $request->input('cidade');
        $end->uf = $request->input('uf');
        $end->cep = $request->input('cep');
        
        $end->save();
        // dd([$user,$end]);        
        return view('home');

        

        // return view('index');
    }
}
