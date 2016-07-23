<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['type', 'date', 'title', 'description', 'imgUrl', 'created_at', 'updated_at'];

}
