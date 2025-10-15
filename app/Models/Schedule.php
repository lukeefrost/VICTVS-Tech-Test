<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['title', 'status', 'datetime', 'language', 'location_id'];

    protected $casts = ['datetime' => 'datetime'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'schedule_candidate');
    }
}
