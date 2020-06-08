<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Course extends Eloquent
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'credits'
    ];
    protected $dates = [
        'deleted_at'
    ];

    public function attendanceInfo(){
        return $this->hasMany(AttendanceInfo::class);
    }
}
