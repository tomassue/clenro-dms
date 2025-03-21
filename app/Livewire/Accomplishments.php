<?php

namespace App\Livewire;

use App\Models\AccomplishmentCategoryModel;
use App\Models\AccomplishmentModel;
use App\Models\FilesModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Title('Accomplishments')]
class Accomplishments extends Component
{
    use WithPagination, WithFileUploads;

    public $search,
        $date_range_filter;
    public $editMode;
    public $accomplishment_id;
    public $accomplishment_category_id,
        $date,
        $details,
        $no_of_participants,
        $file_id = [],
        $remarks;
    public $preview_file_id = [];
    public $outgoing_history = [];
    public $pdf;

    public function mount()
    {
        $user = auth()->user();

        if (!$user->can('read accomplishments')) {
            abort(403, 'Unauthorized');
        }
    }

    public function rules()
    {
        return [
            'accomplishment_category_id' => 'required',
            'date' => 'required',
            'details' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'accomplishment_category_id' => 'accomplishment category'
        ];
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('reset-files');
    }

    public function render()
    {
        return view(
            'livewire.accomplishments',
            [
                'accomplishments' => $this->loadAccomplishments(),
                'accomplishment_category_select' => $this->loadAccomplishmentCategorySelect()
            ]
        );
    }

