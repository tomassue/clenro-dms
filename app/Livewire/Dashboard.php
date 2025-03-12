<?php

namespace App\Livewire;

use App\Livewire\Components\ForwardToDivisionModal;
use App\Models\ForwardedIncomingRequestModel;
use App\Models\IncomingRequestModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Dashboard')]
class Dashboard extends Component
{
    use WithPagination;

    // public $search;

    public function mount()
    {
        $user = auth()->user();

        if (!$user->can('read dashboard')) {
            abort(403, message: 'Unauthorized');
        }
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'incoming_requests' => $this->loadIncomingRequests(),
            'total_requests' => $this->countTotalIncomingRequest(),
            'pending_requests' => $this->countPendingIncomingRequest(),
            'forwarded_requests' => $this->countForwardedIncomingRequest(),
            'completed_requests' => $this->countCompletedIncomingRequest(),
        ]);
    }

    public function countTotalIncomingRequest()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;

        return !is_null($user_division_id) && $user_division_id != "1" && $user_division_id !== ""
            ? ForwardedIncomingRequestModel::where('division_id', $user_division_id)
            ->count()
            : IncomingRequestModel::count();
    }

    public function countPendingIncomingRequest()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;
        $status_id = 1; // Pending

        return !is_null($user_division_id) && $user_division_id != "1" && $user_division_id !== ""
            ? ForwardedIncomingRequestModel::where('division_id', $user_division_id)
            ->whereHas('incomingRequest', function ($query) use ($status_id) {
                $query->where('status_id', $status_id);
            })
            ->count()
            : IncomingRequestModel::where('status_id', $status_id)->count();
    }

    public function countForwardedIncomingRequest()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;
        $status_id = 3; // Forwarded

        return !is_null($user_division_id) && $user_division_id != "1" && $user_division_id !== ""
            ? ForwardedIncomingRequestModel::where('division_id', $user_division_id)
            ->whereHas('incomingRequest', function ($query) use ($status_id) {
                $query->where('status_id', $status_id);
            })
            ->count()
            : IncomingRequestModel::where('status_id', $status_id)->count();
    }

    public function countCompletedIncomingRequest()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;
        $status_id = 4; // Completed

        return !is_null($user_division_id) && $user_division_id != "1" && $user_division_id !== ""
            ? ForwardedIncomingRequestModel::where('division_id', $user_division_id)
            ->whereHas('incomingRequest', function ($query) use ($status_id) {
                $query->where('status_id', $status_id);
            })
            ->count()
            : IncomingRequestModel::where('status_id', $status_id)->count();
    }

    public function loadIncomingRequests()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;
        $status_id = 4; // Completed

        return !is_null($user_division_id) && $user_division_id != "1" && $user_division_id !== ""
            ? ForwardedIncomingRequestModel::with('incomingRequest') // Eager load relationship
            ->where('division_id', $user_division_id)
            ->whereHas('incomingRequest', function ($query) use ($status_id) {
                $query->whereNot('status_id', $status_id);
            })
            ->orderBy('created_at', 'asc')
            ->get()
            : IncomingRequestModel::whereNot('status_id', $status_id)
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
