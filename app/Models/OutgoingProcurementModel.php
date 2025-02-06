<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OutgoingProcurementModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tbl_outgoing_procurement";

    protected $fillable = [
        "pr_no",
        "po_no"
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
            ->logOnly(['pr_no', 'po_no'])
            ->logOnlyDirty();
    }
}
