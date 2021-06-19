<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        return view('administrador.index');
    }

    public function editais()
    {
        return view('administrador.index');
    }

    public function areas()
    {
        return view('administrador.index');
    }

    public function users()
    {
        $users = User::doesntHave('administradors')->orderBy('updated_at', 'ASC')->paginate(100);

        return view('administrador.users', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::doesntHave('administradors')->find($id);

        return view('administrador.editUser', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        // dd($request->all());
        $user = User::doesntHave('administradors')->find($id);
        $user->update([
            'name'  => $request->name,
            'email' => $request->email
        ]);

        return redirect()->route('admin.users')->with(['message' => "Atualizado com sucesso!"]);
    }
    public function deleteUser( $id)
    {
        // dd($request->all());
        $user = User::doesntHave('administradors')->find($id);
        $user->delete();

        return redirect()->route('admin.users')->with(['message' => "Deletado com sucesso!"]);
    }
    public function search(Request $request)
    {
        // dd($request->all());
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
