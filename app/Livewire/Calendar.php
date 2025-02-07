<?php

namespace App\Livewire;

use App\Models\IncomingRequestModel;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Calendar')]
class Calendar extends Component
{
    public $editMode;
    public $search;

    public function render()
    {
        return view('livewire.calendar');
    }

    public function loadIncomingRequestCalendar()
    {
        return IncomingRequestModel::all()
            ->map(function ($item) {
                $backgroundColor = '#E4A11B'; // Default color

                switch ($item->status) {
                    case 'pending':
                        $backgroundColor = '#dc3545'; // Red
                        break;
                    case 'processed':
                        $backgroundColor = '#ffbf36'; // Yellow
                        break;
                    case 'forwarded':
                        $backgroundColor = '#282f3a'; // Dark
                        break;
                    case 'completed':
                        $backgroundColor = '#00d082'; // Green
                        break;
                    case 'cancelled':
                        $backgroundColor = '#6c757d'; // Grey
                        break;
                    default:
                        $backgroundColor = '#E4A11B';
                        break;
                }

                return [
                    'id'              => $item->id,
                    'title'           => $item->office_or_barangay_or_organization . ' | ' . strtoupper($item->venue_id),
                    'start'           => $item->request_date . 'T' . $item->start_time,
                    'end'             => $item->return_date . 'T' . $item->end_time,
                    'allDay'          => false,
                    'backgroundColor' => $backgroundColor
                ];
            });
    }
}
