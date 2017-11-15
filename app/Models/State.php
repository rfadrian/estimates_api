<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';

    protected $fillable = ['name'];

    public $timestamps = false;

    const PENDING = 1;
    const PUBLISHED = 2;
    const DISCARDED = 3;
}
