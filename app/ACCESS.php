<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ACCESS extends Model
{
    //

    use SoftDeletes;

    protected $table = 'access_key';

    protected $primaryKey = 'id';

    protected $fillable = [
        'key',
        'division',
        'is_allowed',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];
}
