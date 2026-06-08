<?php

namespace App\Livewire\Ui;

use Livewire\Component;
use Livewire\Attributes\On;

class Modal extends Component
{
    public bool $open = false;
    public string $child = '';
    public array $params = [];
    public int $instance = 0;
    public string $htmlTitle = 'Detalhes';

    #[On('open-modal')]
    public function open($component, $params = [], $title = 'Detalhes'): void
    {
        $this->child     = (string) $component;
        $this->params    = is_array($params) ? $params : [];
        $this->htmlTitle = (string) $title;
        $this->open      = true;
        $this->instance++;

        $this->dispatch('bootstrap-modal:show', id: 'appModal');
    }

    #[On('close-modal')]
    public function close(): void
    {
        $this->reset(['open','child','params']);
        $this->dispatch('bootstrap-modal:hide', id: 'appModal');
        $this->instance++;
    }

    public function render() { return view('livewire.ui.modal'); }
}
