<?php

namespace App\Livewire\Settings;

use App\Models\IncomingDocumentCategoryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Incoming Document Category')]
class IncomingDocumentCategory extends Component
{
    use WithPagination;

    public $editMode,
        $incoming_document_categories_id;
    public $search;
    public $incoming_document_category_name;

    public function rules()
    {
        return [
            'incoming_document_category_name' => ['required', Rule::unique('ref_incoming_document_category')->ignore($this->incoming_document_categories_id, 'id')],
        ];
    }

    public function attributes()
    {
        return [
            'incoming_document_category_name' => 'category',
        ];
    }

    public function render()
    {
        return view(
            'livewire.settings.incoming-document-category',
            [
                'incoming_document_categories' => $this->loadIncomingDocumentCategory()
            ]
        );
    }

    public function clear()
    {
        $this->reset();
    }

    public function loadIncomingDocumentCategory()
    {
        return IncomingDocumentCategoryModel::withTrashed()
            ->when($this->search, function ($query) {
                $query->where('incoming_document_category_name', 'like', "%{$this->search}%");
            })
            ->paginate(10);
    }

    public function createIncomingDocumentCategory()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $incoming_document_category = new IncomingDocumentCategoryModel();
                $incoming_document_category->incoming_document_category_name = $this->incoming_document_category_name;
                $incoming_document_category->save();
            });

            $this->clear();
            $this->dispatch('hide-incomingDocumentCategoryModal');
            $this->dispatch('success', message: 'Incoming Document Category created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readIncomingDocumentCategory(IncomingDocumentCategoryModel $incoming_document_categories_id)
    {
        try {
            $this->editMode = true;

            $this->incoming_document_categories_id = $incoming_document_categories_id->id;
            $this->incoming_document_category_name = $incoming_document_categories_id->incoming_document_category_name;

            $this->dispatch('show-incomingDocumentCategoryModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function updateIncomingDocumentCategory()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $incoming_document_category = IncomingDocumentCategoryModel::findOrFail($this->incoming_document_categories_id);
                $incoming_document_category->incoming_document_category_name = $this->incoming_document_category_name;
                $incoming_document_category->save();
            });

            $this->clear();
            $this->dispatch('hide-incomingDocumentCategoryModal');
            $this->dispatch('success', message: 'Incoming Document Category updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function deleteIncomingDocumentCategory(IncomingDocumentCategoryModel $incoming_document_categories_id)
    {
        try {
            $incoming_document_categories_id->delete();
            $this->dispatch('success', message: 'Incoming Document Category deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function restoreIncomingDocumentCategory($incoming_document_categories_id)
    {
        try {
            $incoming_document_categories_id = IncomingDocumentCategoryModel::withTrashed()->find($incoming_document_categories_id);
            $incoming_document_categories_id->restore();
            $this->dispatch('success', message: 'Incoming Document Category restored successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
