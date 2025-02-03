<?php

namespace App\Models;

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

    public function type()
    {
        return $this->morphTo();
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
