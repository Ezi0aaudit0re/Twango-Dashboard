<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PsychometricStyle extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'psychometric_styles';
    protected $fillable=['type','aspect','style'];

}
