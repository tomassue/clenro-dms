<?php

namespace App\Livewire\Incoming;

use App\Livewire\Components\ForwardToDivisionModal;
use App\Livewire\Settings\IncomingDocumentCategory;
use App\Models\CategoryModel;
use App\Models\DivisionModel;
use App\Models\FilesModel;
use App\Models\ForwardedIncomingDocumentsModel;
use App\Models\IncomingDocumentCategoryModel;
use App\Models\IncomingDocumentModel;
use App\Models\IncomingRequestCategoryModel;
use App\Models\StatusModel;
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

#[Title('Incoming Documents')]
class Documents extends Component
{
    use WithPagination, WithFileUploads;

    public $editMode;
    public $search,
        $filter_status;
    public $incoming_document_id;
    public $category_id,
        $info,
        $file_id = [],
        $date,
        $status_id,
        $forwarded_to_division_id,
        $remarks;
    public $preview_file_id = [];
    public $document_history = [];

    public function mount()
    {
        $user = auth()->user();

        if (!$user->can('read incoming documents')) {
            abort(403, 'Unauthorized');
        }
    }

    public function rules()
    {
        $rules = [
            'category_id' => 'required',
            'date' => 'required'
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

    #[On('refresh-incoming-documents')]
    public function refreshIncomingDocuments()
    {
        $this->loadIncomingDocuments();
    }

    public function render()
    {
        return view(
            'livewire.incoming.documents',
            [
                'incoming_documents' => $this->loadIncomingDocuments(),
                'recent_forwarded_incoming_documents' => $this->loadRecentForwardedIncomingDocuments(),
                'status_select' => $this->loadStatusSelect(),
                'incoming_document_category_select' => $this->loadIncomingDocumentCategorySelect(),
                'division_select' => $this->loadDivisionSelect()
            ]
        );
    }

    public function loadIncomingDocuments()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;

        // return IncomingDocumentModel::query()
        //     ->when($this->search, function ($query) {
        //         $query->where('category_id', 'like', '%' . $this->search . '%')
        //             ->orWhere('info', 'like', '%' . $this->search . '%');
        //     })
        //     ->when($this->filter_status, function ($query) {
        //         $query->where('status_id', 'like', '%' . $this->filter_status . '%');
        //     })
        //     ->when(!is_null($user_division_id) && $user_division_id != "1" && $user_division_id !== "", function ($query) use ($user_division_id) {
        //         $query->where('forwarded_to_division_id', $user_division_id);
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);

        //* We will remove the forwarded_division_id and create a table tbl_forwarded_incoming_documents to better track division's interaction to the forwarded document. The backed-up database 3-9-2025 works well with the commented query

        return IncomingDocumentModel::query()
            ->when($this->search, function ($query) {
                $query->where('category_id', 'like', "{$this->search}")
                    ->orWhere('info', 'like', "{$this->search}");
            })
            ->when($this->filter_status, function ($query) {
                $query->where('status_id', 'like', "{$this->filter_status}");
            })
            ->when(!is_null($user_division_id) && $user_division_id != "1" && $user_division_id !== "", function ($query) use ($user_division_id) {
                $query->whereHas('forwardedDivisions', function ($subQuery) use ($user_division_id) {
                    $subQuery->where('division_id', $user_division_id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function loadRecentForwardedIncomingDocuments()
    {
        return IncomingDocumentModel::query()
            ->with('forwardedDivisions.division') // Load division along with forwardedDivisions
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function loadStatusSelect()
    {
        return StatusModel::where('status_type', 'incoming document')
            ->whereNot('status_name', 'forwarded')
            ->when(!is_null(Auth::user()->division_id) && Auth::user()->division_id != "1" && Auth::user()->division_id !== "", function ($query) {
                $query->whereNot('status_name', 'completed');
            })
            ->get();
    }

    public function loadIncomingDocumentCategorySelect()
    {
        return IncomingDocumentCategoryModel::all();
    }

    public function loadDivisionSelect()
    {
        return DivisionModel::whereNot('division_name', 'Admin')->get();
    }

    public function createIncomingDocument()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $incoming_document = new IncomingDocumentModel();
                $incoming_document->category_id = $this->category_id;
                $incoming_document->info = $this->info;

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
                $incoming_document->file_id = json_encode($file_id ?? []);

                $incoming_document->date = $this->date;
                $incoming_document->status_id = '7'; //* PENDING
                $incoming_document->remarks = $this->remarks;
                $incoming_document->save();
            });

            $this->clear();
            $this->dispatch('hide-incomingDocumentModal');
            $this->dispatch('success', message: 'Incoming document created successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function readIncomingDocument($incoming_document_id)
    {
        try {
            $this->editMode = true;

            $this->incoming_document_id = $incoming_document_id;

            $incoming_document = IncomingDocumentModel::withTrashed()->findOrFail($incoming_document_id);

            //* 1. Everytime users other than the superadmin and the admin, if they open the document, it will be marked as opened
            if (Auth::user()->division_id != 1 && !empty(Auth::user()->division_id) && $incoming_document->status->status_name == 'forwarded') {

                $forwarded_incoming_document = ForwardedIncomingDocumentsModel::where('incoming_document_id', $incoming_document_id)
                    ->where('division_id', Auth::user()->division_id)
                    ->first();

                if ($forwarded_incoming_document) {
                    $forwarded_incoming_document->is_opened = true; // Received
                    $forwarded_incoming_document->save();
                }

                //* 2. Then we check if all incoming documents are opened, we will update the status to received.
                $this->checkIncomingDocumentIfAllOpened($incoming_document_id);
            }

            $this->fill(
                $incoming_document->only(
                    'category_id',
                    'info',
                    'date',
                    'status_id',
                    'remarks'
                )
            );

            if ($incoming_document->file_id) {
                $this->preview_file_id = []; // unset it first

                foreach (json_decode($incoming_document->file_id) as $item) {
                    $files = FilesModel::findOrFail($item);
                    $this->preview_file_id[] = $files;
                }
            }

            $this->dispatch('show-incomingDocumentModal');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    private function checkIncomingDocumentIfAllOpened($incoming_document_id)
    {
        try {
            $totalForwarded = ForwardedIncomingDocumentsModel::where('incoming_document_id', $incoming_document_id)->count();

            $totalOpened = ForwardedIncomingDocumentsModel::where('incoming_document_id', $incoming_document_id)
                ->where('is_opened', true)
                ->count();

            if ($totalForwarded == $totalOpened) {
                $incoming_document = IncomingDocumentModel::findOrFail($incoming_document_id);
                $incoming_document->status_id = '15'; // Received
                $incoming_document->save();
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

    public function updateIncomingDocument()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            $incoming_document = IncomingDocumentModel::findOrFail($this->incoming_document_id);

            DB::transaction(function () use ($incoming_document) {
                $incoming_document->category_id = $this->category_id;
                $incoming_document->info = $this->info;

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
                    $existing_file_id = json_decode($incoming_document->file_id, true) ?? [];
                    $updated_file_id = array_unique(array_merge($existing_file_id, $file_id));
                    $incoming_document->file_id = json_encode($updated_file_id);
                }

                $incoming_document->date = $this->date;

                // Users other than the admin shouldn't be able to change the status
                if (Auth::user()->division_id == 1 || empty(Auth::user()->division_id)) {
                    $incoming_document->status_id = $this->status_id;
                } else {
                    if ($incoming_document->status_id == '15') { // Received
                        $incoming_document->status_id = "7"; // Processed
                    }
                }

                $incoming_document->remarks = $this->remarks;
                $incoming_document->save();
            });

            $this->clear();
            $this->dispatch('hide-incomingDocumentModal');
            $this->dispatch('success', message: 'Incoming document updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readDocumentHistory($incoming_document_id)
    {
        try {
            // // Fetch all statuses in a key-value pair: [status_id => status_name]
            // $statusMap = StatusModel::withTrashed()->pluck('status_name', 'id');
            // $divisionMap = DivisionModel::withTrashed()->pluck('division_name', 'id');

            // $this->document_history = Activity::whereIn('subject_type', [IncomingDocumentModel::class, ForwardedIncomingDocumentsModel::class])
            //     ->whereIn('log_name', ['incoming document', 'forwarded incoming document'])
            //     ->where('subject_id', $incoming_document_id)

            //     /**
            //      ** Here, I want to exclude created forwarded incoming document.
            //      ** I can't directly use ->where('log_name', 'forwarded incoming document')->whereNot('event', 'created') because it it would only filter forwarded incoming document logs and completely exclude incoming document logs.
            //      ** So, we need to include all incoming document and include forwarded incoming document logs, but exclude created forwarded incoming document logs.
            //      */
            //     ->where(function ($query) {
            //         $query->where('log_name', '!=', 'forwarded incoming document')
            //             ->orWhere(function ($subQuery) {
            //                 $subQuery->where('log_name', 'forwarded incoming document')
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
            //         $attributes = $item->properties['attributes'] ?? [];
            //         // $oldStatusId = $item->properties['old']['status_id'] ?? null;
            //         $newStatusId = $item->properties['attributes']['status_id'] ?? null;
            //         $division = $item->properties['attributes']['forwarded_to_division_id'] ?? null;

            //         return [
            //             // 'incoming_request_no' => $item->subject->incoming_request_no ?? 'N/A',
            //             'updated_at' => Carbon::parse($item->updated_at)->format('M d Y g:i A'),
            //             'status' => $newStatusId ? $statusMap[$newStatusId] ?? 'Unknown Status' : (isset($attributes['is_opened']) ? ((bool) $attributes['is_opened'] ? 'Opened' : '-') : '-'), //* UPDATED attributes
            //             'forwarded_to_division' => $divisionMap[$division] ?? 'N/A',
            //             'updated_by' => $item->causer ? $item->causer->name : 'System',
            //             'subject_type' => $item->subject_type,
            //             'is_opened' => isset($attributes['is_opened']) ? (bool) $attributes['is_opened'] : '-'
            //         ];
            //     });

            $this->document_history = Activity::whereIn('subject_type', [IncomingDocumentModel::class, ForwardedIncomingDocumentsModel::class])
                ->where('subject_id', $incoming_document_id)
                ->whereIn('log_name', ['incoming document', 'forwarded incoming document'])
                ->where(function ($query) {
                    $query->where(function ($subQuery) {
                        // Include incoming request logs that are NOT 'created' events
                        $subQuery->where('log_name', 'incoming document')
                            ->whereNot('event', 'created');
                    })
                        ->orWhere(function ($subQuery) {
                            // Include forwarded incoming request logs that are NOT 'created' events
                            $subQuery->where('log_name', 'forwarded incoming document')
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
                                    'category_id' => 'Category',
                                    'status_id' => 'Status',
                                    'file_id' => 'Files',
                                    'forwarded_to_division_id' => 'Forwarded To Division',
                                    // Add other field mappings here as needed
                                    // 'another_field' => 'Friendly Name',
                                    default => ucfirst(str_replace('_', ' ', $key))
                                };

                                // Format date only fields
                                if (in_array($key, ['date', 'deleted_at'])) {
                                    $oldValue = $oldValue !== '-' ? Carbon::parse($oldValue)->format('M d, Y') : '-';
                                    $newValue = $newValue !== '-' ? Carbon::parse($newValue)->format('M d, Y') : '-';
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
                                    $oldValue = $oldValue !== '-' ? IncomingDocumentCategory::find($oldValue)?->incoming_document_category_name : '-';
                                    $newValue = $newValue !== '-' ? IncomingDocumentCategory::find($newValue)?->incoming_document_category_name : '-';
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
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function viewIncomingDocument($incoming_document_id)
    {
        try {
            $incoming_document = IncomingDocumentModel::findOrFail($incoming_document_id);
            $this->fill(
                $incoming_document->only(
                    'info',
                    'remarks'
                )
            );
            $this->category_id = $incoming_document->category->incoming_document_category_name;
            $this->date = $incoming_document->formatted_date;
            $this->status_id = $incoming_document->status->status_name;

            if ($incoming_document->file_id) {
                $this->preview_file_id = []; // unset it first

                foreach (json_decode($incoming_document->file_id) as $item) {
                    $files = FilesModel::findOrFail($item);
                    $this->preview_file_id[] = $files;
                }
            }

            $this->dispatch('show-viewIncomingDocumentModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function forwardToDivision($incoming_document_id)
    {
        // We dispatch an event to trigger the modal and with a parameter which is the id of the incoming document. The child component which is the forwarded-to-division-modal will listen to the event together with the parameter.
        $this->dispatch('show-forwardToDivisionModal', id: $incoming_document_id);
    }
}
