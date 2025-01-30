<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTypeModel extends Model
{
    use SoftDeletes;

    protected $table = "ref_category_type";

    protected $fillable = [
        'category_type_name'
    ];
}
