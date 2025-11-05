<?php

namespace App\Jobs;

use App\Mail\EmailCertificado;
use App\Mail\EmailCertificadoSemAnexo;
use App\Models\Submissao\Certificado;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Trabalho;
use App\Models\Users\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\RateLimiter;

class EmitirCertificadoPorDestinatarioJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 600;

    protected $destinatarioId;
    protected $certificadoId;
    protected $evento;
    protected $semAnexo;
    protected $trabalhoId; 
    protected $validacaoHash;

    /**
     * Define the unique signature of the job.
     * Para evitar re-emissões do mesmo certificado para o mesmo usuário.
     * @return string
     */
    public function uniqueId(): string
    {
        return "certificado-{$this->certificadoId}-destinatario-{$this->destinatarioId}";
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($destinatarioId, $certificadoId, Evento $evento, $semAnexo, $validacaoHash, $trabalhoId = null)
    {
        $this->destinatarioId = $destinatarioId;
        $this->certificadoId = $certificadoId;
        $this->evento = $evento;
        $this->semAnexo = $semAnexo;
        $this->validacaoHash = $validacaoHash;
        $this->trabalhoId = $trabalhoId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $key = 'send-certificate-mails';
        $maxAttempts = 200; 
        $decayMinutes = 1;

        RateLimiter::attempt(
            $key,
            $maxAttempts,
            function () {
                $certificado = Certificado::find($this->certificadoId);
                $evento = $this->evento;
                $user = User::find($this->destinatarioId);
                $validacao = $this->validacaoHash;
                $semAnexo = $this->semAnexo;

                if (!$certificado || !$user) {
                    return;
                }
                
                $cargo_label = 'participante (credenciado)';
                
                $qrcode = base64_encode(QrCode::generate($validacao));
                $texto = $certificado->texto;
                
                
                if ($semAnexo) {
                    $link = route('certificado.view', urlencode($validacao));
                    Mail::to($user->email)->send(new EmailCertificadoSemAnexo($user, $cargo_label, $evento->nome, $link));
                } else {
                    $pdf = Pdf::loadView('coordenador.certificado.certificado_preenchivel', [
                        'texto' => $texto, 
                        'qrcode' => $qrcode, 
                        'validacao' => $validacao, 
                        'certificado' => $certificado, 
                        'user' => $user, 
                        'cargo' => $cargo_label, 
                        'evento' => $evento, 
                        'dataHoje' => $certificado->data->isoFormat('LL'), 
                        'now' => now()->isoFormat('LL')
                    ])->setPaper('a4', 'landscape');
                    
                    Mail::to($user->email)->send(new EmailCertificado($user, $cargo_label, $evento->nome, $pdf));
                }

            },
            $decayMinutes
        );
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $this->release(RateLimiter::availableIn($key)); 
        }
    }
}