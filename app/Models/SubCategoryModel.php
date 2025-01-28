<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategoryModel extends Model
{
    use SoftDeletes;

    protected $table = 'ref_sub_category';

    protected $fillable = [
        'category_id',
        'sub_category_name'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'id');
    }
}
