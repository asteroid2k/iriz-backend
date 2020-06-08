<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class StudentLog extends Eloquent
{
    use SoftDeletes;
    //
    protected $fillable = [
        'ccode', 'log','isLive'
    ];

    protected $dates = [
      'started_at'
    ];
}
