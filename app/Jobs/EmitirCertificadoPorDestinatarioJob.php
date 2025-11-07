<?php

namespace App\Jobs;

use App\Models\Submissao\Certificado;
use App\Models\Submissao\Evento;
use App\Models\Users\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Svg\Gradient\Stop;
use Throwable;

class EmitirCertificadoPorDestinatarioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    public $destinatarioId;
    public $certificadoId;
    public $evento_id;
    protected $semAnexo;
    public $trabalhoId;
    protected $validacaoHash;

    public $cargo;


    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($destinatarioId, $cargo,  $certificadoId, $evento_id, $semAnexo, $trabalhoId = null)
    {
        $this->destinatarioId = $destinatarioId;
        $this->certificadoId = $certificadoId;
        $this->evento_id = $evento_id;
        $this->semAnexo = $semAnexo;
        $this->trabalhoId = $trabalhoId;
        $this->cargo = $cargo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (function_exists('set_time_limit')) {
            set_time_limit(0); // 0 = ilimitado
        }

        $this->validacaoHash = Hash::make($this->destinatarioId . now()->timestamp . mt_rand());

        $certificado = Certificado::find($this->certificadoId);
        $user = User::find($this->destinatarioId);
        $evento = Evento::find($this->evento_id);

        if (!$certificado || !$user) {
            return;
        }

        $pdf = Pdf::loadView('coordenador.certificado.certificado_preenchivel', [
            'texto' => $certificado->texto,
            'qrcode' => base64_encode(QrCode::generate($this->validacaoHash)),
            'validacao' => $this->validacaoHash,
            'certificado' => $certificado,
            'user' => $user,
            'cargo' => $this->cargo,
            'evento' => $evento,
            'dataHoje' => $certificado->data->isoFormat('LL'),
            'now' => now()->isoFormat('LL')
        ])->setPaper('a4', 'landscape');

        $path = "certificados/{$evento->id}/{$user->id}/certificado_{$evento->nome}.pdf";

        if($pdf){
            Storage::disk('local')->put($path, $pdf->output());

            if(Storage::disk('local')->exists($path)){
                DB::table('certificado_user')->updateOrInsert([
                    'certificado_id' => $this->certificadoId,
                    'user_id' => $this->destinatarioId,
                    'validacao' => $this->validacaoHash,
                    'valido' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'trabalho_id' => null,
                    'palestra_id' => null,
                    'comissao_id' => null,
                    'atividade_id' => null,
                    'path' => $path,
                ]);
            }
        }else{
            Log::warning("EmitirCertificadoPorDestinatarioJob: Falha na criação do pdf do user: {$this->destinatarioId}");
            $this->fail(new RuntimeException("Falha na criação do pdf do user: {$this->destinatarioId}"));
        }

        EnviarCertificadoJob::dispatch($this->destinatarioId, $this->cargo, $this->certificadoId, $this->evento_id, $path)
            ->onQueue('envio_emails_certificados');

    }


    public function failed(Throwable $exception)
    {
        Log::error("EmitirCertificadoPorDestinatarioJob failed: {$exception->getMessage()}");
    }
}