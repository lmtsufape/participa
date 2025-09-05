<?php

namespace App\Livewire\Modals;

use App\Mail\EmailConviteRevisor;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Modalidade;
use App\Models\Submissao\Trabalho as SubmissaoTrabalho;
use App\Models\Users\Revisor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Trabalho extends Component
{
    public int $trabalhoId;
    public int $eventoId;

    public SubmissaoTrabalho $trabalho;
    public Evento $evento;

    #[Validate('required|integer')]
    public $revisorId;


    protected $rules = [
        'eventoId'   => 'required|integer|exists:eventos,id',
        'trabalhoId' => 'required|integer|exists:trabalhos,id',
        'revisorId'  => 'required|integer|exists:revisors,id',
    ];

    public function mount(int $trabalhoId, int $eventoId)
    {
        $this->trabalho = SubmissaoTrabalho::with(['autor','coautors.user','atribuicoes.user','modalidade','area'])
                           ->findOrFail($trabalhoId);
        $this->evento   = Evento::findOrFail($eventoId);
    }

    public function addRevisor()
    {
        $data = $this->validate();

        DB::transaction(function () use ($data) {
            $evento   = Evento::lockForUpdate()->findOrFail($data['eventoId']);
            abort_unless(Gate::any([
                'isCoordenadorOrCoordenadorDaComissaoCientifica',
                'isCoordenadorEixo'
            ], $evento), 403);

            $trabalho = SubmissaoTrabalho::lockForUpdate()->findOrFail($data['trabalhoId']);
            $revisor  = Revisor::findOrFail($data['revisorId']);

            if($trabalho->atribuicoes()->whereKey($revisor->id)->exists()){
                session()->flash('error', 'Revisor já atribuído ao trabalho.');
                return;
            }

            if( $trabalho->autor_id === $revisor->user_id ||
                $trabalho->coautors()->where('autorId', $revisor->user_id)->exists())
            {
                session()->flash('error', $revisor->user->name . 'não pode ser revisor deste trabalho.');
                return;
            }

            $trabalho->avaliado = 'processando';
            $trabalho->save();

            $prazo = $this->atualizarPrazoCorrecaoAtribuicao($trabalho->id);
            $token = Str::random(40);

            $revisor->trabalhosAtribuidos()->syncWithoutDetaching([
                $trabalho->id => [
                    'confirmacao'    => false,
                    'parecer'        => 'processando',
                    'prazo_correcao' => $prazo,
                    'token'          => $token,
                ],
            ]);

            $revisor->increment('correcoesEmAndamento');

            $subject = config('app.name').' - Atribuição como avaliador(a) e/ou parecerista';
            Mail::to($revisor->user->email)->queue(
                new EmailConviteRevisor($revisor->user, $evento, $subject, $evento->email, $trabalho->titulo, $token)
            );
        });

        $this->trabalho->load('atribuicoes.user','coautors.user');
        session()->flash('success', 'Atribuição realizada com sucesso!');

        $this->reset('revisorId');

    }

    public function removerRevisor(int $revisorId)
    {
        $this->validateOnly('eventoId');
        $this->validateOnly('trabalhoId');

        $evento   = $this->evento ?? Evento::find($this->eventoId);
        $trabalho = SubmissaoTrabalho::find($this->trabalhoId);
        $revisor  = Revisor::find($revisorId);

        if (! $evento || ! $trabalho || ! $revisor) {
            session()->flash('error', 'Registro não encontrado (evento, trabalho ou revisor).');
            return;
        }

        if (! Gate::any([
            'isCoordenadorOrCoordenadorDaComissaoCientifica',
            'isCoordenadorEixo',
        ], $evento)) {
            session()->flash('error', 'Acesso negado.');
            return;
        }

        if ($trabalho->avaliado($revisor->user)) {
            session()->flash('error', 'O revisor já deu início à avaliação do trabalho, ele não pode ser removido.');
            return;
        }

        DB::transaction(function () use ($trabalho, $revisor, $revisorId) {
            $revisor->decrement('correcoesEmAndamento');

            $trabalho->atribuicoes()->detach($revisorId);

        });

        $this->trabalho = $trabalho->fresh('atribuicoes.user');

        $mensagem = $trabalho->titulo.' foi retirado de '.$revisor->user->name.' com sucesso!';
        session()->flash('success', $mensagem);

        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function atualizarPrazoCorrecaoAtribuicao($trabalhoId)
    {
        $modalidadeid = SubmissaoTrabalho::find($trabalhoId)->modalidadeId;
        $modalidade = Modalidade::find($modalidadeid);
        $prazoCorrecao = now()->addDays(10)->startOfDay();
        if($prazoCorrecao > $modalidade->fimRevisao) {
            $prazoCorrecao = $modalidade->fimRevisao;
        }
        return $prazoCorrecao;
    }

    public function render()
    {
        $opcoes = $this->evento->revisors()
            ->where([['modalidadeId',$this->trabalho->modalidade->id],['areaId',$this->trabalho->area->id]])
            ->get()
            ->filter(fn($r)=> !$this->trabalho->atribuicoes->contains($r)
                            && is_null($this->trabalho->coautors->where('autorId',$r->user_id)->first())
                            && $this->trabalho->autorId != $r->user_id)
            ->sortBy(fn($r) => $r->user->name ?? '');

        return view('livewire.modals.trabalho', compact('opcoes'));
    }
}

