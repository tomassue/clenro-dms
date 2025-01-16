<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('User Management')]
class UserManagement extends Component
{
    use WithPagination;

    public function render()
    {
        $data = [
            'users' => $this->loadUsersTable()
        ];

        return view('livewire.settings.user-management', $data);
    }

    public function loadUsersTable()
    {
        $users = User::paginate(10);

        return $users;
    }
}
