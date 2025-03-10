<?php

namespace App\Livewire\Components;

use App\Livewire\Incoming\Documents;
use App\Models\DivisionModel;
use App\Models\ForwardedIncomingDocumentsModel;
use App\Models\IncomingDocumentModel;
use Illuminate\Support\Facades\DB;
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

    public function attributes()
    {
        return [
            'division_id' => 'division'
        ];
    }

    //* An event was triggered from the parent component which is the documents together with the parameter.
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
        $this->validate($this->rules(), [], $this->attributes());

        if ($this->page == 'incoming documents') {
            try {
                DB::transaction(function () {
                    foreach ($this->division_id as $item) {
                        $incoming_document = IncomingDocumentModel::findOrFail($this->incoming_document_id);
                        $incoming_document->status_id = '8'; // Forwarded
                        $incoming_document->save();

                        $forwarded_incoming_document = new ForwardedIncomingDocumentsModel();
                        $forwarded_incoming_document->incoming_document_id = $this->incoming_document_id;
                        $forwarded_incoming_document->division_id = $item;
                        $forwarded_incoming_document->save();
                    }
                });

                $this->clear();
                $this->dispatch('hide-forwardToDivisionModal');
                $this->dispatch('success', message: 'Document forwarded to division successfully.');

                /**
                 ** Since every livewire compoment is an island, updates from the child component won't be reflected in the parent component.
                 ** So we dispatch an event to refresh the parent component.
                 * @see App\Livewire\Incoming\Documents::class, @method refreshIncomingDocuments
                 */
                $this->dispatch('refresh-incoming-documents');
            } catch (\Throwable $th) {
                // throw $th;
                $this->dispatch('error');
            }
        } else {
            //TODO Incoming Requests 
            dd('wew');
        }
    }
}
