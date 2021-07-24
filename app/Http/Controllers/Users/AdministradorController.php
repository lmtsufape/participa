<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\User;
use App\Models\Submissao\Endereco;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Users\Administrador;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin', Administrador::class);
        return view('administrador.index');
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
        //
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
    public function destroy($id)
    {
        $this->authorize('isAdmin', Administrador::class);
        return view('administrador.index');
    }

    public function editais()
    {
        $this->authorize('isAdmin', Administrador::class);
        return view('administrador.index');
    }

    public function areas()
    {
        $this->authorize('isAdmin', Administrador::class);
        return view('administrador.index');
    }

    public function users()
    {
        $this->authorize('isAdmin', Administrador::class);
        $users = User::doesntHave('administradors')->orderBy('updated_at', 'ASC')->paginate(100);

        return view('administrador.users', compact('users'));
    }

    public function editUser($id)
    {
        $this->authorize('isAdmin', Administrador::class);
        $user = User::doesntHave('administradors')->find($id);
        $end = $user->endereco;

        return view('administrador.editUser', ['user'=>$user,'end'=>$end]);
    }

    public function updateUser(Request $request, $id)
    {
        // dd($request->all());
        $this->authorize('isAdmin', Administrador::class);
        $user = User::doesntHave('administradors')->find($id);

        if ($request->passaporte != null &&  $request->cpf != null) {
            $request->merge(['passaporte' => null]);
        }
        if($user->usuarioTemp == true){
            $validator = $request->validate([
                'name' => 'bail|required|string|max:255',
                'cpf'           => ($request->passaporte ==null ? ['bail','required','cpf','unique:users'] : 'nullable'),
                'passaporte'    => ($request->cpf ==null ? 'bail|required|max:10|unique:users' : 'nullable'),
                'celular' => 'required|string|max:16',
                'instituicao' => 'required|string| max:255',
                'especialidade' => 'nullable|string',
                'rua' => 'required|string|max:255',
                'numero' => 'required|string',
                'bairro' => 'required|string|max:255',
                'cidade' => 'required|string|max:255',
                'complemento' => 'nullable|string|max:255',
                'uf' => 'required|string',
                'cep' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
                // 'primeiraArea' => 'required|string',
            ]);

            // criar endereço
            $end = new Endereco();
            $end->rua = $request->input('rua');
            $end->numero = $request->input('numero');
            $end->bairro = $request->input('bairro');
            $end->cidade = $request->input('cidade');
            $end->complemento = $request->input('complemento');
            $end->uf = $request->input('uf');
            $end->cep = $request->input('cep');

            $end->save();

            // Atualizar dados não preenchidos de User

            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
            $user->passaporte = $request->input('passaporte');
            $user->celular = $request->input('celular');
            $user->instituicao = $request->input('instituicao');
            $user->password = bcrypt($request->password);
            if ($request->input('especialidade') != null) {
                $user->especProfissional = $request->input('especialidade');
            }
            $user->usuarioTemp = null;
            $user->enderecoId = $end->id;
            $user->email_verified_at = now();
            $user->save();

            return redirect()->route('admin.users')->with(['message' => "Cadastro completado com sucesso!"]);

        }

        else {
            if ($request->passaporte != null &&  $request->cpf != null) {
                $request->merge(['passaporte' => null]);
            }
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'cpf'           => ($request->passaporte  ==null ? ['bail','required','cpf',Rule::unique('users')->ignore($user->id)] : 'nullable'),
                'passaporte'    => ($request->cpf == null && $request->cpf ==null? ['bail','required','max:10',Rule::unique('users')->ignore($user->id)] : ['nullable']),
                'celular' => 'required|string|max:16',
                'instituicao' => 'required|string| max:255',
                // 'especProfissional' => 'nullable|string',
                'rua' => 'required|string|max:255',
                'numero' => 'required|string',
                'bairro' => 'required|string|max:255',
                'complemento' => 'nullable|string|max:255',
                'cidade' => 'required|string|max:255',
                'uf' => 'required|string',
                'cep' => 'required|string',
            ]);

            // User

            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
            $user->passaporte = $request->input('passaporte');
            $user->celular = $request->input('celular');
            $user->instituicao = $request->input('instituicao');
            if ($request->input('password') != null) {
                $request->validate(['password' => 'string|min:8|confirmed',
                ]);
                $user->password = bcrypt($request->password);
            }
            // $user->especProfissional = $request->input('especProfissional');
            $user->usuarioTemp = null;
            $user->update();

            // endereço
            $end = Endereco::find($user->enderecoId);
            $end->rua = $request->input('rua');
            $end->numero = $request->input('numero');
            $end->bairro = $request->input('bairro');
            $end->cidade = $request->input('cidade');
            $end->complemento = $request->input('complemento');
            $end->uf = $request->input('uf');
            $end->cep = $request->input('cep');

            $end->update();
            // dd([$user,$end]);
            return redirect()->route('admin.users')->with(['message' => "Usuário atualizado com sucesso!"]);

        }


        return redirect()->route('admin.users')->with(['message' => "Atualizado com sucesso!"]);
    }

    public function deleteUser( $id)
    {
        // dd($request->all());
        $this->authorize('isAdmin', Administrador::class);
        $user = User::doesntHave('administradors')->find($id);
        $user->delete();

        return redirect()->route('admin.users')->with(['message' => "Deletado com sucesso!"]);
    }
    public function search(Request $request)
    {
        // dd($request->all());
        $this->authorize('isAdmin', Administrador::class);
        $users = User::doesntHave('administradors')->where('email','ilike', '%'.$request->search.'%' )->paginate(100);
        if($users->count() == 0){
            $users = User::doesntHave('administradors')->where('name','ilike', '%'.$request->search.'%')->paginate(100);

        }
        if($users->count() == 0){
            return view('administrador.users', compact('users'))->with(['message' => "Nenhum Resultado encontrado!"]);

        }
        return view('administrador.users', compact('users'));


    }

}
