<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['country', 'latitude', 'longitude'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
