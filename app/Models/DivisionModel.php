<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DivisionModel extends Model
{
    use SoftDeletes;

    protected $table = 'ref_division';
}
