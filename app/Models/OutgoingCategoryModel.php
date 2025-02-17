<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutgoingCategoryModel extends Model
{
    use SoftDeletes;

    protected $table = 'ref_outgoing_category';

    protected $fillable = [
        'outgoing_category_name',
    ];
}
