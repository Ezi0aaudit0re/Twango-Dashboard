<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';
    protected $fillable = ['id','userAId','userBId','message','type','date','uniqueKey'];

    public function userA(){
        return $this->belongsTo('App\User', 'userAId', 'id');
    }
   	public function userB(){
        return $this->belongsTo('App\User','userBId','id');
    }
}
