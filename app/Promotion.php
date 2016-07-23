<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'promotions';

    /**
     * Assigning the has many relationship with profiles table
     *
     */
    protected $fillable = array('userAId','userBId','type','twangz');

    public function profile(){
        return $this->hasMany('App\Profile','userAId','userId');
    }
}
