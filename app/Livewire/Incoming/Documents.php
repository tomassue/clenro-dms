<?php

namespace App\Livewire\Incoming;

use App\Models\CategoryModel;
use App\Models\IncomingDocumentModel;
use App\Models\StatusModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Incoming Documents')]
class Documents extends Component
{
    use WithPagination;

    public $editMode;
    public $search,
        $filter_status;
    public $incoming_document_id;
    public $category_id,
        $info,
        $file_id = [],
        $date,
        $status_id,
        $remarks;

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
            'category_id' => 'category'
        ];
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        return view(
            'livewire.incoming.documents',
            [
                'incoming_documents' => $this->loadIncomingDocuments(),
                'status_select' => $this->loadStatusSelect(),
                'category_select' => $this->loadCategorySelect()
            ]
        );
    }

    public function loadIncomingDocuments()
    {
        return IncomingDocumentModel::query()
            ->when($this->search, function ($query) {
                $query->where('category_id', 'like', '%' . $this->search . '%')
                    ->orWhere('info', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    public function loadStatusSelect()
    {
        return StatusModel::all();
    }

    public function loadCategorySelect()
    {
        return CategoryModel::query()
            ->where('category_type_id', 2)
            ->get();
    }

    public function createIncomingDocument()
    {
        $this->validate($this->rules(), [], $this->attributes());
        try {
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
