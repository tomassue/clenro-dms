<?php

namespace App\Livewire\Components;

use App\Livewire\Incoming\Documents;
use App\Models\DivisionModel;
use App\Models\ForwardedIncomingDocumentsModel;
use App\Models\ForwardedIncomingRequestModel;
use App\Models\IncomingDocumentModel;
use App\Models\IncomingRequestModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ForwardToDivisionModal extends Component
{
    public $incoming_document_id,
        $incoming_request_id;
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
    public function setIncomingDocumentId($id)
    {
        if ($this->page == 'incoming requests') {
            $this->incoming_request_id = $id;

            $this->division_id = [];

            $this->division_id = ForwardedIncomingRequestModel::where('incoming_request_id', $id)
                ->pluck('division_id')
                ->map(fn($id) => (int) $id)
                ->toArray();

            $this->dispatch('set-division-select', id: $this->division_id);
        } elseif ($this->page == 'incoming documents') {
            $this->incoming_document_id = $id;

            $this->division_id = [];

            $this->division_id = ForwardedIncomingDocumentsModel::where('incoming_document_id', $id)
                ->pluck('division_id')
                ->map(fn($id) => (int) $id)
                ->toArray();

            $this->dispatch('set-division-select', id: $this->division_id);
        } else {
            $this->dispatch('error');
        }
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

        try {
            if ($this->page == 'incoming requests') {
                DB::transaction(function () {
                    foreach ($this->division_id as $item) {
                        $incoming_request = IncomingRequestModel::findOrFail($this->incoming_request_id);
                        $incoming_request->status_id = '8'; // Forwarded
                        $incoming_request->save();

                        // $forwarded_incoming_request = new ForwardedIncomingRequestModel();
                        // $forwarded_incoming_request->incoming_request_id = $this->incoming_request_id;
                        // $forwarded_incoming_request->division_id = $item;
                        // $forwarded_incoming_request->save();

                        // Get currently saved division IDs from the database
                        $existing_division_ids = ForwardedIncomingRequestModel::where('incoming_request_id', $this->incoming_request_id)
                            ->pluck('division_id')
                            ->toArray();

                        // Determine which divisions need to be deleted
                        $divisions_to_delete = array_diff($existing_division_ids, $this->division_id);
                        if (!empty($divisions_to_delete)) {
                            ForwardedIncomingRequestModel::where('incoming_request_id', $this->incoming_request_id)
                                ->whereIn('division_id', $divisions_to_delete)
                                ->delete();
                        }

                        // Determine which divisions need to be inserted
                        $divisions_to_insert = array_diff($this->division_id, $existing_division_ids);
                        foreach ($divisions_to_insert as $division) {
                            ForwardedIncomingRequestModel::create([
                                'incoming_request_id' => $this->incoming_request_id,
                                'division_id' => $division,
                            ]);
                        }
                    }
                });

                $this->clear();
                $this->dispatch('hide-forwardToDivisionModal');
                $this->dispatch('success', message: 'Request forwarded to division successfully.');
                $this->dispatch('refresh-incoming-requests');
            } elseif ($this->page == 'incoming documents') {
                try {
                    DB::transaction(function () {
                        foreach ($this->division_id as $item) {
                            $incoming_document = IncomingDocumentModel::findOrFail($this->incoming_document_id);
                            $incoming_document->status_id = '8'; // Forwarded
                            $incoming_document->save();

                            // $forwarded_incoming_document = new ForwardedIncomingDocumentsModel();
                            // $forwarded_incoming_document->incoming_document_id = $this->incoming_document_id;
                            // $forwarded_incoming_document->division_id = $item;
                            // $forwarded_incoming_document->save();

                            // Get currently saved division IDs from the database
                            $existing_division_ids = ForwardedIncomingDocumentsModel::where('incoming_document_id', $this->incoming_document_id)
                                ->pluck('division_id')
                                ->toArray();

                            // Determine which divisions need to be deleted
                            $divisions_to_delete = array_diff($existing_division_ids, $this->division_id);
                            if (!empty($divisions_to_delete)) {
                                ForwardedIncomingDocumentsModel::where('incoming_document_id', $this->incoming_document_id)
                                    ->whereIn('division_id', $divisions_to_delete)
                                    ->delete();
                            }

                            // Determine which divisions need to be inserted
                            $divisions_to_insert = array_diff($this->division_id, $existing_division_ids);
                            foreach ($divisions_to_insert as $division) {
                                ForwardedIncomingDocumentsModel::create([
                                    'incoming_document_id' => $this->incoming_document_id,
                                    'division_id' => $division,
                                ]);
                            }
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
            }
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
