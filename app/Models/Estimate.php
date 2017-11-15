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

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * To be published an estimate needs to have a category, a title and being pending
     * @param $estimate
     * @return bool
     */
    public function isPublishable($estimate)
    {
        if ($estimate->title &&
            $estimate->category_id &&
            $estimate->state_id == State::PENDING){
            return true;
        }
        return false;
    }

}
