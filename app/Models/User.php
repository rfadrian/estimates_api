<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address', 'email', 'phone',
    ];

    public function estimates()
    {
        return $this->hasMany('App\Models\User');
    }
}
