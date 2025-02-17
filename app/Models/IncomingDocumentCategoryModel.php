<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomingDocumentCategoryModel extends Model
{
    use SoftDeletes;

    protected $table = 'ref_incoming_document_category';

    protected $fillable = [
        'incoming_document_category_name',
    ];
}
