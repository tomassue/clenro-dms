<?php

namespace App\Livewire\Settings;

use App\Models\OutgoingCategoryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Outgoing Category')]
class OutgoingCategory extends Component
{
    use WithPagination;

    public $editMode,
        $outgoing_category_id;
    public $search;
    public $outgoing_category_name;

    public function rules()
    {
        return [
            'outgoing_category_name' => ['required', Rule::unique('ref_outgoing_category')->ignore($this->outgoing_category_id, 'id')]
        ];
    }

    public function attributes()
    {
        return [
            'outgoing_category_name' => 'category'
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
            'livewire.settings.outgoing-category',
            [
                'outgoing_categories' => $this->loadOutgoingCategory()
            ]
        );
    }

    public function loadOutgoingCategory()
    {
        return OutgoingCategoryModel::withTrashed()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    public function createOutgoingCategory()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $outgoing_category = new OutgoingCategoryModel();
                $outgoing_category->outgoing_category_name = $this->outgoing_category_name;
                $outgoing_category->save();
            });

            $this->clear();
            $this->dispatch('hide-outgoingCategoryModal');
            $this->dispatch('refreshOutgoingCategory');
            $this->dispatch('success', message: 'Outrgoing category successfully added.');
        } catch (\Exception $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readOutgoingCategory(OutgoingCategoryModel $outgoing_category)
    {
        try {
            $this->editMode = true;
            $this->outgoing_category_id = $outgoing_category->id;
            $this->outgoing_category_name = $outgoing_category->outgoing_category_name;
            $this->dispatch('show-outgoingCategoryModal');
        } catch (\Exception $e) {
            $this->dispatch('error');
        }
    }

    public function updateOutgoingCategory()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $outgoing_category = OutgoingCategoryModel::find($this->outgoing_category_id);
                $outgoing_category->outgoing_category_name = $this->outgoing_category_name;
                $outgoing_category->save();
            });

            $this->clear();
            $this->dispatch('hide-outgoingCategoryModal');
            $this->dispatch('success', message: 'Outrgoing category successfully updated.');
        } catch (\Exception $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function deleteOutgoingCategory(OutgoingCategoryModel $outgoing_category)
    {
        try {
            $outgoing_category->delete();
            $this->dispatch('success', message: 'Outrgoing category successfully deleted.');
        } catch (\Exception $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function restoreOutgoingCategory($outgoing_category_id)
    {
        try {
            $outgoing_category = OutgoingCategoryModel::withTrashed()->find($outgoing_category_id);
            $outgoing_category->restore();
            $this->dispatch('success', message: 'Outrgoing category successfully restored.');
        } catch (\Exception $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
