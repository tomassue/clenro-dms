<?php

namespace App\Livewire;

use App\Models\IncomingRequestModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Dashboard')]
class Dashboard extends Component
{
    use WithPagination;

    public $search;

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
            'completed_requests' => $this->countCompletedIncomingRequest(),
        ]);
    }

    public function countTotalIncomingRequest()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;

        return IncomingRequestModel::when(!is_null($user_division_id) && $user_division_id != "1", function ($query) use ($user_division_id) {
            $query->where('forwarded_to_division_id', $user_division_id);
        })
            ->count();
    }

    public function countPendingIncomingRequest()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;

        return IncomingRequestModel::when(!is_null($user_division_id) && $user_division_id != "1", function ($query) use ($user_division_id) {
            $query->where('forwarded_to_division_id', $user_division_id);
        })
            ->where('status_id', '1') // Pending
            ->count();
    }

    public function countCompletedIncomingRequest()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;

        return IncomingRequestModel::when(!is_null($user_division_id) && $user_division_id != "1", function ($query) use ($user_division_id) {
            $query->where('forwarded_to_division_id', $user_division_id);
        })
            ->where('status_id', '4') // Completed
            ->count();
    }

    public function loadIncomingRequests()
    {
        $user = auth()->user();
        $user_division_id = $user->division_id;

        return IncomingRequestModel::query()
            ->when($this->search, function ($query) {
                $query->where('incoming_request_no', 'like', '%' . $this->search . '%');
            })
            ->when(!is_null($user_division_id) && $user_division_id != "1", function ($query) use ($user_division_id) {
                $query->where('forwarded_to_division_id', $user_division_id);
            })
            ->where('status_id', '1')
            ->orderBy('created_at', 'asc')
            ->paginate(10);
    }
}
