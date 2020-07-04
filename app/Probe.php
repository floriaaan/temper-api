<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Probe extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'user', 'category', 'gps_lon', 'gps_lat', 'state'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}