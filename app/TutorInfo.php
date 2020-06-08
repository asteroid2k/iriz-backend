<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

/**
 * @property mixed tutor_id
 * @property mixed courses
 */
class TutorInfo extends Eloquent
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tutor_id', 'courses','name','pin'
    ];
    protected $dates = [
        'deleted_at'
    ];

    public function user(){
        return User::where('ID','=',$this->tutor_id);
    }

}
