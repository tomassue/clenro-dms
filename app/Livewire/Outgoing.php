<?php

namespace App\Livewire;

use App\Models\FilesModel;
use App\Models\OutgoingModel;
use App\Models\OutgoingOthersModel;
use App\Models\OutgoingPayrollModel;
use App\Models\OutgoingProcurementModel;
use App\Models\OutgoingRisModel;
use App\Models\OutgoingVoucherModel;
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

#[Title('Outgoing')]
class Outgoing extends Component
{
    use WithPagination, WithFileUploads;

    public $editMode,
        $type,
        $preview_file_id = [],
        $document_history = [];
    public $search,
        $filter_status;
    public $outgoing_id;
    public $date,
        $details,
        $destination,
        $person_responsible,
        $file_id = [],
        $status_id,
        $document_name,
        $payroll_type,
        $pr_no,
        $po_no,
        $ppmp_code,
        $voucher_name;

    public function rules()
    {
        $commonRules = [
            'date' => 'required',
            'details' => 'required',
            'destination' => 'required',
            'person_responsible' => 'required'
        ];

        switch ($this->type) {
            case 'voucher':
                return array_merge([
                    'voucher_name' => 'required'
                ], $commonRules);
            case 'ris':
                return array_merge([
                    'document_name' => 'required',
                    'ppmp_code' => 'required'
                ], $commonRules);
            case 'procurement':
                return array_merge([
                    'pr_no' => 'required',
                    'po_no' => 'required',
                ], $commonRules);
            case 'payroll':
                return array_merge([
                    'payroll_type' => 'required',
                ], $commonRules);
            case 'other':
                return array_merge([
                    'document_name' => 'required',
                ], $commonRules);
            default:
                // throw new \InvalidArgumentException("Invalid type: {$this->type}");
                $this->dispatch('error');
        }
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
            'livewire.outgoing',
            [
                'outgoing' => $this->loadOutgoing(),
                'status_select' => $this->loadStatusSelect()
            ]
        );
    }

    public function loadOutgoing()
    {
        return OutgoingModel::query()
            ->when($this->search, function ($query) {
                $query->where('category_id', 'like', '%' . $this->search . '%')
                    ->orWhere('info', 'like', '%' . $this->search . '%');
            })
            ->when($this->filter_status, function ($query) {
                $query->where('status_id', 'like', '%' . $this->filter_status . '%');
            })
            ->paginate(10);
    }

    public function loadStatusSelect()
    {
        return StatusModel::where('status_type', 'outgoing')
            ->get();
    }

    public function createOutgoing()
    {
        switch ($this->type) {
            case 'voucher':
                $this->validate();

                DB::transaction(function () {
                    // Create the OutgoingVoucherModel instance
                    $outgoing_voucher = new OutgoingVoucherModel();
                    $outgoing_voucher->voucher_name = $this->voucher_name;
                    $outgoing_voucher->save(); // Create the OutgoingVoucherModel instance

                    // Create the OutgoingModel instance
                    $outgoing = new OutgoingModel();
                    $outgoing->date = $this->date;
                    $outgoing->details = $this->details;
                    $outgoing->destination = $this->destination;
                    $outgoing->person_responsible = $this->person_responsible;

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
                    $outgoing->file_id = json_encode($file_id ?? []);
                    $outgoing->status_id = '11';

                    // Associate the OutgoingModel with the OutgoingVoucherModel
                    $outgoing_voucher->outgoing()->save($outgoing);
                });

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('success', message: 'Outgoing created successfully.');
                break;
            case 'ris':
                $this->validate();

                DB::transaction(function () {
                    $outgoing_ris = new OutgoingRisModel();
                    $outgoing_ris->document_name = $this->document_name;
                    $outgoing_ris->ppmp_code = $this->ppmp_code;
                    $outgoing_ris->save();

                    $outgoing = new OutgoingModel();
                    $outgoing->date = $this->date;
                    $outgoing->details = $this->details;
                    $outgoing->destination = $this->destination;
                    $outgoing->person_responsible = $this->person_responsible;

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
                    $outgoing->file_id = json_encode($file_id ?? []);
                    $outgoing->status_id = '11';

                    $outgoing_ris->outgoing()->save($outgoing);
                });

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('success', message: 'Outgoing created successfully.');
                break;
            case 'procurement':
                $this->validate();

                DB::transaction(function () {
                    $outgoing_procurement = new OutgoingProcurementModel();
                    $outgoing_procurement->pr_no = $this->pr_no;
                    $outgoing_procurement->po_no = $this->po_no;
                    $outgoing_procurement->save();

                    $outgoing = new OutgoingModel();
                    $outgoing->date = $this->date;
                    $outgoing->details = $this->details;
                    $outgoing->destination = $this->destination;
                    $outgoing->person_responsible = $this->person_responsible;

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
                    $outgoing->file_id = json_encode($file_id ?? []);
                    $outgoing->status_id = '11';

                    $outgoing_procurement->outgoing()->save($outgoing);
                });

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('success', message: 'Outgoing created successfully.');
                break;
            case 'payroll':
                $this->validate();

                DB::transaction(function () {
                    $outgoing_payroll = new OutgoingPayrollModel();
                    $outgoing_payroll->payroll_type = $this->payroll_type;
                    $outgoing_payroll->save();

                    $outgoing = new OutgoingModel();
                    $outgoing->date = $this->date;
                    $outgoing->details = $this->details;
                    $outgoing->destination = $this->destination;
                    $outgoing->person_responsible = $this->person_responsible;

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
                    $outgoing->file_id = json_encode($file_id ?? []);
                    $outgoing->status_id = '11';

                    $outgoing_payroll->outgoing()->save($outgoing);
                });

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('success', message: 'Outgoing created successfully.');
                break;
            case 'other':
                $this->validate();

                DB::transaction(function () {
                    $outgoing_other = new OutgoingOthersModel();
                    $outgoing_other->document_name = $this->document_name;
                    $outgoing_other->save();

                    $outgoing = new OutgoingModel();
                    $outgoing->date = $this->date;
                    $outgoing->details = $this->details;
                    $outgoing->destination = $this->destination;
                    $outgoing->person_responsible = $this->person_responsible;

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
                    $outgoing->file_id = json_encode($file_id ?? []);
                    $outgoing->status_id = '11';

                    $outgoing_other->outgoing()->save($outgoing);

                    $this->clear();
                    $this->dispatch('hide-outgoingModal');
                    $this->dispatch('success', message: 'Outgoing created successfully.');
                });
                break;
            default:
                // throw new \InvalidArgumentException("Invalid type: {$this->type}");
                $this->dispatch('error');
        }
    }

    public function readOutgoing(OutgoingModel $outgoing_id)
    {
        switch ($outgoing_id->type_type) {
            case 'App\Models\OutgoingVoucherModel':
                try {
                    $this->editMode = true;

                    $this->type = 'voucher';

                    $this->outgoing_id = $outgoing_id;

                    $this->fill(
                        $this->outgoing_id->only(
                            'date',
                            'details',
                            'destination',
                            'person_responsible',
                            'status_id'
                        ),
                    );

                    if ($this->outgoing_id->file_id) {
                        $this->preview_file_id = []; // unset it first

                        foreach (json_decode($this->outgoing_id->file_id) as $item) {
                            $files = FilesModel::findOrFail($item);
                            $this->preview_file_id[] = $files;
                        }
                    }

                    $outgoing_voucher = OutgoingVoucherModel::findOrFail($outgoing_id->type_id);

                    $this->fill(
                        $outgoing_voucher->only(
                            'voucher_name'
                        ),
                    );

                    $this->dispatch('show-outgoingModal');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->dispatch('error');
                }
                break;
            case 'App\Models\OutgoingRisModel':
                try {
                    $this->editMode = true;

                    $this->type = 'ris';

                    $this->outgoing_id = $outgoing_id;

                    $this->fill(
                        $this->outgoing_id->only(
                            'date',
                            'details',
                            'destination',
                            'person_responsible',
                            'status_id'
                        ),
                    );

                    if ($this->outgoing_id->file_id) {
                        $this->preview_file_id = []; // unset it first

                        foreach (json_decode($this->outgoing_id->file_id) as $item) {
                            $files = FilesModel::findOrFail($item);
                            $this->preview_file_id[] = $files;
                        }
                    }

                    $outgoing_ris = OutgoingRisModel::findOrFail($outgoing_id->type_id);

                    $this->fill(
                        $outgoing_ris->only(
                            'document_name',
                            'ppmp_code'
                        ),
                    );

                    $this->dispatch('show-outgoingModal');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->dispatch('error');
                }
                break;
            case 'App\Models\OutgoingProcurementModel':
                try {
                    $this->editMode = true;

                    $this->type = 'procurement';

                    $this->outgoing_id = $outgoing_id;

                    $this->fill(
                        $this->outgoing_id->only(
                            'date',
                            'details',
                            'destination',
                            'person_responsible',
                            'status_id'
                        ),
                    );

                    if ($this->outgoing_id->file_id) {
                        $this->preview_file_id = []; // unset it first

                        foreach (json_decode($this->outgoing_id->file_id) as $item) {
                            $files = FilesModel::findOrFail($item);
                            $this->preview_file_id[] = $files;
                        }
                    }

                    $outgoing_procurement = OutgoingProcurementModel::findOrFail($outgoing_id->type_id);

                    $this->fill(
                        $outgoing_procurement->only(
                            'pr_no',
                            'po_no'
                        ),
                    );

                    $this->dispatch('show-outgoingModal');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->dispatch('error');
                }
                break;
            case 'App\Models\OutgoingPayrollModel':
                try {
                    $this->editMode = true;

                    $this->type = 'payroll';

                    $this->outgoing_id = $outgoing_id;

                    $this->fill(
                        $this->outgoing_id->only(
                            'date',
                            'details',
                            'destination',
                            'person_responsible',
                            'status_id'
                        ),
                    );

                    if ($this->outgoing_id->file_id) {
                        $this->preview_file_id = []; // unset it first

                        foreach (json_decode($this->outgoing_id->file_id) as $item) {
                            $files = FilesModel::findOrFail($item);
                            $this->preview_file_id[] = $files;
                        }
                    }

                    $outgoing_payroll = OutgoingPayrollModel::findOrFail($outgoing_id->type_id);

                    $this->fill(
                        $outgoing_payroll->only(
                            'payroll_type'
                        ),
                    );

                    $this->dispatch('show-outgoingModal');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->dispatch('error');
                }
                break;
            case 'App\Models\OutgoingOthersModel':
                try {
                    $this->editMode = true;

                    $this->type = 'other';

                    $this->outgoing_id = $outgoing_id;

                    $this->fill(
                        $this->outgoing_id->only(
                            'date',
                            'details',
                            'destination',
                            'person_responsible',
                            'status_id'
                        ),
                    );

                    if ($this->outgoing_id->file_id) {
                        $this->preview_file_id = []; // unset it first

                        foreach (json_decode($this->outgoing_id->file_id) as $item) {
                            $files = FilesModel::findOrFail($item);
                            $this->preview_file_id[] = $files;
                        }
                    }

                    $outgoing_other = OutgoingOthersModel::findOrFail($outgoing_id->type_id);

                    $this->fill(
                        $outgoing_other->only(
                            'document_name'
                        ),
                    );

                    $this->dispatch('show-outgoingModal');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->dispatch('error');
                }
                break;
            default:
                // throw new \InvalidArgumentException("Invalid type: {$this->type}");
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

    public function updateOutgoing()
    {
        // $this->validate();

        //* $this->outgoing_id is already a model instance of OutgoingModel; from readOutgoing()

        switch ($this->type) {
            case 'voucher':
                try {
                    $outgoing_voucher = OutgoingVoucherModel::findOrFail($this->outgoing_id->type_id);
                    $outgoing_voucher->voucher_name = $this->voucher_name;
                    $outgoing_voucher->save();

                    $this->outgoing_id->date = $this->date;
                    $this->outgoing_id->details = $this->details;
                    $this->outgoing_id->destination = $this->destination;
                    $this->outgoing_id->person_responsible = $this->person_responsible;

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
                        $existing_file_id = json_decode($this->outgoing_id->file_id, true) ?? [];
                        $updated_file_id = array_unique(array_merge($existing_file_id, $file_id));
                        $this->outgoing_id->file_id = json_encode($updated_file_id);
                    }

                    $this->outgoing_id->status_id = $this->status_id;
                    $this->outgoing_id->save();

                    $this->clear();
                    $this->dispatch('hide-outgoingModal');
                    $this->dispatch('success', message: 'Outgoing is updated successfully.');
                } catch (\Throwable $th) {
                    // throw $th;
                    $this->dispatch('error');
                }
                break;
            case 'ris':
                try {
                    $outgoing_ris = OutgoingRisModel::findOrFail($this->outgoing_id->type_id);
                    $outgoing_ris->document_name = $this->document_name;
                    $outgoing_ris->ppmp_code = $this->ppmp_code;
                    $outgoing_ris->save();

                    $this->outgoing_id->date = $this->date;
                    $this->outgoing_id->details = $this->details;
                    $this->outgoing_id->destination = $this->destination;
                    $this->outgoing_id->person_responsible = $this->person_responsible;

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
                        $existing_file_id = json_decode($this->outgoing_id->file_id, true) ?? [];
                        $updated_file_id = array_unique(array_merge($existing_file_id, $file_id));
                        $this->outgoing_id->file_id = json_encode($updated_file_id);
                    }

                    $this->outgoing_id->status_id = $this->status_id;
                    $this->outgoing_id->save();

                    $this->clear();
                    $this->dispatch('hide-outgoingModal');
                    $this->dispatch('success', message: 'Outgoing is updated successfully.');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->dispatch('error');
                }
                break;
            case 'procurement':
                try {
                    $outgoing_procurement = OutgoingProcurementModel::findOrFail($this->outgoing_id->type_id);
                    $outgoing_procurement->pr_no = $this->pr_no;
                    $outgoing_procurement->po_no = $this->po_no;
                    $outgoing_procurement->save();

                    $this->outgoing_id->date = $this->date;
                    $this->outgoing_id->details = $this->details;
                    $this->outgoing_id->destination = $this->destination;
                    $this->outgoing_id->person_responsible = $this->person_responsible;

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
                        $existing_file_id = json_decode($this->outgoing_id->file_id, true) ?? [];
                        $updated_file_id = array_unique(array_merge($existing_file_id, $file_id));
                        $this->outgoing_id->file_id = json_encode($updated_file_id);
                    }

                    $this->outgoing_id->status_id = $this->status_id;
                    $this->outgoing_id->save();

                    $this->clear();
                    $this->dispatch('hide-outgoingModal');
                    $this->dispatch('success', message: 'Outgoing is updated successfully.');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->dispatch('error');
                }
                break;
            case 'payroll':
                try {
                    $outgoing_payroll = OutgoingPayrollModel::findOrFail($this->outgoing_id->type_id);
                    $outgoing_payroll->payroll_type = $this->payroll_type;
                    $outgoing_payroll->save();

                    $this->outgoing_id->date = $this->date;
                    $this->outgoing_id->details = $this->details;
                    $this->outgoing_id->destination = $this->destination;
                    $this->outgoing_id->person_responsible = $this->person_responsible;

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
                        $existing_file_id = json_decode($this->outgoing_id->file_id, true) ?? [];
                        $updated_file_id = array_unique(array_merge($existing_file_id, $file_id));
                        $this->outgoing_id->file_id = json_encode($updated_file_id);
                    }

                    $this->outgoing_id->status_id = $this->status_id;
                    $this->outgoing_id->save();

                    $this->clear();
                    $this->dispatch('hide-outgoingModal');
                    $this->dispatch('success', message: 'Outgoing is updated successfully.');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->dispatch('error');
                }
                break;
            case 'other':
                try {
                    $outgoing_other = OutgoingOthersModel::findOrFail($this->outgoing_id->type_id);
                    $outgoing_other->document_name = $this->document_name;
                    $outgoing_other->save();

                    $this->outgoing_id->date = $this->date;
                    $this->outgoing_id->details = $this->details;
                    $this->outgoing_id->destination = $this->destination;
                    $this->outgoing_id->person_responsible = $this->person_responsible;

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
                        $existing_file_id = json_decode($this->outgoing_id->file_id, true) ?? [];
                        $updated_file_id = array_unique(array_merge($existing_file_id, $file_id));
                        $this->outgoing_id->file_id = json_encode($updated_file_id);
                    }

                    $this->outgoing_id->status_id = $this->status_id;
                    $this->outgoing_id->save();

                    $this->clear();
                    $this->dispatch('hide-outgoingModal');
                    $this->dispatch('success', message: 'Outgoing is updated successfully.');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->dispatch('error');
                }
                break;
            default:
                // throw new \InvalidArgumentException("Invalid type: {$this->type}");
                $this->dispatch('error');
        }
    }

    //* Retrieve history with the morphed table. For now, we only retrieves status coming from the outgoing record.
    public function readOutgoingHistory($outgoing_id)
    {
        try {
            // Fetch all statuses in a key-value pair: [status_id => status_name]
            $statusMap = StatusModel::withTrashed()->pluck('status_name', 'id');

            $this->document_history = Activity::where('subject_type', OutgoingModel::class)
                ->where('subject_id', $outgoing_id)
                ->where('log_name', 'outgoing')
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
