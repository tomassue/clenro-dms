<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VenueModel extends Model
{
    use SoftDeletes;

    protected $table = 'ref_venue';

    protected $fillable = [
        'venue_name'
    ];
}
