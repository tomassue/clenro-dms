<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OutgoingOthersModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tbl_outgoing_others";

    protected $fillable = [
        'document_name'
    ];

    public function outgoing()
    {
        return $this->morphOne(OutgoingModel::class, 'type');
    }

    /* -------------------------------------------------------------------------- */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('outgoing_others') // Custom log name for this model
            ->logOnly(['document_name']) // Log only specific attributes
            ->logOnlyDirty(); // Log only changed attributes
    }
}
