<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingVoucherModel extends Model
{
    use HasFactory;

    protected $table = "tbl_outgoing_voucher";

    protected $fillable = [
        "voucher_name"
    ];

    public function outgoing()
    {
        return $this->morphOne(OutgoingModel::class, 'type');
    }
}
