<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilesModel extends Model
{
    use SoftDeletes;

    protected $table = 'ref_files';

    protected $fillable = [
        'file_name',
        'file_size',
        'file_type',
        'file_content',
        'user_id'
    ];
}
