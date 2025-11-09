<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['title', 'status', 'datetime', 'language', 'location_id'];

    protected $casts = ['datetime' => 'datetime'];

//    private const STATUS_PENDING = 'Pending';
//    private const STATUS_STARTED = 'Started';
//    private const STATUS_FINISHED = 'Finished';
    public const STATUS_CANCELLED = 'Cancelled';

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'schedule_candidate');
    }
}
