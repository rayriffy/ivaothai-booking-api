<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CONFIRMPOOL extends Model
{
    //

    protected $table = 'confirmpool';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ticket_code',
        'ticket_timeout',
        'event_id',
        'flight_id',
        'user_division',
        'user_vid',
        'user_email',
        'user_rating',
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
    ];
}
