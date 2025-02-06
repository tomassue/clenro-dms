<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OutgoingVoucherModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tbl_outgoing_voucher";

    protected $fillable = [
        "voucher_name"
    ];

    public function outgoing()
    {
        return $this->morphOne(OutgoingModel::class, 'type');
    }

    /* -------------------------------------------------------------------------- */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('outgoing_voucher')
            ->logOnly(['voucher_name'])
            ->logOnlyDirty();
    }
}
