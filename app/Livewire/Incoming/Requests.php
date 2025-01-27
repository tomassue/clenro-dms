<?php

namespace App\Livewire\Incoming;

use App\Models\IncomingRequestModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Incoming Requests')]
class Requests extends Component
{
    use WithPagination, WithFileUploads;

    public $editMode;
    public $incoming_request_id;
    public $search;
    public $incoming_request_no, $office_or_barangay_or_organization_name, $date_requested, $date_returned, $actual_returned_date, $category_id, $sub_category_id, $venue_id, $time_started, $time_ended, $contact_person_name, $contact_person_number, $description, $file_id = [], $status_id;

    public function rules()
    {
        return [
            'incoming_request_no' => 'required',
            'office_or_barangay_or_organization_name' => 'required',
            'date_requested' => 'required',
            'date_returned' => 'required',
            'actual_returned_date' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'venue_id' => 'required',
            'time_started' => 'required',
            'time_ended' => 'required',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'description' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'category',
            'sub_category_id' => 'sub-category',
            'venue_id' => 'venue'
        ];
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view(
            'livewire.incoming.requests',
            [
                'incoming_requests' => $this->loadIncomingRequests()
            ]
        );
    }

    public function loadIncomingRequests()
    {
        return IncomingRequestModel::paginate(10);
    }

    public function createIncomingRequest()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            //
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
