<?php

namespace App\Livewire\Settings;

use App\Models\IncomingRequestCategoryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Incoming Request Category')]
class IncomingRequestCategory extends Component
{
    use WithPagination;

    public $editMode;
    public $search;
    public $incoming_request_categories_id;
    public $incoming_request_category_name;

    public function mount()
    {
        $user = auth()->user();

        if (!$user->can('read references')) {
            abort(403, 'Unauthorized');
        }
    }

    public function rules()
    {
        return [
            'incoming_request_category_name' => ['required', Rule::unique('ref_incoming_request_category')->ignore($this->incoming_request_categories_id, 'id')],
        ];
    }

    public function attributes()
    {
        return [
            'incoming_request_category_name' => 'category',
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
            'livewire.settings.incoming-request-category',
            [
                'incoming_request_categories' => $this->loadIncomingRequestCategory()
            ]
        );
    }

    public function loadIncomingRequestCategory()
    {
        return IncomingRequestCategoryModel::withTrashed()
            ->when($this->search, function ($query) {
                $query->where('incoming_request_category_name', 'like', "%{$this->search}%");
            })
            ->paginate(10);
    }

    public function createIncomingRequestCategory()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $incoming_request_category = new IncomingRequestCategoryModel();
                $incoming_request_category->incoming_request_category_name = $this->incoming_request_category_name;
                $incoming_request_category->save();
            });

            $this->clear();
            $this->dispatch('hide-incomingRequestCategoryModal');
            $this->dispatch('success', message: 'Incoming Request Category created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readIncomingRequestCategory(IncomingRequestCategoryModel $incoming_request_categories_id)
    {
        try {
            $this->editMode = true;

            $this->incoming_request_categories_id = $incoming_request_categories_id->id;
            $this->incoming_request_category_name = $incoming_request_categories_id->incoming_request_category_name;

            $this->dispatch('show-incomingRequestCategoryModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function updateIncomingRequestCategory()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $incoming_request_category = IncomingRequestCategoryModel::find($this->incoming_request_categories_id);
                $incoming_request_category->incoming_request_category_name = $this->incoming_request_category_name;
                $incoming_request_category->save();
            });

            $this->clear();
            $this->dispatch('hide-incomingRequestCategoryModal');
            $this->dispatch('success', message: 'Incoming Request Category updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function deleteIncomingRequestCategory(IncomingRequestCategoryModel $incoming_request_categories_id)
    {
        try {
            $incoming_request_categories_id->delete();
            $this->dispatch('success', message: 'Incoming Request Category deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function restoreIncomingRequestCategory($incoming_request_categories_id)
    {
        try {
            $incoming_request_categories_id = IncomingRequestCategoryModel::withTrashed()->find($incoming_request_categories_id);
            $incoming_request_categories_id->restore();
            $this->dispatch('success', message: 'Incoming Request Category restored successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
