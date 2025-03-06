<?php

namespace App\Livewire\Settings;

use App\Models\AccomplishmentCategoryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Accomplishment Category')]
class AccomplishmentCategory extends Component
{
    use WithPagination;

    public $editMode;
    public $search;
    public $accomplishment_category_id;
    public $accomplishment_category_name;

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
            'accomplishment_category_name' => ['required', Rule::unique('ref_accomplishment_categories', 'accomplishment_category_name')->ignore($this->accomplishment_category_id, 'id')]
        ];
    }

    public function updated($property)
    {
        if ($property === 'search') {
            $this->resetPage();
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
            'livewire.settings.accomplishment-category',
            [
                'accomplishment_categories' => $this->loadAccomplishmentCategories()
            ]
        );
    }

    public function loadAccomplishmentCategories()
    {
        return AccomplishmentCategoryModel::withTrashed()
            ->when($this->search, function ($query) {
                $query->where('accomplishment_category_name', 'like', "%{$this->search}%");
            })
            ->paginate(10);
    }

    public function createAccomplishmentCategory()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $accomplishment_category = new AccomplishmentCategoryModel();
                $accomplishment_category->accomplishment_category_name = $this->accomplishment_category_name;
                $accomplishment_category->save();
            });

            $this->clear();
            $this->dispatch('hide-accomplishmentCategoryModal');
            $this->dispatch('success', message: 'Accomplishment Category created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readAccomplishmentCategory(AccomplishmentCategoryModel $accomplishment_category)
    {
        try {
            $this->fill(
                $accomplishment_category->only(
                    'accomplishment_category_name'
                )
            );

            $this->accomplishment_category_id = $accomplishment_category->id;
            $this->editMode = true;
            $this->dispatch('show-accomplishmentCategoryModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function updateAccomplishmentCategory()
    {
        $this->validate();  // validate the form

        try {
            DB::transaction(function () {
                $accomplishment_category = AccomplishmentCategoryModel::findOrFail($this->accomplishment_category_id);
                $accomplishment_category->accomplishment_category_name = $this->accomplishment_category_name;
                $accomplishment_category->save();
            });

            $this->clear();
            $this->dispatch('hide-accomplishmentCategoryModal');
            $this->dispatch('success', message: 'Accomplishment Category updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function deleteAccomplishmentCategory(AccomplishmentCategoryModel $accomplishment_category)
    {
        try {
            $accomplishment_category->delete();

            $this->dispatch('success', message: 'Accomplishment Category deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function restoreAccomplishmentCategory($accomplishment_category_id)
    {
        try {
            $accomplishment_category = AccomplishmentCategoryModel::withTrashed()->findOrFail($accomplishment_category_id);
            $accomplishment_category->restore();

            $this->dispatch('success', message: 'Accomplishment Category restored successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
