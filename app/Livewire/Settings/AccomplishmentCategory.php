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
        return AccomplishmentCategoryModel::paginate(10);
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
}
