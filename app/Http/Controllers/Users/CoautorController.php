<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Users\Coautor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoautorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('coautor.index');
    }

    public function trabalhos()
    {
        // $trabalhos = DB::table('users')
        //                 ->join('coautors', 'users.id', '=', 'coautors.autorId')
        //                 ->join('trabalhos', 'coautors.id', '=', 'orders.user_id')
        //                 ->select('users.*', 'contacts.phone', 'orders.price')
        //                 ->get();
        return view('coautor.index');
    }

    public function listarTrabalhos(){
        $user = Auth::user();
        $comoCoautor = Coautor::where('autorId', $user->id)->first();

        $trabalhos = collect();

        if ($comoCoautor != null) {
            $trabalhosC = $comoCoautor->trabalhos;
            foreach ($trabalhosC as $trab) {
                if ($trab->autorId != auth()->user()->id) {
                    $trabalhos->push($trab);
                }
            }
        }

        return view('coautor.listarTrabalhos', [
            'trabalhos' => $trabalhos,
        ]);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coautor  $coautor
     * @return \Illuminate\Http\Response
     */
    public function show(Coautor $coautor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coautor  $coautor
     * @return \Illuminate\Http\Response
     */
    public function edit(Coautor $coautor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Coautor  $coautor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coautor $coautor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coautor  $coautor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coautor $coautor)
    {
        //
    }
}
