<?php

namespace App\Livewire;

use App\Models\OutgoingModel;
use App\Models\StatusModel;
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
        return OutgoingModel::paginate(10);
    }

    public function loadStatusSelect()
    {
        return StatusModel::where('status_type', 'outgoing')
            ->get();
    }
}
