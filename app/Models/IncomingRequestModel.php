<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class IncomingRequestModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'tbl_incoming_requests';

    protected $fillable = [
        'incoming_request_no',
        'office_or_barangay_or_organization_name',
        'date_requested',
        'date_returned',
        'actual_returned_date',
        'category_id',
        'venue_id',
        'time_started',
        'time_ended',
        'contact_person_name',
        'contact_person_number',
        'description',
        'file_id',
        'status_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('incoming_request')
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Check if the reference number is already set
            if (empty($model->incoming_request_no)) {
                $model->incoming_request_no = self::generateUniqueReference('REQ-', 8);
                // dd($model->incoming_request_no); // Debug the generated reference number
            }
        });
    }

    /**
     * Generate a unique reference number.
     *
     * @param string $prefix
     * @param int $length
     * @return string
     */
    public static function generateUniqueReference(string $prefix = '', int $length = 6): string
    {
        do {
            // Generate the reference number with the specified prefix
            $reference = $prefix . strtoupper(substr(uniqid(), -$length));
        } while (self::where('incoming_request_no', $reference)->exists());

        return $reference;
    }

    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategoryModel::class, 'sub_category_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

    /**
     * Accessor for formatted date_requested.
     *
     * @return string
     */
    public function getFormattedDateRequestedAttribute()
    {
        return Carbon::parse($this->date_requested)->format('M d, Y'); // e.g., Jan 01, 2024
    }

    /**
     * Accessor for formatted date_returned.
     *
     * @return string
     */
    public function getFormattedDateReturnedAttribute()
    {
        return $this->date_returned
            ? Carbon::parse($this->date_returned)->format('M d, Y')
            : null; // Handle null values
    }

    /**
     * Accessor for formatted actual_returned_date.
     *
     * @return string
     */
    public function getFormattedActualReturnedDateAttribute()
    {
        return $this->actual_returned_date
            ? Carbon::parse($this->actual_returned_date)->format('M d, Y')
            : null;
    }
}
