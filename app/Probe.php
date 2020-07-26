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
        'id', 'name', 'user', 'category', 'gps_lon', 'gps_lat', 'state', 'token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'share_token'
    ];

    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        static::creating(function ($query) {
            $query->token = bin2hex(random_bytes(6));
            $query->share_token = bin2hex(random_bytes(8));
        });
    }
}