<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PsychometricQuestion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'psychometric_questions';
        protected $fillable=['type','heading','imgUrl','isActive'];

}
