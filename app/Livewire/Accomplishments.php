<?php

namespace App\Livewire;

use App\Models\AccomplishmentCategoryModel;
use App\Models\AccomplishmentModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Accomplishments')]
class Accomplishments extends Component
{
    use WithPagination;

    public $search;
    public $editMode;
    public $accomplishment_id;
    public $accomplishment_category_id,
        $date,
        $details,
        $no_of_participants;

    public function rules()
    {
        return [
            'accomplishment_category_id' => 'required',
            'date' => 'required',
            'details' => 'required',
            'no_of_participants' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'accomplishment_category_id' => 'accomplishment category',
            'no_of_participants' => 'no. of participants'
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
            'livewire.accomplishments',
            [
                'accomplishments' => $this->loadAccomplishments(),
                'accomplishment_category_select' => $this->loadAccomplishmentCategorySelect()
            ]
        );
    }

    public function loadAccomplishments()
    {
        return AccomplishmentModel::when($this->search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('details', 'like', '%' . $search . '%')
                    ->orWhereHas('accomplishment_category', function ($query) use ($search) {
                        $query->where('accomplishment_category_name', 'like', '%' . $search . '%');
                    });
            });
        })
            ->paginate(10);
    }

    public function loadAccomplishmentCategorySelect()
    {
        return AccomplishmentCategoryModel::all();
    }

    public function createAccomplishment()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $accomplishment = new AccomplishmentModel();
                $accomplishment->accomplishment_category_id = $this->accomplishment_category_id;
                $accomplishment->date = $this->date;
                $accomplishment->details = $this->details;
                $accomplishment->no_of_participants = $this->no_of_participants;
                $accomplishment->save();
            });

            $this->clear();
            $this->dispatch('hide-accomplishmentModal');
            $this->dispatch('success', message: 'Accomplishment created successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function readAccomplishment(AccomplishmentModel $accomplishment_id)
    {
        try {
            $this->editMode = true;

            $this->accomplishment_id = $accomplishment_id->id;

            $this->fill(
                $accomplishment_id->only(
                    'accomplishment_category_id',
                    'date',
                    'details',
                    'no_of_participants'
                )
            );

            $this->dispatch('show-accomplishmentModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function updateAccomplishment()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $accomplishment = AccomplishmentModel::find($this->accomplishment_id);
                $accomplishment->accomplishment_category_id = $this->accomplishment_category_id;
                $accomplishment->date = $this->date;
                $accomplishment->details = $this->details;
                $accomplishment->no_of_participants = $this->no_of_participants;
                $accomplishment->save();
            });

            $this->clear();
            $this->dispatch('hide-accomplishmentModal');
            $this->dispatch('success', message: 'Accomplishment updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
