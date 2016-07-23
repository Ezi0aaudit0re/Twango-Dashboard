<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invites';

    /**
     * Assigning the has many relationship with profiles table
     *
     */
}
