<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomingRequestModel extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_incoming_requests';

    protected $fillable = [
        'incoming_request_no',
        'office_or_barangay_or_organization_name',
        'date_requested',
        'date_returned',
        'actual_returned_date',
        'category_id',
        'venue_id',
        'time_started',
        'time_ended',
        'contact_person_name',
        'contact_person_number',
        'description',
        'file_id',
        'status_id'
    ];
}
