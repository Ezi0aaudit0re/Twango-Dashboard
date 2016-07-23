<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Norm extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'norms';
 
    /**
     * Assigning the has many relationship with profiles table
     *
     */
    protected $fillable=['ageMean', 'ageStd', 'heightMean', 'heightStd', 'religionMean', 'religionStd', 'cityMean', 'cityStd', 'collegeMean', 'collegeStd', 'salaryMean', 'salaryStd', 'photoMean', 'photoStd', 'mutualFriendMean', 'mutualFriendStd', 'psychoOMean', 'psychoOStd', 'psychoCMean', 'psychoCStd', 'psychoEMean', 'psychoEStd', 'psychoAMean', 'psychoAStd', 'psychoNMean', 'psychoNStd','mutualInterestMean', 'mutualInterestStd', 'starvationMean', 'starvationStd', 'freshnessMean', 'freshnessStd', 'currencyUsedMean', 'currencyUsedStd', 'activenessMean', 'activenessStd'];
 
}