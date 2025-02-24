<?php

namespace App\Livewire\Incoming;

use App\Models\CategoryModel;
use App\Models\DivisionModel;
use App\Models\FilesModel;
use App\Models\IncomingDocumentCategoryModel;
use App\Models\IncomingDocumentModel;
use App\Models\IncomingRequestCategoryModel;
use App\Models\StatusModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
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
    public $division_id; //* Forwarded to division id
    public $preview_file_id = [];
    public $document_history = [];

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

    public function render()
    {
        return view(
            'livewire.incoming.documents',
            [
                'incoming_documents' => $this->loadIncomingDocuments(),
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

        return IncomingDocumentModel::query()
            ->when($this->search, function ($query) {
                $query->where('category_id', 'like', '%' . $this->search . '%')
                    ->orWhere('info', 'like', '%' . $this->search . '%');
            })
            ->when($this->filter_status, function ($query) {
                $query->where('status_id', 'like', '%' . $this->filter_status . '%');
            })
            ->when(!is_null($user_division_id) && $user_division_id != "1", function ($query) use ($user_division_id) {
                $query->where('forwarded_to_division_id', $user_division_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function loadStatusSelect()
    {
        return StatusModel::where('status_type', 'incoming document')
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
            throw $th;
            $this->dispatch('error');
        }
    }

    public function readIncomingDocument($incoming_document_id)
    {
        try {
            $this->editMode = true;

            $this->incoming_document_id = $incoming_document_id;

            $incoming_document = IncomingDocumentModel::withTrashed()->findOrFail($incoming_document_id);
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
                $incoming_document->status_id = $this->status_id;
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
            // Fetch all statuses in a key-value pair: [status_id => status_name]
            $statusMap = StatusModel::withTrashed()->pluck('status_name', 'id');
            $divisionMap = DivisionModel::withTrashed()->pluck('division_name', 'id');

            $this->document_history = Activity::where('subject_type', IncomingDocumentModel::class)
                ->where('subject_id', $incoming_document_id)
                ->where('log_name', 'incoming_document')
                ->whereNotNull('properties->attributes->status_id') //* Logs with changes in status_id ONLY
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) use ($statusMap, $divisionMap) {
                    $oldStatusId = $item->properties['old']['status_id'] ?? null;
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
            // throw $th;
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
                $incoming_request = IncomingDocumentModel::findOrFail($this->incoming_document_id);
                $incoming_request->forwarded_to_division_id = $this->division_id;
                $incoming_request->status_id = '8'; // FORWARDED
                $incoming_request->save();
            });

            $this->clear();
            $this->dispatch('hide-forwardToDivisionModal');
            $this->dispatch('success', message: 'Incoming Request forwarded successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }
}
