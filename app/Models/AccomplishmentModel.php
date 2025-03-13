<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AccomplishmentModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'tbl_accomplishments';

    protected $fillable = [
        'accomplishment_category_id',
        'date',
        'details',
        'no_of_participants',
        'remarks',
        'file_id',
        'user_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('accomplishment')
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    public function accomplishment_category()
    {
        return $this->belongsTo(AccomplishmentCategoryModel::class, 'accomplishment_category_id', 'id');
    }

    public function getFormattedDateAttribute()
    {
        return date('F j, Y', strtotime($this->date));
    }
}
