<?php

namespace App\Livewire\Incoming;

use App\Models\CategoryModel;
use App\Models\FilesModel;
use App\Models\IncomingRequestModel;
use App\Models\StatusModel;
use App\Models\SubCategoryModel;
use App\Models\VenueModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Title('Incoming Requests')]
class Requests extends Component
{
    use WithPagination, WithFileUploads;

    public $editMode;
    public $incoming_request_id;
    public $search,
        $filter_status;
    public $incoming_request_no,
        $office_or_barangay_or_organization_name,
        $date_requested,
        // $date_returned,
        // $actual_returned_date,
        $category_id,
        $sub_category_id,
        // $venue_id,
        // $time_started,
        // $time_ended,
        $date_and_time,
        $contact_person_name,
        $contact_person_number,
        $description,
        $file_id = [],
        $status_id;
    public $preview_file_id = [];
    public $document_history = [];

    public function rules()
    {
        $rules = [
            'incoming_request_no' => 'required',
            'office_or_barangay_or_organization_name' => 'required',
            // 'date_requested' => 'required',
            // 'date_returned' => 'required',
            // 'actual_returned_date' => 'required', //* ONLY required upon updating the status to DONE
            'category_id' => 'required',
            // 'venue_id' => 'required',
            // 'time_started' => 'required',
            // 'time_ended' => 'required',
            'date_and_time' => 'required',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'description' => 'required'
        ];

        if ($this->category_id && $this->loadSubCategorySelect()->count() != 0) {
            $rules['sub_category_id'] = 'required';
        }

        if ($this->editMode) {
            $rules['status_id'] = 'required';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'category_id' => 'category',
            'sub_category_id' => 'sub-category',
            // 'venue_id' => 'venue',
            // 'date_returned' => 'return date',
            'status_id' => 'status'
        ];
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();

        // Reset Plugins
        $this->dispatch('reset-files');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function generateIncomingRequestNo()
    {
        $this->incoming_request_no = IncomingRequestModel::generateUniqueReference('REF-', 8); // Pre-generate reference number to show in the input field (disabled).
    }

    public function render()
    {
        return view(
            'livewire.incoming.requests',
            [
                'incoming_requests' => $this->loadIncomingRequests(),
                'category_select' => $this->loadCategorySelect(),
                'sub_category_select' => $this->loadSubCategorySelect(),
                'venue_select' => $this->loadVenueSelect(),
                'status_select' => $this->loadStatusSelect()
            ]
        );
    }

    public function loadIncomingRequests()
    {
        return IncomingRequestModel::query()
            ->when($this->filter_status, function ($query) {
                $query->where('status_id', $this->filter_status);
            })
            ->paginate(10);
    }

    public function loadCategorySelect()
    {
        return CategoryModel::where('category_type_id', 1)
            ->get();
    }

    public function loadSubCategorySelect()
    {
        return SubCategoryModel::when($this->category_id, function ($query) {
            $query->where('category_id', $this->category_id);
        }, function ($query) {
            $query->whereNull('id'); // No results
        })->get();
    }

    public function loadVenueSelect()
    {
        return VenueModel::all();
    }

    public function loadStatusSelect()
    {
        return StatusModel::where('status_type', 'incoming request')
            ->get();
    }

    public function createIncomingRequest()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $incoming_request = new IncomingRequestModel();
                $incoming_request->incoming_request_no = $this->incoming_request_no;
                $incoming_request->office_or_barangay_or_organization_name = $this->office_or_barangay_or_organization_name;
                $incoming_request->date_requested = $this->date_requested;
                $incoming_request->date_returned = $this->date_returned;
                $incoming_request->category_id = $this->category_id;
                $incoming_request->sub_category_id = $this->sub_category_id;
                $incoming_request->venue_id = $this->venue_id;
                $incoming_request->time_started = $this->time_started;
                $incoming_request->time_ended = $this->time_ended;
                $incoming_request->contact_person_name = $this->contact_person_name;
                $incoming_request->contact_person_number = $this->contact_person_number;
                $incoming_request->description = $this->description;

                //* File upload
                foreach ($this->file_id ?? [] as $file) {
                    $files = new FilesModel();
                    $files->file_name = $file->getClientOriginalName();
                    $files->file_size = $file->getSize();
                    $files->file_type = $file->getMimeType();
                    $files->file_content = file_get_contents($file->path());
                    $files->user_id = Auth::user()->id;
                    $files->save();

                    $file_id[] = $files->id;
                }
                $incoming_request->file_id = json_encode($file_id ?? []);

                $incoming_request->status_id = '1'; //* PENDING
                $incoming_request->save();
            });

            $this->clear();
            $this->dispatch('hide-incomingRequestModal');
            $this->dispatch('success', message: 'Incoming Request created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    //* Reading data for updating after.
    public function readIncomingRequest($incoming_request_id)
    {
        try {
            $this->editMode = true;

            $this->incoming_request_id = $incoming_request_id;

            $incoming_request = IncomingRequestModel::withTrashed()->findOrFail($incoming_request_id);
            $this->fill(
                $incoming_request->only(
                    'incoming_request_no',
                    'office_or_barangay_or_organization_name',
                    'date_requested',
                    'date_returned',
                    'category_id',
                    'sub_category_id',
                    'venue_id',
                    'time_started',
                    'time_ended',
                    'contact_person_name',
                    'contact_person_number',
                    'description',
                    'status_id'
                )
            );

            if ($incoming_request->file_id) {
                $this->preview_file_id = []; // unset it first

                foreach (json_decode($incoming_request->file_id) as $item) {
                    $files = FilesModel::findOrFail($item);
                    $this->preview_file_id[] = $files;
                }
            }

            $this->dispatch('show-incomingRequestModal');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function readFile($file_id)
    {
        try {
            $signedURL = URL::temporarySignedRoute(
                'file.view',
                now()->addMinutes(10),
                ['id' => $file_id]
            );

            $this->dispatch('read-file', url: $signedURL);
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function updateIncomingRequest()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            $incoming_request = IncomingRequestModel::findOrFail($this->incoming_request_id);

            DB::transaction(function () use ($incoming_request) {
                $incoming_request->incoming_request_no = $this->incoming_request_no;
                $incoming_request->office_or_barangay_or_organization_name = $this->office_or_barangay_or_organization_name;
                $incoming_request->date_requested = $this->date_requested;
                $incoming_request->date_returned = $this->date_returned;
                $incoming_request->category_id = $this->category_id;
                $incoming_request->sub_category_id = $this->sub_category_id;
                $incoming_request->venue_id = $this->venue_id;
                $incoming_request->time_started = $this->time_started;
                $incoming_request->time_ended = $this->time_ended;
                $incoming_request->contact_person_name = $this->contact_person_name;
                $incoming_request->contact_person_number = $this->contact_person_number;
                $incoming_request->description = $this->description;

                //* File upload
                $file_id = [];

                foreach ($this->file_id ?? [] as $file) {
                    $files = new FilesModel();
                    $files->file_name = $file->getClientOriginalName();
                    $files->file_size = $file->getSize();
                    $files->file_type = $file->getMimeType();
                    $files->file_content = file_get_contents($file->path());
                    $files->user_id = Auth::user()->id;
                    $files->save();

                    $file_id[] = $files->id;
                }

                if (!empty($file_id)) {
                    $existing_file_id = json_decode($incoming_request->file_id, true) ?? [];
                    $updated_file_id = array_unique(array_merge($existing_file_id, $file_id));
                    $incoming_request->file_id = json_encode($updated_file_id);
                }

                $incoming_request->status_id = $this->status_id;
                $incoming_request->save();
            });

            $this->clear();
            $this->dispatch('hide-incomingRequestModal');
            $this->dispatch('success', message: 'Incoming Request updated successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function readDocumentHistory($incoming_request_id)
    {
        try {
            // Fetch all statuses in a key-value pair: [status_id => status_name]
            $statusMap = StatusModel::withTrashed()->pluck('status_name', 'id');

            $this->document_history = Activity::where('subject_type', IncomingRequestModel::class)
                ->where('subject_id', $incoming_request_id)
                ->where('log_name', 'incoming_request')
                ->whereNotNull('properties->attributes->status_id') //* Logs with changes in status_id ONLY
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) use ($statusMap) {
                    $oldStatusId = $item->properties['old']['status_id'] ?? null;
                    $newStatusId = $item->properties['attributes']['status_id'] ?? null;

                    return [
                        // 'incoming_request_no' => $item->subject->incoming_request_no ?? 'N/A',
                        'updated_at' => Carbon::parse($item->updated_at)->format('M d Y g:i A'),
                        'status' => $newStatusId ? $statusMap[$newStatusId] ?? 'Unknown Status' : 'N/A', //* UPDATED attributes
                        'updated_by' => $item->causer ? $item->causer->name : 'System'
                    ];
                });

            $this->dispatch('show-documentHistoryModal');
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
    }
}
