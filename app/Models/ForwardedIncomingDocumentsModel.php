<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ForwardedIncomingDocumentsModel extends Model
{
    use LogsActivity;

    protected $table = "tbl_forwarded_incoming_documents";

    protected $fillable = [
        'incoming_document_id',
        'division_id',
        'is_opened'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('forwaded incoming document')
            ->logOnly(['*'])
            ->logOnlyDirty();
    }
}
