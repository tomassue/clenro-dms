<?php

namespace App\Livewire\Incoming;

use App\Models\CategoryModel;
use App\Models\DivisionModel;
use App\Models\FilesModel;
use App\Models\IncomingRequestCategoryModel;
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
        $category_id,
        $date_and_time,
        $contact_person_name,
        $contact_person_number,
        $description,
        $file_id = [],
        $status_id,
        $remarks;
    public $division_id; //* Forwarded to division id
    public $preview_file_id = [];
    public $document_history = [];

    public function rules()
    {
        $rules = [
            'incoming_request_no' => 'required',
            'office_or_barangay_or_organization_name' => 'required',
            'category_id' => 'required',
            'date_and_time' => 'required',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'description' => 'required'
        ];

        if ($this->editMode) {
            $rules['status_id'] = 'required';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'category_id' => 'category',
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
                'recent_forwarded_incoming_requests' => $this->loadRecentForwardedIncomingRequests(),
                'category_select' => $this->loadCategorySelect(),
                'status_select' => $this->loadStatusSelect(),
                'division_select' => $this->loadDivisionSelect()
            ]
        );
    }

    public function loadIncomingRequests()
    {
        // $user = auth()->user();

        // $user_division_id = $user->division_id;

        // return IncomingRequestModel::query()
        //     ->when($this->filter_status, function ($query) {
        //         $query->where('status_id', $this->filter_status);
        //     })
        //     ->when($this->search, function ($query) {
        //         $query->where('incoming_request_no', 'like', '%' . $this->search . '%');
        //     })
        //     ->when(!empty($user_division_id) && $user_division_id != "1", function ($query) use ($user_division_id) {
        //         $query->where(function ($subQuery) use ($user_division_id) {
        //             $subQuery->whereNull('forwarded_to_division_id')
        //                 ->orWhere('forwarded_to_division_id', $user_division_id);
        //         });
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);

        $user = auth()->user();
        $user_division_id = $user->division_id;

        return IncomingRequestModel::query()
            ->when($this->filter_status, function ($query) {
                $query->where('status_id', $this->filter_status);
            })
            ->when($this->search, function ($query) {
                $query->where('incoming_request_no', 'like', '%' . $this->search . '%');
            })
            ->when(!is_null($user_division_id) && $user_division_id != "1", function ($query) use ($user_division_id) {
                $query->where('forwarded_to_division_id', $user_division_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function loadRecentForwardedIncomingRequests()
    {
        return IncomingRequestModel::query()
            ->with('division')
            ->whereNotNull('forwarded_to_division_id')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
    }

    public function loadCategorySelect()
    {
        return IncomingRequestCategoryModel::all();
    }

    public function loadStatusSelect()
    {
        return StatusModel::where('status_type', 'incoming request')
            ->whereNot('status_name', 'forwarded')
            ->get();
    }

    public function loadDivisionSelect()
    {
        return DivisionModel::whereNot('division_name', 'Admin')->get();
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
                $incoming_request->category_id = $this->category_id;
                $incoming_request->date_and_time = $this->date_and_time;
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
                    'category_id',
                    'date_and_time',
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
                $incoming_request->category_id = $this->category_id;
                $incoming_request->date_and_time = $this->date_and_time;
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
                $incoming_request->remarks = $this->remarks;
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

    public function viewIncomingRequest($incoming_request_id)
    {
        try {
            $this->incoming_request_id = $incoming_request_id;

            $incoming_request = IncomingRequestModel::withTrashed()->findOrFail($incoming_request_id);
            $this->status_id = $incoming_request->status->status_name;
            $this->incoming_request_no = $incoming_request->incoming_request_no;
            $this->office_or_barangay_or_organization_name = $incoming_request->office_or_barangay_or_organization_name;
            $this->date_requested = $incoming_request->formatted_date_requested;
            $this->category_id = $incoming_request->category->incoming_request_category_name;
            $this->date_and_time = $incoming_request->formatted_date_and_time;
            $this->contact_person_name = $incoming_request->contact_person_name;
            $this->contact_person_number = $incoming_request->contact_person_number;
            $this->description = $incoming_request->description;
            $this->status_id = $incoming_request->status->status_name;
            $this->remarks = $incoming_request->remarks;

            if ($incoming_request->file_id) {
                $this->preview_file_id = []; // unset it first

                foreach (json_decode($incoming_request->file_id) as $item) {
                    $files = FilesModel::findOrFail($item);
                    $this->preview_file_id[] = $files;
                }
            }

            $this->dispatch('show-viewIncomingRequestModal');
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
            $divisionMap = DivisionModel::withTrashed()->pluck('division_name', 'id');

            $this->document_history = Activity::where('subject_type', IncomingRequestModel::class)
                ->where('subject_id', $incoming_request_id)
                ->where('log_name', 'incoming_request')
                ->whereNotNull('properties->attributes->status_id') //* View logs with changes in status_id ONLY
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) use ($statusMap, $divisionMap) {
                    // $oldStatusId = $item->properties['old']['status_id'] ?? null; //* OLD attributes
                    $newStatusId = $item->properties['attributes']['status_id'] ?? null;
                    $division = $item->properties['attributes']['forwarded_to_division_id'] ?? null;

                    return [
                        // 'incoming_request_no' => $item->subject->incoming_request_no ?? 'N/A',
                        'updated_at' => Carbon::parse($item->updated_at)->format('M d Y g:i A'),
                        'status' => $newStatusId ? $statusMap[$newStatusId] ?? 'Unknown Status' : 'N/A', //* UPDATED attributes
                        'forwarded_to_division' => $divisionMap[$division] ?? 'N/A',
                        'updated_by' => $item->causer ? $item->causer->name : 'System'
                    ];
                });

            $this->dispatch('show-documentHistoryModal');
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
    }

    public function forwardToDivision()
    {
        $this->validate([
            'division_id' => 'required'
        ], [], [
            'division_id' => 'division'
        ]);

        try {
            DB::transaction(function () {
                $incoming_request = IncomingRequestModel::findOrFail($this->incoming_request_id);
                $incoming_request->forwarded_to_division_id = $this->division_id;
                $incoming_request->status_id = '3'; // FORWARDED
                $incoming_request->save();
            });

            $this->clear();
            $this->dispatch('hide-forwardToDivisionModal');
            $this->dispatch('success', message: 'Incoming Request forwarded successfully.');
        } catch (\Throwable $th) {
            throw $th;
            $this->dispatch('error');
        }
    }
}
