<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'emails';
    protected $fillable = ['name','email'];
}
