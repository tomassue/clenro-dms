<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class IncomingDocumentModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = "tbl_incoming_documents";

    protected $fillable = [
        "category_id",
        "file_id",
        "date",
        "status_id",
        "remarks"
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('incoming_document')
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }
}
