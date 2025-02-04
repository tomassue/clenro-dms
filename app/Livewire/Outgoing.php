<?php

namespace App\Livewire;

use App\Models\FilesModel;
use App\Models\OutgoingModel;
use App\Models\OutgoingPayrollModel;
use App\Models\OutgoingProcurementModel;
use App\Models\OutgoingRisModel;
use App\Models\OutgoingVoucherModel;
use App\Models\StatusModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Outgoing')]
class Outgoing extends Component
{
    use WithPagination, WithFileUploads;

    public $editMode,
        $type;
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

                dd('other');
                break;
            default:
                // throw new \InvalidArgumentException("Invalid type: {$this->type}");
                $this->dispatch('error');
        }
    }
}
