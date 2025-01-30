<?php

namespace App\Livewire\Incoming;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Incoming Documents')]
class Documents extends Component
{
    public $editMode;
    public $search;
    public $incoming_document_id;

    public function render()
    {
        return view('livewire.incoming.documents');
    }
}
