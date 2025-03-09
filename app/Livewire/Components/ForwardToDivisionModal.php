<?php

namespace App\Livewire\Components;

use App\Models\DivisionModel;
use Livewire\Attributes\On;
use Livewire\Component;

class ForwardToDivisionModal extends Component
{
    public $incoming_document_id;
    public $page;
    public $division_id = [];

    public function rules()
    {
        return [
            'division_id' => 'required'
        ];
    }

    //TODO
    // An event was triggered from the parent component which is the documents together with the parameter.
    #[On('show-forwardToDivisionModal')]
    public function setIncomingDocumentId($incoming_document_id)
    {
        $this->incoming_document_id = $incoming_document_id;
    }

    public function clear()
    {
        $this->resetExcept('page');
        $this->resetValidation();
        $this->dispatch('reset-division-select');
    }

    public function render()
    {
        return view(
            'livewire.components.forward-to-division-modal',
            [
                'division_select' => $this->loadDivisionsSelect()
            ]
        );
    }

    public function loadDivisionsSelect()
    {
        return DivisionModel::whereNot('division_name', 'Admin')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->division_name
                ];
            });
    }

    public function forwardToDivision()
    {
        if ($this->page == 'incoming documents') {
            dd($this->division_id);
        } else {
            dd('wew');
        }
    }
}
