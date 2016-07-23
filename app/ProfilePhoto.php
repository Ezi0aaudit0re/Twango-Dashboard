<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfilePhoto extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profile_photos';

    /**
     * Assigning the has many relationship with profiles table
     *
     */
         protected $fillable = array('userId', 'imgUrl', 'isDp', 'isVerified');

    public function user(){
        return $this->belongsTo('App\User');
    }
}
