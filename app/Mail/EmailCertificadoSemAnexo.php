<?php

namespace App\Mail;

use App\Enums\TipoCertificado;
use App\Models\Submissao\Atividade;
use App\Models\Submissao\Certificado;
use App\Models\Submissao\Palestra;
use App\Models\Submissao\TipoComissao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EmailCertificadoSemAnexo extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $cargo, $nomeEvento, $link, $certificado, $trabalho, $request_palestra_id, $request_tipo_comissao_id, $request_atividade_id;

    protected $request_destinatario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $cargo, string $nomeEvento, $request_destinatario, $certificado, $trabalho = null, $request_palestra_id = null, $request_tipo_comissao_id = null, $request_atividade_id = null)
    {
        $this->user = $user;
        $this->cargo = $cargo;
        $this->nomeEvento = $nomeEvento;
        $this->request_destinatario = $request_destinatario;
        $this->certificado = $certificado;
        $this->trabalho = $trabalho;
        $this->request_palestra_id = $request_palestra_id;
        $this->request_tipo_comissao_id = $request_tipo_comissao_id;
        $this->request_atividade_id = $request_atividade_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $hash = Hash::make($this->user->id);
        $this->link = route('certificado.view', urlencode($hash));

        switch ($this->request_destinatario) {
            case TipoCertificado::Apresentador:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash, 'trabalho_id' => $this->trabalho->id]);

                break;
            case TipoCertificado::ComissaoCientifica:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash]);

                break;
            case TipoCertificado::ComissaoOrganizadora:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash]);

                break;
            case TipoCertificado::Revisor:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash]);

                break;
            case TipoCertificado::Participante:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash]);

                break;
            case TipoCertificado::Inscrito:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash]);

                break;
            case TipoCertificado::Palestrante:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash, 'palestra_id' => $this->request_palestra_id]);

                break;
            case TipoCertificado::CoordenadorComissaoCientifica:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash]);

                break;
            case TipoCertificado::OutrasComissoes:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash, 'comissao_id' => $this->request_tipo_comissao_id]);

                break;
            case TipoCertificado::InscritoAtividade:
                $this->certificado->usuarios()->attach($this->user->id, ['validacao' => $hash, 'atividade_id' => $this->request_atividade_id]);

                break;
        }

        return $this
            ->subject(config('app.name').' - Certificado')
            ->markdown('emails.emailEnviarCertificadoSemAnexo')
            ->with([
                'user' => $this->user,
                'cargo' => $this->cargo,
                'evento' => $this->nomeEvento,
                'link' => $this->link,
            ]);
    }
}
