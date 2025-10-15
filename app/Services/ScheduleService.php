<?php
namespace App\Services;

use App\Models\Candidate;
use App\Models\Location;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleService
{

    /**
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getSchedules(Request $request): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Schedule::with(['candidates', 'location']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('candidate')) {
            $query->whereHas('candidates', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->candidate}%");
            });
        }
        if ($request->filled('country')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('country', 'like', "%{$request->country}%");
            });
        }
        if ($request->filled('date')) {
            $query->whereDate('datetime', $request->date);
        }

        if ($request->filled('sort')) {
            [$col, $dir] = array_merge(explode(':', $request->sort), ['asc']);
            $query->orderBy($col, $dir);
        } else {
            $query->orderBy('datetime', 'asc');
        }

        return $query->paginate(20);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Schedule|null
     */
    public function getSchedule($id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Schedule|null
    {
        return Schedule::with(['candidates', 'location'])->findOrFail($id);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function saveScheduleStatus($id)
    {
        $schedule = Schedule::findOrFail($id);

        $map = ['Pending' => 'Started', 'Started' => 'Finished', 'Finished' => 'Finished'];

        $schedule->status = $map[$schedule->status] ?? 'Pending';
        $schedule->save();

        return $schedule;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createSchedule(array $data)
    {
        $loc = Location::firstOrCreate(
            ['country' => $data['location']['country']],
            [
                'latitude' => $data['location']['latitude'] ?? null,
                'longitude' => $data['location']['longitude'] ?? null,
            ]
        );

        $schedule = Schedule::create([
            'title' => $data['title'],
            'status' => $data['status'] ?? 'Pending',
            'datetime' => $data['datetime'] ?? null,
            'language' => $data['language'] ?? null,
            'location_id' => $loc->id,
        ]);

        foreach ($data['candidates'] ?? [] as $name) {
            $candidate = Candidate::firstOrCreate(['name' => $name]);
            $schedule->candidates()->attach($candidate->id);
        }

        return $schedule;
    }
}
?>
