<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingRisModel extends Model
{
    use HasFactory;

    protected $table = "tbl_outgoing_ris";

    protected $fillable = [
        'document_name',
        'ppmp_code'
    ];

    public function outgoing()
    {
        return $this->morphOne(OutgoingModel::class, 'type');
    }
}
