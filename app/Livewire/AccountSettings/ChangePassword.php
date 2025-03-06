<?php

namespace App\Livewire\AccountSettings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Change Password')]
class ChangePassword extends Component
{
    public $current_password,
        $new_password,
        $confirm_password;

    public function rules()
    {
        return [
            'current_password' => 'required|current_password',
            'new_password' => 'required||min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'confirm_password' => 'required|same:new_password',
        ];
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.account-settings.change-password');
    }

    public function updatePassword()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $user = auth()->user();
                $user->update(['password' => Hash::make($this->new_password)]);
            });

            $this->clear();

            // Log out the user
            // Auth::logout();

            // Optionally, you can invalidate the session and regenerate the CSRF token
            // session()->invalidate();
            // session()->regenerateToken();

            // $this->dispatch('success', message: 'Password updated successfully.');

            return redirect()->route('dashboard');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
