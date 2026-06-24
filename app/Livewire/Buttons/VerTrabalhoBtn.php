<?php

namespace App\Livewire\Buttons;

use Livewire\Component;
use App\Livewire\Ui\Modal as ModalComp;

class VerTrabalhoBtn extends Component
{
    public int $trabalhoId;
    public int $eventoId;

    public function abrir(): void
    {
        // Nada de named params aqui: passe posicionalmente
        $this->dispatch(
            'open-modal',
            'modals.trabalho',                                   // $component
            ['trabalhoId' => $this->trabalhoId, 'eventoId' => $this->eventoId],  // $params
            'Trabalho'                                          // $title
        )->to(ModalComp::class);
    }

    public function render()
    {
        return view('livewire.buttons.ver-trabalho-btn');
    }
}
