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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\EmailCertificado;
use App\Mail\EmailCertificadoSemAnexo;

class EmitirCertificadoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $destinatariosIds;
    protected $certificadoId;
    protected $evento;
    protected $semAnexo;

    protected const BATCH_SIZE = 50; 
    protected const SLEEP_SECONDS = 15;

    public function __construct(array $destinatariosIds, int $certificadoId, Evento $evento, bool $semAnexo)
    {
        $this->destinatariosIds = $destinatariosIds;
        $this->certificadoId = $certificadoId;
        $this->evento = $evento;
        $this->semAnexo = $semAnexo;
        $this->onQueue('certificados');
    }

    public function handle()
    {
        $certificado = Certificado::find($this->certificadoId);

        if (!$certificado || $certificado->medidas->count() == 0) {
            return;
        }

        $lotes = array_chunk($this->destinatariosIds, self::BATCH_SIZE);
        $totalLotes = count($lotes);

        foreach ($lotes as $loteIndex => $loteIds) {
            $this->processarLote($loteIds, $certificado);

            if ($loteIndex < $totalLotes - 1) {
                sleep(self::SLEEP_SECONDS);
            }
        }
    }

    protected function processarLote(array $loteIds, Certificado $certificado)
    {
        $evento = $this->evento;
        $semAnexo = $this->semAnexo;
        $tipoDestinatario = $certificado->tipo; 

        foreach ($loteIds as $destinatarioId) {
            $validacaoHash = Hash::make($destinatarioId); 
            $qrcode = base64_encode(QrCode::generate($validacaoHash));
            $user = User::find($destinatarioId);
            
            if (!$user) continue;

            DB::table('certificado_user')->insert([
                'certificado_id' => $certificado->id,
                'user_id' => $destinatarioId,
                'validacao' => $validacaoHash,
                'valido' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $cargo_label = 'participante (credenciado)';
            $texto = $certificado->texto;
            
            if ($semAnexo) {
                $link = route('certificado.view', urlencode($validacaoHash));
                Mail::to($user->email)->send(new EmailCertificadoSemAnexo($user, $cargo_label, $evento->nome, $link));
            } else {
                $pdf = Pdf::loadView('coordenador.certificado.certificado_preenchivel', [
                    'texto' => $texto, 
                    'qrcode' => $qrcode, 
                    'validacao' => $validacaoHash, 
                    'certificado' => $certificado, 
                    'user' => $user, 
                    'cargo' => $cargo_label, 
                    'evento' => $evento, 
                    'dataHoje' => $certificado->data->isoFormat('LL'), 
                    'now' => now()->isoFormat('LL')
                ])->setPaper('a4', 'landscape');
                
                Mail::to($user->email)->send(new EmailCertificado($user, $cargo_label, $evento->nome, $pdf));
            }
        }
    }

}