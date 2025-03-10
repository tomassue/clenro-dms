<?php

namespace App\Livewire\Settings;

use App\Models\DivisionModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('User Management')]
class UserManagement extends Component
{
    use WithPagination;

    public $editMode = false;
    public $search;
    public $name,
        $username,
        $division_id,
        $email,
        $permissions = [];
    public $user, // User object
        $user_id; // User id

    public function mount()
    {
        $user = auth()->user();

        if (!$user->can('read user management')) {
            abort(403, 'Unauthorized');
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'username' => ['required', Rule::unique('users', 'username')->ignore($this->user_id)],
            'division_id' => 'required',
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($this->user_id)]
        ];
    }

    public function attributes()
    {
        return [
            'division_id' => 'division'
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
        return view('livewire.settings.user-management', [
            'users' => $this->loadUsersTable(),
            'division_select' => $this->loadDivisionSelect()
        ]);
    }

    public function loadUsersTable()
    {
        return User::withTrashed()
            ->with('division')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->paginate(10);
    }

    public function loadDivisionSelect()
    {
        return DivisionModel::all();
    }

    public function createUser()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $user = new User();
                $user->name = $this->name;
                $user->username = $this->username;
                $user->division_id = $this->division_id;
                $user->email = $this->email;
                $user->password = Hash::make('password');
                $user->save();
            });

            $this->dispatch('hide-userModal');
            $this->dispatch('success', message: 'User created successfully.');
            $this->clear();
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function readUser($user_id)
    {
        try {
            $this->editMode = true;

            $this->user_id = $user_id;

            $user = User::withTrashed()->findOrFail($user_id);
            $this->fill(
                $user->only('name', 'username', 'division_id', 'email')
            );

            $this->dispatch('show-userModal');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function updateUser()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            $user = User::findOrFail($this->user_id);

            DB::transaction(function () use ($user) {
                $user->name = $this->name;
                $user->username = $this->username;
                $user->division_id = $this->division_id;
                $user->email = $this->email;
                $user->save();
            });

            $this->dispatch('hide-userModal');
            $this->dispatch('success', message: 'User updated successfully.');
            $this->clear();
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function resetPassword(User $user_id)
    {
        try {
            $user = User::findOrFail($user_id->id);
            $user->password = Hash::make('password');
            $user->save();

            $this->dispatch('success', message: 'Password reset successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function deleteUser(User $user_id)
    {
        try {
            $user = User::findOrFail($user_id->id);
            $user->delete();

            $this->dispatch('success', message: 'User deactivated successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function restoreUser($user_id)
    {
        try {
            $user = User::withTrashed()->findOrFail($user_id);
            $user->restore();

            $this->dispatch('success', message: 'User reactivated successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function readUserPermissions(User $user)
    {
        $this->user = $user;

        $this->permissions = $user->getPermissionNames()->toArray();

        $this->dispatch('show-userPermissionModal');
    }

    public function updateUserPermissions()
    {
        try {
            DB::transaction(function () {
                $this->user->syncPermissions($this->permissions);
            });

            $this->clear();
            $this->dispatch('hide-userPermissionModal');
            $this->dispatch('success', message: 'User permissions updated successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }
}
