<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compatibility extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'compatibilities';
    protected $fillable = ['id','userAId','userBId','compatibilityAB', 'interactionAB','profileShown','profileLiked','reason','ageScore','heightScore','religionScore','salaryScore','collegeScore','photoScore','mutualInterestScore','cityScore','psychometricScore','mutualFriendScore','mutualFriends'];

    public function profiles()
    {
        return $this->belongsTo('App\Profile','userBId','userId');
    }

    public function selfProfiles(){
        return $this->belongsTo('App\Profile','userAId','userId');
    }

    // public function Like(){
    //
    // }
}
