<?php

namespace App\Jobs;

use App\Mail\EmailCertificado;
use App\Mail\EmailCertificadoSemAnexo;
use App\Models\Submissao\Evento;
use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class EnviarCertificadoJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    public $timeout = 600;

    public $certificadoId;
    public $destinatarioId;
    public $evento_id;
    public $cargo;
    public $path;

    protected $semAnexo = null;

    /**
     * Create a new job instance.
     */
    public function __construct($destinatarioId, $cargo, $certificadoId, $evento_id, $path)
    {
        $this->destinatarioId = $destinatarioId;
        $this->cargo = $cargo;
        $this->evento_id = $evento_id;
        $this->certificadoId = $certificadoId;
        $this->path = $path;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $key = "send-certificado-user-{$this->destinatarioId}-mails";
        $maxAttempts = 200;
        $decaySeconds = 60;

        $user = User::find($this->destinatarioId);
        $evento = Evento::find($this->evento_id);

        if (! $user) {
            Log::warning("EnviarCertificadoJob: user not found: {$this->destinatarioId}");
            $this->fail(new RuntimeException("Usuario nao encontrado: {$this->destinatarioId}"));
            return;
        }
        if (! $evento) {
            Log::warning("EnviarCertificadoJob: evento not found: {$this->evento_id}");
            $this->fail(new RuntimeException("Evento nao encontrado: {$this->evento_id}"));
            return;
        }

        if (is_null($user->email) || $user->email === '') {
            Log::warning("EnviarCertificadoJob: user sem e-mail: {$this->destinatarioId}");
            $this->fail(new RuntimeException("Usuario sem email: {$this->destinatarioId}"));
            return;
        }

        $funcionou = RateLimiter::attempt(
            $key,
            $maxAttempts,
            function () use ($user, $evento) {

                if ($this->semAnexo) {
                    $link = route('certificado.view', urlencode($this->validacaoHash));
                    Mail::to($user->email)->send( new EmailCertificadoSemAnexo($user, $this->cargo, $evento->nome, $link));
                } else {
                   //pegar o arquivo do certificado no storage
                   if (Storage::disk('local')->exists($this->path)) {
                        $pdf = Storage::get($this->path);

                        Mail::to($user->email)->send(new EmailCertificado($user, $this->cargo, $evento->nome, $pdf));

                        return true;
                    }

                }
                return false;
            },
            $decaySeconds
        );

        if (!$funcionou) {
            $wait = RateLimiter::availableIn($key);
            $this->release($wait > 0 ? $wait : 10);
        }
    }

    public function failed(Throwable $exception)
    {
        Log::error("EnviarCertificadoJob failed user={$this->destinatarioId}: {$exception->getMessage()}");
    }

    public function uniqueId(): string
    {
        return "certificado-{$this->certificadoId}-destinatario-{$this->destinatarioId}";
    }

}
