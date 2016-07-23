<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratio extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ratios';
    protected $fillable=['id','date','totalM','totalF','newM','newF','activeM','activeF','mutualLikeM','mutualLikeF','avTimeFirstMatchM','avTimeFirstMatchF','avTimeMatchM','avTimeMatchF'];

}
