<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cities';

    /**
     * Assigning the has many relationship with profiles table
     *
     */
    public function profile(){
        return $this->hasMany('App\Profile');
    }
}
