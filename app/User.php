<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
//use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract
                                    //CanResetPasswordContract
{
    use Authenticatable, Authorizable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'loginMethod','firstName','lastName','birthday','role','dpUrl','originalDpUrl','profileUrl','email','password','phone','status','isApproved','token','validUser','waitListNo'];

    /**
     * Assigning the has One relationship with profiles table
     *
     */
    public function profile(){
        return $this->hasOne('App\Profile','userId','id');
    }

     public function psychometric(){
        return $this->hasOne('App\PsychometricResponse','userId','id');
    }
    /**
     * Assigning the has One relationship with profile Photo table
     *
     */
    public function photo(){
        return $this->hasMany('App\ProfilePhoto','userId','id');
    }

    public function eventUser(){
        return $this->hasMany('App\EventUser','userId','id');
    }

    public function fromMessage(){
        return $this->hasMany('App\Message','userAId','id');
    }

    public function toMessage(){
        return $this->hasMany('App\Message','userBId','id');
    }

    public function isLiked(){
        return $this->hasMany('App\Like','userAId','id');
    }

    public function compatibility(){
        return $this->hasMany('App\Compatibility','userAId','id');
    }
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['rememberToken'];
}
