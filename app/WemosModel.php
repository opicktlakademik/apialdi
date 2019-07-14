<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WemosModel extends Model
{
    //
    public $timestamps = false;
    protected $table = 'wemos';
    protected $casts = [
        'ip' => 'ipAddress',
    ];
}
