<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events';
	protected $fillable=['name','description','id','imgUrl'];
    /**
     * Assigning the has many relationship with profiles table
     *
     */
    public function eventPhoto(){
        return $this->hasMany('App\EventPhoto','eventId','id');
    }
    public function eventUser(){
        return $this->hasMany('App\EventUser','eventId','id');
    }
    public function review(){
        return $this->hasMany('App\Review','eventId','id');
    }
}
