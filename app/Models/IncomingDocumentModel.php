<?php

namespace App\Models;

use Carbon\Carbon;
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
            ->useLogName('incoming document')
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    public function category()
    {
        return $this->belongsTo(IncomingDocumentCategoryModel::class, 'category_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

    // public function division()
    // {
    //     return $this->belongsTo(DivisionModel::class, 'forwarded_to_division_id', 'id');
    // }

    public function forwardedDivisions()
    {
        return $this->hasMany(ForwardedIncomingDocumentsModel::class, 'incoming_document_id');
    }

    /**
     * Accessor for formatted date.
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('M d, Y');
    }
}
