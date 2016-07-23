<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'feedbacks';

    protected $fillable=['rating','feedback','userId'];

    /**
     * Assigning the has many relationship with profiles table
     *
     */
    public function profile(){
        return $this->hasMany('App\Profile');
    }
}
