<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OutgoingModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tbl_outgoing";

    protected $fillable = [
        'type_type',
        'type_id',
        'date',
        'details',
        'destination',
        'person_responsible',
        'file_id'
    ];

    /* -------------------------------------------------------------------------- */

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('M d, Y');
    }

    /* -------------------------------------------------------------------------- */

    public function type()
    {
        return $this->morphTo();
    }

    /* -------------------------------------------------------------------------- */

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

    /* -------------------------------------------------------------------------- */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('outgoing')
            ->logOnly(['*'])
            ->logOnlyDirty();
    }
}
