<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reviews';
    protected $fillable=['profileUrl','review','eventId','reviewer'];

    /**
     * Assigning the has many relationship with profiles table
     *
     */
    public function event(){
        return $this->belongsTo('App\Event','eventId','id');
    }
}
