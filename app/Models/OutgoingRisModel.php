<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OutgoingRisModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tbl_outgoing_ris";

    protected $fillable = [
        'document_name',
        'ppmp_code'
    ];

    public function outgoing()
    {
        return $this->morphOne(OutgoingModel::class, 'type');
    }

    /* -------------------------------------------------------------------------- */

    public function getActivitylogOptions(): LogOptions
    {

        return LogOptions::defaults()
            ->useLogName('outgoing_procurement')
            ->logOnly(['document_name', 'ppmp_code'])
            ->logOnlyDirty();
    }
}
