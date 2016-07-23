<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PsychometricResult extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'psychometric_results';
        protected $fillable=['type','keyword','description'];

}
