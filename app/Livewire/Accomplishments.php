<?php

namespace App\Livewire;

use App\Models\AccomplishmentModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Accomplishments')]
class Accomplishments extends Component
{
    use WithPagination;

    public $editMode;
    public $accomplishment_id;

    public function render()
    {
        return view(
            'livewire.accomplishments',
            [
                'accomplishments' => $this->loadAccomplishments()
            ]
        );
    }

    public function loadAccomplishments()
    {
        return AccomplishmentModel::paginate(10);
    }

    //TODO Continue
}
