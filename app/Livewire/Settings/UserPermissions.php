<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

#[Title('User Permissions')]
class UserPermissions extends Component
{
    use WithPagination;

    public $editMode;
    public $search;
    public $name;

    public function mount()
    {
        $user = auth()->user();

        if (!$user->can('read permissions')) {
            abort(403, 'Unauthorized');
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string'
        ];
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view(
            'livewire.settings.user-permissions',
            [
                'permissions' => $this->loadUserPermissions()
            ]
        );
    }

    public function loadUserPermissions()
    {
        return Permission::when($this->search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
            ->paginate(10);
    }

    public function createPermission()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $permission = new Permission();
                $permission->name = $this->name;
                $permission->save();
            });

            $this->clear();
            $this->dispatch('success', message: 'Permission created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
