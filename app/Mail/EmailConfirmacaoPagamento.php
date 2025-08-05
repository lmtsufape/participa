<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class EmailConfirmacaoPagamento extends Mailable
{
    use Queueable, SerializesModels;

    public $evento;
    public $inscricao;
    public $user;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($inscricao, $evento)
    {
        $this->evento = $evento;
        $this->inscricao = $inscricao;
        $this->user = $inscricao->user;
        $this->email = $inscricao->user->email;
    }

    public function build()
    {
        if (!$this->inscricao->codigo_validacao) {
            $this->inscricao->codigo_validacao = $this->gerarCodigoValidacao();
            $this->inscricao->save();
        }

        $data = [
            'nome' => $this->inscricao->user->name,
            'valor' => $this->inscricao->pagamento ? $this->inscricao->pagamento->valor : 0,
            'data' => now(),
            'codigo_validacao' => $this->inscricao->codigo_validacao,
        ];

        $pdf = Pdf::loadView('inscricao.recibo_pdf', $data)
                ->setPaper('a4', 'portrait');

        $email = $this
            ->to($this->email)
            ->subject('Confirmação de Pagamento - ' . $this->evento->nome)
            ->markdown('emails.emailConfirmacaoPagamento', [
                'user' => $this->user,
                'evento' => $this->evento,
                'inscricao' => $this->inscricao
            ]);

        try {
            $email->attachData($pdf->output(), "recibo-{$this->inscricao->id}.pdf", [
                'mime' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF do recibo: ' . $e->getMessage());
        }

        return $email;
    }

    private function gerarCodigoValidacao()
    {
        do {
            $codigo = strtoupper(substr(md5(uniqid()), 0, 16));
        } while (\App\Models\Inscricao\Inscricao::where('codigo_validacao', $codigo)->exists());

        return $codigo;
    }
}
