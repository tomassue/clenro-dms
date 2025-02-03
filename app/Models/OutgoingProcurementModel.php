<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingProcurementModel extends Model
{
    use HasFactory;

    protected $table = "tbl_outgoing_procurement";

    protected $fillable = [
        "pr_no",
        "po_no"
    ];

    public function outgoing()
    {
        return $this->morphOne(OutgoingModel::class, 'type');
    }
}
