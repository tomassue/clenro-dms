<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomingRequestCategoryModel extends Model
{
    use SoftDeletes;

    protected $table = 'ref_incoming_request_category';

    protected $fillable = [
        'incoming_request_category_name',
    ];
}
