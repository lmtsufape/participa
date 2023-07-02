<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Endereco;
use App\Models\Users\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // dd($data);
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'cpf' => ($data['passaporte'] == null ? ['required', 'cpf'] : 'nullable'),
            'passaporte' => ($data['cpf'] == null ? 'required|max:10' : 'nullable'),
            'celular' => ['required', 'string', 'max:20'],
            'instituicao' => ['required', 'string', 'max:255'],
            'pais' => ['required', 'string', 'max:255'],
            'rua' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string'],
            'bairro' => ['required', 'string', 'max:255'],
            'cidade' => ['required', 'string', 'max:255'],
            'uf' => ['required', 'string'],
            'cep' => ['required', 'string'],
            'complemento' => ['nullable', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        // dd("teste");
        // endereço
        // dd($end)

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->cpf = $data['cpf'];
        $user->passaporte = $data['passaporte'];
        $user->celular = $data['full_number'];
        $user->instituicao = $data['instituicao'];

        if ($data['rua'] != null && $data['cep'] != null) {
            $end = new Endereco($data);
            $end->save();
            $user->enderecoId = $end->id;
            $user->save();

            return $user;
        }

        $user->enderecoId = null;
        $user->save();

        app()->setLocale('pt-BR');

        return $user;
    }
}

// 'cpf'           => ['required_if: passaporte, null' ,
//                                     function ($attribute, $value, $fail) use ($data){
//                                         if ($data['passaporte'] == null && $value == null) {
//                                             $fail($attribute.' ou cpf precisa ser preenchido.');
//                                             return;
//                                         }
//                                         if ($data['passaporte'] == null && User::where('cpf',$data['cpf'])->count() != 0) {
//                                             $fail($attribute.' já está me uso.');
//                                             return;
//                                         }
//                                         if ($data['cpf'] != null) {
//                                             if (User::where('cpf',$value)->count() != 0) {
//                                                 $fail($attribute.' já está me uso.');
//                                                 return;
//                                             }
//                                         }

//                                     },],
//             'passaporte'    => ['required_if: cpf, null','max:10',
//                                 function ($attribute, $value, $fail) use ($data){
//                                     if ($data['cpf'] == null && $value == null) {
//                                         // dd( $data['cpf'] == null && $value == null);
//                                         $fail($attribute.' ou cpf precisa ser preenchido.');
//                                         return;
//                                     }
//                                     if ($data['passaporte'] != null && User::where('passaporte',$data['passaporte'])->count() != 0) {
//                                         $fail($attribute.' já está me uso.');
//                                         return;
//                                     }

//                                 },
//                                 ],
