<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ForwardedIncomingDocumentsModel extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = "tbl_forwarded_incoming_documents";

    protected $fillable = [
        'incoming_document_id',
        'division_id',
        'is_opened'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('forwarded incoming document')
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    // This is to customize what is being saved in the subject_id under this model.
    // Since we are trying to connect this model to IncomingDocumentModel. We want the id of IncomingDocumentModel to be saved here as the subject_id.
    public function tapActivity(Activity $activity)
    {
        $activity->subject_id = $this->incoming_document_id;
    }

    /* -------------------------------------------------------------------------- */

    public function division()
    {
        return $this->belongsTo(DivisionModel::class, 'division_id', 'id');
    }
}
