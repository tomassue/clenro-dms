<?php

namespace App\Livewire;

use App\Models\FilesModel;
use App\Models\IncomingRequestModel;
use App\Models\StatusModel;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Calendar')]
class Calendar extends Component
{
    public $editMode;
    public $search,
        $filter_status;
    public $incoming_request_calendar_id,
        $preview_file_id = [];
    public $incoming_request_no,
        $office_or_barangay_or_organization_name,
        $date_requested,
        $date_returned,
        $actual_returned_date,
        $category_id,
        $sub_category_id,
        $venue_id,
        $time_started,
        $time_ended,
        $contact_person_name,
        $contact_person_number,
        $description,
        $file_id,
        $status_id;

    public function mount()
    {
        $user = auth()->user();

        if (!$user->can('read calendar')) {
            return abort(403, 'Unauthorized');
        }
    }

    public function clear()
    {
        $this->reset();
    }

    public function updated($property)
    {
        if ($property === 'filter_status') {
            $this->dispatch('refresh-calendar', meetings: $this->loadIncomingRequestCalendar());
        }
    }

    public function render()
    {
        return view(
            'livewire.calendar',
            [
                'incoming_request_calendar' => $this->loadIncomingRequestCalendar(),
                'status_select' => $this->loadStatusSelect()
            ]
        );
    }

    public function loadIncomingRequestCalendar()
    {
        return IncomingRequestModel::with(['status', 'venue'])
            ->when($this->filter_status, function ($query) {
                $query->where('status_id', $this->filter_status);
            })
            ->get()
            ->map(function ($item) {
                $backgroundColor = '#E4A11B'; // Default color
                $borderColor = '#E4A11B';

                switch ($item->status->status_name) {
                    case 'pending':
                        $backgroundColor = '#f1416c'; // Red
                        $borderColor = '#f1416c'; // Red
                        break;
                    case 'processed':
                        $backgroundColor = '#7239ea'; // Purple
                        $borderColor = '#7239ea'; // Purple
                        break;
                    case 'forwarded':
                        $backgroundColor = '#ffc700'; // Yellow
                        $borderColor = '#ffc700'; // Yellow
                        break;
                    case 'completed':
                        $backgroundColor = '#00d9d9'; // Neon Blue
                        $borderColor = '#00d9d9'; // Neon Blue
                        break;
                    case 'cancelled':
                        $backgroundColor = '#181c32'; // Black
                        $borderColor = '#181c32'; // Black
                        break;
                    default:
                        $backgroundColor = '#E4A11B';
                        $borderColor = '#E4A11B';
                        break;
                }

                return [
                    'id'              => $item->id,
                    'title'           => $item->office_or_barangay_or_organization_name . ' | ' . strtoupper($item->venue->venue_name),
                    'start'           => $item->date_requested . 'T' . $item->time_started,
                    'end'             => $item->date_returned . 'T' . $item->time_ended,
                    'allDay'          => false,
                    'backgroundColor' => $backgroundColor,
                    'borderColor'     => $borderColor
                ];
            });
    }

    public function loadStatusSelect()
    {
        return StatusModel::where('status_type', 'incoming request')
            ->get();
    }

    public function showDetails(IncomingRequestModel $incoming_request_calendar_id)
    {
        try {
            $this->incoming_request_calendar_id = $incoming_request_calendar_id;

            if ($incoming_request_calendar_id->file_id) {
                $this->preview_file_id = []; // unset it first

                foreach (json_decode($incoming_request_calendar_id->file_id) as $item) {
                    $files = FilesModel::findOrFail($item);
                    $this->preview_file_id[] = $files;
                }
            }

            $this->dispatch('show-detailsModal');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }

    public function readFile($file_id)
    {
        try {
            $signedURL = URL::temporarySignedRoute(
                'file.view',
                now()->addMinutes(10),
                ['id' => $file_id]
            );

            $this->dispatch('read-file', url: $signedURL);
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
