<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OutgoingPayrollModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tbl_outgoing_payroll";

    protected $fillable = [
        "payroll_type"
    ];

    public function outgoing()
    {
        return $this->morphOne(OutgoingModel::class, 'type');
    }

    /* -------------------------------------------------------------------------- */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('outgoing_payroll')
            ->logOnly(['payroll_type'])
            ->logOnlyDirty();
    }
}
