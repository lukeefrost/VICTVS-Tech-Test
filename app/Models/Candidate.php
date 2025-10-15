<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = ['name'];

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_candidate');
    }
}
