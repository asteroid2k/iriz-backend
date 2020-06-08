<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

/**
 * @property mixed student_id
 * @property mixed courses
 * @property mixed level
 */
class StudentInfo extends Eloquent
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
        'student_id', 'courses', 'level'
    );
    protected $dates = [
        'deleted_at'
    ];

    public function user(){
        return User::where('ID','=',$this->student_id);
    }
}
