<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusModel extends Model
{
    use SoftDeletes;

    protected $table = 'ref_status';

    protected $fillable = [
        'status_name'
    ];
}
