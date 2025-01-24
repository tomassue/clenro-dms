<?php

namespace App\Livewire\Settings;

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
    public $name, $username, $email;
    public $user_id;

    public function rules()
    {
        return [
            'name' => 'required|string',
            'username' => ['required', Rule::unique('users', 'username')->ignore($this->user_id)],
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($this->user_id)]
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
            'users' => $this->loadUsersTable()
        ]);
    }

    public function loadUsersTable()
    {
        return User::withTrashed()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->paginate(10);
    }

    public function createUser()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $user = new User();
                $user->name = $this->name;
                $user->username = $this->username;
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

    public function readUser(User $user_id)
    {
        try {
            $this->editMode = true;

            $this->user_id = $user_id;
            $this->fill(
                $this->user_id->only('name', 'username', 'email')
            );

            $this->dispatch('show-userModal');
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch('error');
        }
    }

    public function updateUser()
    {
        $this->validate();

        try {
            $user = User::findOrFail($this->user_id->id);

            DB::transaction(function () use ($user) {
                $user->name = $this->name;
                $user->username = $this->username;
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
}
