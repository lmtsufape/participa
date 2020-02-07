<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    function perfil(){
        $user = User::find(Auth::user()->id);
        return view('user.perfilUser',$user);
    }
}
