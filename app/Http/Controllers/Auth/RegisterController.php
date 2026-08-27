<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Submissao\Endereco;
use App\Models\Users\User;
use App\Providers\RouteServiceProvider;
use App\Rules\UniqueCaseInsensitive;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailConfirmacaoCadastro;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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

    public function register(StoreUserRequest $request)
    {
        $payload = $request->payload();

        $user = DB::transaction(function () use ($payload) {
            $userData = $payload['user'];
            $enderecoData = $payload['endereco'];
            $perfilIdentitarioData = $payload['perfilIdentitario'];

            $user = User::withTrashed()
                ->where('email', $userData['email'])
                ->first();

            if ($user) {
                $user->restore();

                $user->update($userData);
            } else {
                $user = User::create($userData);
            }

            if (collect($enderecoData)->contains(fn ($value) => filled($value))) {
                if ($user->enderecoId) {
                    $endereco = Endereco::find($user->enderecoId);

                    if ($endereco) {
                        $endereco->update($enderecoData);
                    } else {
                        $endereco = Endereco::create($enderecoData);

                        $user->update([
                            'enderecoId' => $endereco->id,
                        ]);
                    }
                } else {
                    $endereco = Endereco::create($enderecoData);

                    $user->update([
                        'enderecoId' => $endereco->id,
                    ]);
                }
            }

            if ($user->perfilIdentitario()->exists()) {
                $user->perfilIdentitario()->update(
                    $perfilIdentitarioData
                );
            } else {
                $user->perfilIdentitario()->create(
                    $perfilIdentitarioData
                );
            }

            return $user;
        });

        event(new Registered($user));

        $this->guard()->login($user);

        Mail::to($user->email)
            ->send(new EmailConfirmacaoCadastro($user));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    protected function redirectTo()
    {
        return route('index');
    }
}
