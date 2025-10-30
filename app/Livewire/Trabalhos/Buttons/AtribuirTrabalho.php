<?php

namespace App\Livewire\Trabalhos\Buttons;

use Livewire\Component;
use App\Livewire\Ui\Modal as ModalComp;

class AtribuirTrabalho extends Component
{
    public int $trabalhoId;
    public int $eventoId;

    public function abrir(): void
    {
        // Nada de named params aqui: passe posicionalmente
        $this->dispatch(
            'open-modal',
            'trabalhos.modals.atribuir-trabalho',                                   // $component
            ['trabalhoId' => $this->trabalhoId, 'eventoId' => $this->eventoId],  // $params
            'Trabalho'                                          // $title
        )->to(ModalComp::class);
    }

    public function render()
    {
        return view('livewire.trabalhos.buttons.atribuir-trabalho');
    }
}
