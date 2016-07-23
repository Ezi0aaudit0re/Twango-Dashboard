<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventPhoto extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'event_photos';
    protected $fillable=['imgUrl','isDp','eventId'];

    /**
     * Assigning the has many relationship with profiles table
     *
     */
    public function event(){
        return $this->belongsTo('App\Event','eventId','id');
    }
}
