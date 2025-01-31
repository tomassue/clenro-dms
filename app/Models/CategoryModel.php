<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryModel extends Model
{
    use SoftDeletes;

    protected $table = 'ref_category';

    protected $fillable = [
        'category_type_id',
        'category_name'
    ];

    public function categoryType()
    {
        return $this->belongsTo(CategoryTypeModel::class, 'category_type_id', 'id');
    }
}
