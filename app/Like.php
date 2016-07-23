<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'likes';
    protected $fillable = ['id','userAId','userBId','isLike', 'reason','date','expiryDate'];

    public function user(){
        return $this->belongsTo('App\User','userBId','id');
    }
    public function profile(){
        return $this->belongsTo('App\Profile','userAId','userId');
    }
    public function psychometric(){
        return $this->belongsTo('App\PsychometricResponse','userBId','userId');
    }

    public function compatibility(){
        return $this->belongsTo('App\Compatibility', 'userAId', 'userBId');
    }

}
