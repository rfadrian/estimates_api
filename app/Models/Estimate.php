<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    protected $table = 'estimates';

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'category_id',
        'state_id'
    ];

}
