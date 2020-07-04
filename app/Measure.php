<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'probe', 'temperature', 'humidity', 'date'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}