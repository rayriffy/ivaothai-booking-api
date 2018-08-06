<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FLIGHT extends Model
{
    //

    use SoftDeletes;

    protected $table = 'flights';

    protected $primaryKey = 'id';

    protected $fillable = [
        'event_id',
        'flight_id',
        'user_division',
        'user_vid',
        'user_email',
        'user_rating',
        'aircraft_callsign',
        'aircraft_model',
        'flight_rule',
        'flight_type',
        'flight_load',
        'time_departure',
        'time_arrival',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];
}
