<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Endereco;
use App\Models\Users\User;
use App\Providers\RouteServiceProvider;
use App\Rules\UniqueCaseInsensitive;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailConfirmacaoCadastro;

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
    //protected $redirectTo = RouteServiceProvider::HOME;

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
        // Verifica se existe um usuário não deletado com o mesmo email
        $userAtivo = User::where('email', strtolower($data['email']))->whereNull('deleted_at')->first();
        if ($userAtivo) {
            $messages = ['email.unique' => 'Este email já está cadastrado no sistema.'];
            return Validator::make($data, ['email' => 'unique:users'], $messages);
        }

        $validations = [
            'name' => ['required', 'string', 'max:255'],
            'nomeSocial' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'dataNascimento' => ['required', 'date', 'before:today'],
            'cpf' => ($data['passaporte'] == null && $data['cnpj'] == null ? ['required', 'cpf'] : 'nullable'),
            'cnpj' => ($data['passaporte'] == null && $data['cpf'] == null ? ['required'] : 'nullable'),
            'passaporte' => ($data['cpf'] == null && $data['cnpj'] == null ? ['required', 'max:10'] : 'nullable'),
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
        ];
        if ($data['pais'] != 'brasil'){
            $validations['uf'] = ['nullable', 'string'];
            $validations['numero'] = ['nullable', 'string'];
            $validations['cep'] = ['nullable', 'string'];
        }

        return Validator::make($data, $validations);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Verifica se existe um usuário deletado com o mesmo email
        $userDeletado = User::withTrashed()->where('email', strtolower($data['email']))->first();

        if ($userDeletado) {
            // Restaura o user
            $userDeletado->restore();

            $userDeletado->name = $data['name'];
            $userDeletado->nomeSocial = $data['nomeSocial'] ?? null;
            $userDeletado->email = strtolower($data['email']);
            $userDeletado->dataNascimento = $data['dataNascimento'];
            $userDeletado->email_verified_at = now();
            $userDeletado->password = bcrypt($data['password']);
            $userDeletado->cpf = $data['cpf'];
            $userDeletado->cnpj = $data['cnpj'];
            $userDeletado->passaporte = $data['passaporte'];
            $userDeletado->celular = $data['full_number'];
            $userDeletado->instituicao = $data['instituicao'];

            if ($data['rua'] != null && $data['cep'] != null) {
                // Se o usuário já tinha um endereço, atualiza
                if ($userDeletado->enderecoId) {
                    $end = Endereco::find($userDeletado->enderecoId);
                    if ($end) {
                        $end->fill($data);
                        $end->save();
                    } else {
                        $end = new Endereco($data);
                        $end->save();
                        $userDeletado->enderecoId = $end->id;
                    }
                } else {
                    $end = new Endereco($data);
                    $end->save();
                    $userDeletado->enderecoId = $end->id;
                }
            }

            $userDeletado->save();

            Mail::to($userDeletado->email)->send(new EmailConfirmacaoCadastro($userDeletado));

            app()->setLocale('pt-BR');

            return $userDeletado;
        }

        $user = new User();
        $user->name = $data['name'];
        $user->nomeSocial = $data['nomeSocial'] ?? null;
        $user->email = strtolower($data['email']);
        $user->dataNascimento = $data['dataNascimento'];
        $user->email_verified_at = now();
        $user->password = bcrypt($data['password']);
        $user->cpf = $data['cpf'];
        $user->cnpj = $data['cnpj'];
        $user->passaporte = $data['passaporte'];
        $user->celular = $data['full_number'];
        $user->instituicao = $data['instituicao'];

        if ($data['rua'] != null && $data['cep'] != null) {
            $end = new Endereco($data);
            $end->save();
            $user->enderecoId = $end->id;
            $user->save();


            Mail::to($user->email)->send(new EmailConfirmacaoCadastro($user));
            return $user;
        }

        $user->enderecoId = null;
        $user->save();

        Mail::to($user->email)->send(new EmailConfirmacaoCadastro($user));

        app()->setLocale('pt-BR');

        return $user;
    }

    protected function redirectTo()
    {
        return route('index');
    }
}
