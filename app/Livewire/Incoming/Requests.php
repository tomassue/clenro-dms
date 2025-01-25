<?php

namespace App\Livewire\Incoming;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Incoming Requests')]
class Requests extends Component
{
    public function render()
    {
        return view('livewire.incoming.requests');
    }
}
