<?php

namespace App\Livewire\Settings;

use App\Models\VenueModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Venue')]
class Venue extends Component
{
    use WithPagination;

    public $editMode;
    public $search;
    public $venue_id;
    public $venue_name;

    public function rules()
    {
        return [
            'venue_name' => [
                'required',
                Rule::unique('ref_venue', 'venue_name')->ignore($this->venue_id)
            ],
        ];
    }

    public function attributes()
    {
        return [
            'venue_name' => 'Venue',
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
            'livewire.settings.venue',
            [
                'venues' => $this->loadVenue(),
            ]
        );
    }

    public function loadVenue()
    {
        return VenueModel::where('venue_name', 'like', '%' . $this->search . '%')
            ->withTrashed()
            ->paginate(10);
    }

    public function createVenue()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $venue = new VenueModel();
                $venue->venue_name = $this->venue_name;
                $venue->save();
            });

            $this->clear();
            $this->dispatch('success', message: 'Venue created successfully.');
            $this->dispatch('hide-venueModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readVenue($venue_id)
    {
        try {
            $this->editMode = true;

            $this->venue_id = $venue_id;

            $venue = VenueModel::withTrashed()->findOrFail($venue_id);
            $this->fill(
                $venue->only('venue_id', 'venue_name')
            );

            $this->dispatch('show-venueModal');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function updateVenue()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $venue = VenueModel::find($this->venue_id);
                $venue->venue_name = $this->venue_name;
                $venue->save();
            });

            $this->clear();
            $this->dispatch('success', message: 'Venue updated successfully.');
            $this->dispatch('hide-venueModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function deleteVenue(VenueModel $venue)
    {
        try {
            $venue->delete();
            $this->dispatch('success', message: 'Venue deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function restoreVenue($venue_id)
    {
        try {
            $venue_id = VenueModel::withTrashed()->find($venue_id);
            $venue_id->restore();

            $this->dispatch('success', message: 'Venue restored successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
