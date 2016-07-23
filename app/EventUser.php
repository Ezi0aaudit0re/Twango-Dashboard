<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventUser extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'event_users';
    protected $fillable = ['userId','eventId'];

    /**
     * Assigning the has many relationship with profiles table
     *
     */
    public function event(){
        return $this->belongsTo('App\Event');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
