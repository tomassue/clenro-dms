<?php

namespace App\Livewire\Incoming;

use App\Models\CategoryModel;
use App\Models\DivisionModel;
use App\Models\FilesModel;
use App\Models\ForwardedIncomingRequestModel;
use App\Models\IncomingRequestCategoryModel;
use App\Models\IncomingRequestModel;
use App\Models\StatusModel;
use App\Models\SubCategoryModel;
use App\Models\VenueModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\On;
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
    public $division_name,
        $division_id; //* Forwarded to division id
    public $preview_file_id = [];
    public $document_history = [];

    public function mount()
    {
        $user = auth()->user();

        if (!$user->can('read incoming requests')) {
            abort(403, 'Unauthorized');
        }
    }

    public function rules()
    {
        $rules = [
            'incoming_request_no' => 'required',
            'office_or_barangay_or_organization_name' => 'required',
            'category_id' => 'required',
            'date_and_time' => 'required',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required|size:11',
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

    #[On('refresh-incoming-requests')]
    public function refreshIncomingRequest()
    {
        $this->loadIncomingRequests();
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
        $user = auth()->user();
        $user_division_id = $user->division_id;

        return IncomingRequestModel::query()
            ->when($this->filter_status, function ($query) {
                $query->where('status_id', $this->filter_status);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('incoming_request_no', 'like', '%' . $this->search . '%')
                        ->orWhere('office_or_barangay_or_organization_name', 'like', '%' . $this->search . '%')
                        ->orWhere('date_requested', 'like', '%' . $this->search . '%')
                        ->orWhere('contact_person_name', 'like', '%' . $this->search . '%')
                        ->orWhere('contact_person_number', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('category', function ($subQuery) {
                        $subQuery->where('incoming_request_category_name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when(!is_null($user_division_id) && $user_division_id != "1" && $user_division_id !== "", function ($query) use ($user_division_id) {
                $query->whereHas('forwardedDivisions', function ($subQuery) use ($user_division_id) {
                    $subQuery->where('division_id', $user_division_id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function loadRecentForwardedIncomingRequests()
    {
        return IncomingRequestModel::query()
            ->with('forwardedDivisions.division') // Load division along with forwardedDivisions
            ->orderBy('created_at', 'desc')
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

            //* 1. Everytime users other than the superadmin and the admin, if they open the request, it will be marked as opened
            if (Auth::user()->division_id != 1 && !empty(Auth::user()->division_id) && $incoming_request->status->status_name == 'forwarded') {

                $forwarded_incoming_request = ForwardedIncomingRequestModel::where('incoming_request_id', $incoming_request_id)
                    ->where('division_id', Auth::user()->division_id)
                    ->first();

                if ($forwarded_incoming_request) {
                    $forwarded_incoming_request->is_opened = true; // Received
                    $forwarded_incoming_request->save();
                }

                //* 2. Then we check if all incoming requests are opened, we will update the status to received.
                $this->checkIncomingRequestIfAllOpened($incoming_request_id);
            }

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
                    'status_id',
                    'remarks'
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
            throw $th;
            $this->dispatch('error');
        }
    }

    public function checkIncomingRequestIfAllOpened($incoming_request_id)
    {
        try {
            $totalForwarded = ForwardedIncomingRequestModel::where('incoming_request_id', $incoming_request_id)->count();

            $totalOpened = ForwardedIncomingRequestModel::where('incoming_request_id', $incoming_request_id)
                ->where('is_opened', true)
                ->count();

            if ($totalForwarded == $totalOpened) {
                $incoming_request = IncomingRequestModel::findOrFail($incoming_request_id);
                $incoming_request->status_id = '15'; // Received
                $incoming_request->save();
            }
        } catch (\Throwable $th) {
            //throw $th;
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

                // Users other than the admin shouldn't be able to change the status
                if (Auth::user()->division_id == 1 || empty(Auth::user()->division_id)) {
                    $incoming_request->status_id = $this->status_id;
                } else {
                    if ($incoming_request->status_id == '15') { // Received
                        $incoming_request->status_id = "2"; // Processed
                    }
                }

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
            // // Fetch all statuses in a key-value pair: [status_id => status_name]
            // $statusMap = StatusModel::withTrashed()->pluck('status_name', 'id');
            // $divisionMap = DivisionModel::withTrashed()->pluck('division_name', 'id');

            // $this->document_history = Activity::whereIn('subject_type', [IncomingRequestModel::class, ForwardedIncomingRequestModel::class])
            //     ->whereIn('log_name', ['incoming request', 'forwarded incoming request'])
            //     ->where('subject_id', $incoming_request_id)

            //     /**
            //      ** Here, I want to exclude created forwarded incoming request.
            //      ** I can't directly use ->where('log_name', 'forwarded incoming request')->whereNot('event', 'created') because it it would only filter forwarded incoming request logs and completely exclude incoming request logs.
            //      ** So, we need to include all incoming request and include forwarded incoming request logs, but exclude created forwarded incoming request logs.
            //      */
            //     ->where(function ($query) {
            //         $query->where('log_name', '!=', 'forwarded incoming request') // or ->whereNot('log_name', 'forwarded incoming request')
            //             ->orWhere(function ($subQuery) {
            //                 $subQuery->where('log_name', 'forwarded incoming request')
            //                     ->whereNot('event', 'created');
            //             });
            //     })
            //     ->where(function ($query) {
            //         $query->whereNotNull('properties->attributes->status_id')
            //             ->orWhereNotNull('properties->attributes->is_opened'); //* Show only records with either status_id OR is_opened
            //     })
            //     ->orderBy('created_at', 'desc')
            //     ->get()
            //     ->map(function ($item) use ($statusMap, $divisionMap) {
            //         // $oldStatusId = $item->properties['old']['status_id'] ?? null;
            //         $newStatusId = $item->properties['attributes']['status_id'] ?? null;
            //         $attributes = $item->properties['attributes'] ?? [];

            //         return [
            //             // 'incoming_request_no' => $item->subject->incoming_request_no ?? 'N/A',
            //             'updated_at' => Carbon::parse($item->updated_at)->format('M d Y g:i A'),
            //             'status' => $newStatusId ? $statusMap[$newStatusId] ?? 'Unknown Status' : (isset($attributes['is_opened']) ? ((bool) $attributes['is_opened'] ? 'Opened' : '-') : '-'), //* UPDATED attributes
            //             'updated_by' => $item->causer ? $item->causer->name : 'System',
            //             'subject_type' => $item->subject_type,
            //             'is_opened' => isset($attributes['is_opened']) ? (bool) $attributes['is_opened'] : '-'
            //         ];
            //     });

            $this->document_history = Activity::whereIn('subject_type', [IncomingRequestModel::class, ForwardedIncomingRequestModel::class])
                ->where('subject_id', $incoming_request_id)
                ->whereIn('log_name', ['incoming request', 'forwarded incoming request'])
                ->where(function ($query) {
                    $query->where(function ($subQuery) {
                        // Include incoming request logs that are NOT 'created' events
                        $subQuery->where('log_name', 'incoming request')
                            ->whereNot('event', 'created');
                    })
                        ->orWhere(function ($subQuery) {
                            // Include forwarded incoming request logs that are NOT 'created' events
                            $subQuery->where('log_name', 'forwarded incoming request')
                                ->whereNot('event', 'created');
                        });
                })
                ->latest()
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'description' => $activity->description,
                        'causer' => $activity->causer?->name ?? 'System',
                        'created_at' => Carbon::parse($activity->created_at)->format('M d, Y h:i A'),
                        'changes' => collect($activity->properties['attributes'] ?? [])
                            ->except(['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'])
                            ->map(function ($newValue, $key) use ($activity) {
                                $oldValue = $activity->properties['old'][$key] ?? '-';

                                // Custom field name mapping
                                $fieldName = match ($key) {
                                    'incoming_request_no' => 'Incoming Request No.',
                                    'office_or_barangay_or_organization_name' => 'Office/Barangay/Organization Name',
                                    'date_requested' => 'Date Requested',
                                    'category_id' => 'Category',
                                    'date_and_time' => 'Date and Time',
                                    'contact_person_name' => 'Contact Person Name',
                                    'contact_person_number' => 'Contact Person Number',
                                    'status_id' => 'Status',
                                    'file_id' => 'Files',
                                    'forwarded_to_division_id' => 'Forwarded To Division',
                                    // Add other field mappings here as needed
                                    // 'another_field' => 'Friendly Name',
                                    default => ucfirst(str_replace('_', ' ', $key))
                                };

                                // Format date only fields
                                if (in_array($key, ['date_requested', 'deleted_at'])) {
                                    $oldValue = $oldValue !== '-' ? Carbon::parse($oldValue)->format('M d, Y') : '-';
                                    $newValue = $newValue !== '-' ? Carbon::parse($newValue)->format('M d, Y') : '-';
                                }

                                // Format datetime only fields
                                if (in_array($key, ['date_and_time'])) {
                                    $oldValue = $oldValue !== '-' ? Carbon::parse($oldValue)->format('M d, Y h:i A') : '-';
                                    $newValue = $newValue !== '-' ? Carbon::parse($newValue)->format('M d, Y h:i A') : '-';
                                }

                                // Convert array values to a string (e.g., file IDs to filenames)
                                if ($key === 'file_id') {
                                    // Ensure values are decoded from JSON if stored as a string
                                    $oldValue = is_string($oldValue) ? json_decode($oldValue, true) : $oldValue;
                                    $newValue = is_string($newValue) ? json_decode($newValue, true) : $newValue;

                                    if (is_array($oldValue)) {
                                        $oldValue = FilesModel::whereIn('id', $oldValue)->pluck('file_name')->toArray();
                                        $oldValue = !empty($oldValue) ? implode(', ', $oldValue) : '-';
                                    }

                                    if (is_array($newValue)) {
                                        $newValue = FilesModel::whereIn('id', $newValue)->pluck('file_name')->toArray();
                                        $newValue = !empty($newValue) ? implode(', ', $newValue) : '-';
                                    }
                                }

                                // Format status values
                                if ($key === 'status_id') {
                                    $oldValue = $oldValue !== '-' ? StatusModel::find($oldValue)?->status_name : '-';
                                    $newValue = $newValue !== '-' ? StatusModel::find($newValue)?->status_name : '-';
                                }

                                // Format category values
                                if ($key === 'category_id') {
                                    $oldValue = $oldValue !== '-' ? IncomingRequestCategoryModel::find($oldValue)?->incoming_request_category_name : '-';
                                    $newValue = $newValue !== '-' ? IncomingRequestCategoryModel::find($newValue)?->incoming_request_category_name : '-';
                                }

                                //Format is_opened values from boolean to Yes/No
                                if ($key === 'is_opened') {
                                    $oldValue = $oldValue == '1' ? 'Yes' : 'No';
                                    $newValue = $newValue == '1' ? 'Yes' : 'No';
                                }

                                return [
                                    'field' => $fieldName, // Use the custom field name
                                    'old' => $oldValue,
                                    'new' => $newValue,
                                ];
                            })
                            ->values()
                            ->toArray()
                    ];
                });

            $this->dispatch('show-documentHistoryModal');
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
    }

    public function forwardToDivision($incoming_request_id)
    {
        // We dispatch an event to trigger the modal and with a parameter which is the id of the incoming request.
        //The child component which is the forwarded-to-division-modal will listen to the event together with the parameter.
        $this->dispatch('show-forwardToDivisionModal', id: $incoming_request_id);
    }
}
