<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

/**
 * @property bool isVerified
 * @property mixed phone
 * @property mixed last_name
 * @property mixed first_name
 * @property string password
 * @property string role
 * @property string sex
 * @property mixed email
 * @property mixed ID
 * @property mixed title
 */
class User extends Eloquent implements Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;
    use AuthenticableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'first_name', 'last_name', 'phone', 'avatar', 'role','ID','sex'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isTutor()
    {
        return $this->role == 'TUTOR';
    }

    public function isStudent()
    {
        return $this->role == 'STUDENT';
    }

    public function getInfo($ID){
        if ($this->isStudent()){
            return StudentInfo::where('student_id','=',$ID)->first();
        }elseif ($this->isTutor()){
            return TutorInfo::where('tutor_id','=',$ID)->first();
        }
        return null;
    }

}
