<?php

namespace App\Livewire\Settings;

use App\Models\CategoryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Category')]
class Category extends Component
{
    use WithPagination;

    public $editMode;
    public $search;
    public $category_id;
    public $category_name;

    public function rules()
    {
        return [
            'category_name' => ['required', Rule::unique('ref_category', 'category_name')->ignore($this->category_id)]
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
            'livewire.settings.category',
            [
                'categories' => $this->loadCategory()
            ]
        );
    }

    public function loadCategory()
    {
        return CategoryModel::withTrashed()
            ->where('category_name', 'like', '%' . $this->search . '%')
            ->paginate(10);
    }

    public function createCategory()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $category = new CategoryModel();
                $category->category_name = $this->category_name;
                $category->save();
            });

            $this->clear();
            $this->dispatch('hide-categoryModal');
            $this->dispatch('success', message: 'Category created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readCategory($category_id)
    {
        try {
            $this->editMode = true;

            $this->category_id = $category_id;

            $category = CategoryModel::withTrashed()->findOrFail($category_id);
            $this->fill(
                $category->only('category_name')
            );

            $this->dispatch('show-categoryModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function updateCategory()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $category = CategoryModel::find($this->category_id);
                $category->category_name = $this->category_name;
                $category->save();
            });

            $this->clear();
            $this->dispatch('hide-categoryModal');
            $this->dispatch('success', message: 'Category updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function deleteCategory(CategoryModel $category)
    {
        try {
            $category->delete();
            $this->dispatch('success', message: 'Category deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function restoreCategory($category_id)
    {
        try {
            CategoryModel::withTrashed()
                ->where('id', $category_id)
                ->restore();
            $this->dispatch('success', message: 'Category restored successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
