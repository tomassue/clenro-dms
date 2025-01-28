<?php

namespace App\Livewire\Settings;

use App\Models\CategoryModel;
use App\Models\SubCategoryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Sub-category')]
class SubCategory extends Component
{
    use WithPagination;

    public $editMode;
    public $search;
    public $sub_category_id;
    public $category_id, $sub_category_name;

    public function rules()
    {
        return [
            'category_id' => 'required',
            'sub_category_name' => [
                'required',
                Rule::unique('ref_sub_category')->where(function ($query) {
                    return $query->where('category_id', $this->category_id);
                }),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'category',
            'sub_category_name' => 'sub-category',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        return view(
            'livewire.settings.sub-category',
            [
                'sub_categories' => $this->loadSubCategory(),
                'categories' => $this->loadSelectCategory()
            ]
        );
    }

    public function loadSubCategory()
    {
        return SubCategoryModel::with('category')
            ->where('sub_category_name', 'like', '%' . $this->search . '%')
            ->orWhere('category_id', 'like', '%' . $this->search . '%')
            ->withTrashed()
            ->paginate(10);
    }

    public function loadSelectCategory()
    {
        return CategoryModel::all();
    }

    public function createSubCategory()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $sub_category = new SubCategoryModel();
                $sub_category->category_id = $this->category_id;
                $sub_category->sub_category_name = $this->sub_category_name;
                $sub_category->save();
            });

            $this->clear();
            $this->dispatch('hide-subCategoryModal');
            $this->dispatch('success', message: 'Sub-category created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readSubCategory($sub_category_id)
    {
        try {
            $this->editMode = true;

            $this->sub_category_id = $sub_category_id;

            $sub_category = SubCategoryModel::withTrashed()->findOrFail($sub_category_id);
            $this->fill(
                $sub_category->only('category_id', 'sub_category_name')
            );

            $this->dispatch('show-subCategoryModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function updateSubCategory()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $sub_category = SubCategoryModel::find($this->sub_category_id);
                $sub_category->category_id = $this->category_id;
                $sub_category->sub_category_name = $this->sub_category_name;
                $sub_category->save();
            });

            $this->clear();
            $this->dispatch('hide-subCategoryModal');
            $this->dispatch('success', message: 'Sub-category updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function deleteSubCategory(SubCategoryModel $sub_category)
    {
        try {
            $sub_category->delete();

            $this->dispatch('success', message: 'Sub-category deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function restoreSubCategory($sub_category_id)
    {
        try {
            SubCategoryModel::withTrashed()->find($sub_category_id)->restore();

            $this->dispatch('success', message: 'Sub-category restored successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
