<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guy extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'guys';
    protected $fillable = ['id','userId','reason'];

    /**
     * Assigning the has many relationship with profiles table
     *
     */
    public function profile(){
        return $this->hasMany('App\Profile','userId','userId');
    }
}
