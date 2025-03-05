<?php

namespace App\Livewire\Settings;

use App\Models\DivisionModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Divisions')]
class Divisions extends Component
{
    use WithPagination;

    public $editMode;
    public $search;
    public $division_id;
    public $division_name;

    public function rules()
    {
        return [
            'division_name' => ['required', Rule::unique('ref_division', 'division_name')->ignore($this->division_id, 'id')]
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
            'livewire.settings.divisions',
            [
                'divisions' => $this->loadDivisions()
            ]
        );
    }

    public function updated($property)
    {
        if ($property === 'search') {
            $this->resetPage();
        }
    }

    public function loadDivisions()
    {
        return DivisionModel::withTrashed()
            ->when($this->search, function ($query) {
                $query->where('division_name', 'LIKE', "%{$this->search}%");
            })
            ->paginate(5);
    }

    public function createDivision()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $division = new DivisionModel();
                $division->division_name = $this->division_name;
                $division->save();
            });

            $this->clear();
            $this->dispatch('hide-divisionModal');
            $this->dispatch('success', message: 'Division created succesfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readDivision(DivisionModel $division)
    {
        try {
            $this->fill(
                $division->only(
                    'division_name'
                )
            );

            $this->division_id = $division->id;

            $this->editMode = true;

            $this->dispatch('show-divisionModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function updateDivision()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $division = DivisionModel::findOrFail($this->division_id);
                $division->division_name = $this->division_name;
                $division->save();
            });

            $this->clear();
            $this->dispatch('hide-divisionModal');
            $this->dispatch('success', message: 'Division updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function deleteDivision(DivisionModel $division)
    {
        try {
            $division->delete();
            $this->dispatch('success', message: 'Division deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function restoreDivision($division_id)
    {
        try {
            $division = DivisionModel::withTrashed()->findOrFail($division_id);
            $division->restore();

            $this->dispatch('success', message: 'Division restored successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
