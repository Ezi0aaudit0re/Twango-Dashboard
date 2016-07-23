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
    protected $fillable = ['id','userAId','userBId','message','type','uniqueKey','status','date'];
    public function user(){
        return $this->belongsTo('App\User','userBId','id');
    }
   
}
