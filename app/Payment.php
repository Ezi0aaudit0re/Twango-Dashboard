<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * Assigning the has many relationship with profiles table
     *
     */
    protected $fillable = array('paymentId','paymentRequestId','paymentGateway','twangz','currency','actualPrice','payablePrice','discount','paymentStatus','userId','productId');

    public function profile(){
        return $this->hasMany('App\Profile','userId','userId');
    }
}
