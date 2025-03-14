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
        'category_id',
        'sub_category_id',
        'date_and_time',
        'contact_person_name',
        'contact_person_number',
        'description',
        'file_id',
        'status_id',
        'remarks'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('incoming request')
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
        return $this->belongsTo(IncomingRequestCategoryModel::class, 'category_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

    public function forwardedDivisions()
    {
        return $this->hasMany(ForwardedIncomingRequestModel::class, 'incoming_request_id');
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
     * Accessor for formatted date_requested.
     *
     * @return string
     */
    public function getFormattedDateAndTimeAttribute()
    {
        return Carbon::parse($this->date_and_time)->format('M d, Y h:i A');
    }
}
