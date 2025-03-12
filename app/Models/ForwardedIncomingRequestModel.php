<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ForwardedIncomingRequestModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = "tbl_forwarded_incoming_requests";

    protected $fillable = [
        'incoming_request_id',
        'division_id',
        'is_opened'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('forwarded incoming request')
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    // This is to customize what is being saved in the subject_id under this model.
    // Since we are trying to connect this model to IncomingRequestModel. We want the id of IncomingRequestModel to be saved here as the subject_id.
    public function tapActivity(Activity $activity)
    {
        $activity->subject_id = $this->incoming_request_id;
    }

    /* -------------------------------------------------------------------------- */

    public function division()
    {
        return $this->belongsTo(DivisionModel::class, 'division_id', 'id');
    }

    public function incomingRequest()
    {
        return $this->belongsTo(IncomingRequestModel::class, 'incoming_request_id', 'id');
    }
}
