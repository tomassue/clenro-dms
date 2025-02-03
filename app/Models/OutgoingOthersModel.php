<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingOthersModel extends Model
{
    use HasFactory;

    protected $table = "tbl_outgoing_others";

    protected $fillable = [
        'document_name'
    ];

    public function outgoing()
    {
        return $this->morphOne(OutgoingModel::class, 'type');
    }
}
