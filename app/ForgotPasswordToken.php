<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * @property mixed expire_at
 * @property  string user_id
 * @property string code
 */
class ForgotPasswordToken extends Eloquent
{
    use SoftDeletes;
    //

    protected $fillable = array(
        '_id','email','token','user_id'
    );

    protected $dates = [
        'expire_at','deleted_at'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
