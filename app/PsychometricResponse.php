<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PsychometricResponse extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'psychometric_responses';
    protected $fillable = ['id','questionId','answerId','typeA','valueA','typeB','valueB','typeC','valueC','typeD','valueD','typeE','valueE','userId','from','name','email','gender','relationshipStatus'];


}
