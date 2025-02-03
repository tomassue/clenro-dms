<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingPayrollModel extends Model
{
    use HasFactory;

    protected $table = "tbl_outgoing_payroll";

    protected $fillable = [
        "payroll_type"
    ];

    public function outgoing()
    {
        return $this->morphOne(OutgoingModel::class, 'type');
    }
}
