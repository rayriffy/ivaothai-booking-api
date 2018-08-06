<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EVENT extends Model
{
    //

    use SoftDeletes;

    protected $table = 'events';

    protected $primaryKey = 'id';

    protected $fillable = [
        'token',
        'event_id',
        'event_name',
        'booktime_start',
        'booktime_end',
        'airport_departure',
        'airport_arrival',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];
}
