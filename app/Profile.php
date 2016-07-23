<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profiles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['gender','iAm','age', 'heightFeet','heightInch','religion','relationshipStatus','friends','hometown','currentCity','cityLatitude','cityLongitude','companyName','position','salary','college','degree','socialLikes','interests','hobbies','collegeScore','photoScore','importanceProfileScore', 'starvationScore','freshnessScore','currencyUsedScore','activenessScore','isApproved','isDiscoverable','isActive','isFake','salaryScore','cityId','userId','lastMutualLikeDate','lastActiveDate','currencyUsed','currencyLeft','currencyPurchased','promoCode','deviceId','deviceOs'];

    /**
     *
     */
    /**
     * Assigning the belongs To relationship with users table
     *
     */
    public function user(){
        return $this->belongsTo('App\User','userId','id');
    }

    /**
     * Assigning the belongs To relationship with users table
     *
     */
    public function city(){
        return $this->belongsTo('App\City');
    }
    public function compatibilities()
    {
        return $this->hasMany('App\Compatibility','userBId','userId');
    }

    public function psychometric(){
        return $this->hasOne('App\PsychometricResponse','userId','userId');
    }
    public function like()
    {
        return $this->hasMany('App\Like','userAId','userId');
    }

}