    public function loadAccomplishments()
    {
        return AccomplishmentModel::query()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('details', 'like', '%' . $search . '%')
                        ->orWhereHas('accomplishment_category', function ($query) use ($search) {
                            $query->where('accomplishment_category_name', 'like', '%' . $search . '%');
                        })
                        ->orWhere('no_of_participants', 'like', '%' . $search . '%')
                        ->orWhere('date', 'like', '%' . $search . '%');
                });
            })
            ->when($this->date_range_filter, function ($query) {
                // Check if the filter contains a range (indicated by ' - ')
                if (str_contains($this->date_range_filter, ' - ')) {
                    // It's a range, split and filter between dates
                    $dates = explode(' - ', $this->date_range_filter);
                    if (count($dates) === 2) {
                        $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                        $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();

                        $query->whereBetween('date', [$startDate, $endDate]);
                    }
                } else {
                    // It's a single date, filter exact match
                    $singleDate = Carbon::createFromFormat('m/d/Y', trim($this->date_range_filter))->startOfDay();
                    $query->whereDate('date', $singleDate);
                }
            })
            ->when(!is_null(Auth::user()->division_id) && Auth::user()->division_id != "1" && Auth::user()->division_id !== "", function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->paginate(10);
    }

    public function loadAccomplishmentCategorySelect()
    {
        return AccomplishmentCategoryModel::all();
    }

    public function createAccomplishment()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $accomplishment = new AccomplishmentModel();
                $accomplishment->accomplishment_category_id = $this->accomplishment_category_id;
                $accomplishment->date = $this->date;
                $accomplishment->details = $this->details;
                $accomplishment->no_of_participants = $this->no_of_participants;
                $accomplishment->remarks = $this->remarks;

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

                $accomplishment->file_id = json_encode($file_id ?? []);
                $accomplishment->user_id = Auth::user()->id;
                $accomplishment->save();
            });

            $this->clear();
            $this->dispatch('hide-accomplishmentModal');
            $this->dispatch('success', message: 'Accomplishment created successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function readAccomplishment(AccomplishmentModel $accomplishment_id)
    {
        try {
            $this->editMode = true;

            $this->accomplishment_id = $accomplishment_id->id;

            $this->fill(
                $accomplishment_id->only(
                    'accomplishment_category_id',
                    'date',
                    'details',
                    'no_of_participants',
                    'remarks'
                )
            );

            if ($accomplishment_id->file_id) {
                $this->preview_file_id = []; // unset it first

                foreach (json_decode($accomplishment_id->file_id) as $item) {
                    $files = FilesModel::findOrFail($item);
                    $this->preview_file_id[] = $files;
                }
            }

            $this->dispatch('show-accomplishmentModal');
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

    public function updateAccomplishment()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $accomplishment = AccomplishmentModel::find($this->accomplishment_id);
                $accomplishment->accomplishment_category_id = $this->accomplishment_category_id;
                $accomplishment->date = $this->date;
                $accomplishment->details = $this->details;
                $accomplishment->no_of_participants = $this->no_of_participants;
                $accomplishment->remarks = $this->remarks;

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
                    $existing_file_id = json_decode($accomplishment->file_id, true) ?? [];
                    $updated_file_id = array_unique(array_merge($existing_file_id, $file_id));
                    $accomplishment->file_id = json_encode($updated_file_id);
                }

                $accomplishment->user_id = Auth::user()->id;

                $accomplishment->save();
            });

            $this->clear();
            $this->dispatch('hide-accomplishmentModal');
            $this->dispatch('success', message: 'Accomplishment updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readAccomplishmentHistory($outgoing_id)
    {
        try {
            $this->outgoing_history = Activity::where('subject_type', AccomplishmentModel::class)
                ->where('subject_id', $outgoing_id)
                ->where('log_name', 'accomplishment')
                ->whereNot('event', 'created')
                ->latest()
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'description' => $activity->description,
                        'causer' => $activity->causer?->name ?? 'System',
                        'created_at' => Carbon::parse($activity->created_at)->format('M d, Y h:i A'),
                        'changes' => collect($activity->properties['attributes'] ?? [])
                            ->except(['id', 'user_id', 'created_at', 'updated_at', 'deleted_at']) // Exclude timestamps
                            ->map(function ($newValue, $key) use ($activity) {
                                $oldValue = $activity->properties['old'][$key] ?? '-';

                                // Format date fields
                                if (in_array($key, ['date', 'deleted_at'])) {
                                    $oldValue = $oldValue !== '-' ? Carbon::parse($oldValue)->format('M d, Y') : '-';
                                    $newValue = $newValue !== '-' ? Carbon::parse($newValue)->format('M d, Y') : '-';
                                }

                                // Replace foreign keys with related names
                                if ($key === 'accomplishment_category_id') {
                                    $oldValue = $oldValue !== '-' ? AccomplishmentCategoryModel::find($oldValue)?->accomplishment_category_name : '-';
                                    $newValue = $newValue !== '-' ? AccomplishmentCategoryModel::find($newValue)?->accomplishment_category_name : '-';
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

                                return [
                                    'field' => ucfirst(str_replace('_', ' ', $key)), // Format key
                                    'old' => $oldValue,
                                    'new' => $newValue,
                                ];
                            })
                            ->values()
                            ->toArray()
                    ];
                });

            $this->dispatch('show-accomplishmentHistoryModal');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function previewPDF()
    {
        $accomplishments = $this->loadAccomplishments();

        $loadImage = fn($path) => base64_encode(File::get(public_path($path)));

        if ($this->date_range_filter) {
            $dateRange = explode(' - ', $this->date_range_filter);

            if (count($dateRange) === 2) {
                // If both start and end dates exist
                $formattedDateRange = Carbon::parse($dateRange[0])->format('M d, Y') . ' - ' . Carbon::parse($dateRange[1])->format('M d, Y');
            } else {
                // If only one date exists
                $formattedDateRange = Carbon::parse($dateRange[0])->format('M d, Y');
            }
        } else {
            $formattedDateRange = '-';
        }

        $data = [
            'accomplishments' => $accomplishments,
            'cdo_full' => $loadImage('images/compressed_cdofull.png'),
            'cdo_seal' => $loadImage('images/cdo-seal.png'),
            'rise_logo' => $loadImage('images/compressed_rise.png'),
            'watermark' => $loadImage('images/cdo-seal.png'),
            'date' => $formattedDateRange
        ];

        $pdfData = PDF::loadView('livewire.pdf.accomplishments_pdf', $data)
            ->setPaper('A4', 'portrait');

        $this->pdf = 'data:application/pdf;base64,' . base64_encode($pdfData->output());

        $this->dispatch('show-pdfModal');
    }
}
