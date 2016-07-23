<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Psychometric extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'psychometrics';
    protected $fillable=['typeA','valueA','typeB','valueB','typeC','valueC','typeD','valueD','typeE','valueE','userId'];

}
