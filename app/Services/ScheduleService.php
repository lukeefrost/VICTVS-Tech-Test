<?php
namespace App\Services;

use App\Filters\ScheduleFilter;
use App\Http\Resources\ScheduleResource;
use App\Models\Candidate;
use App\Models\Location;
use App\Models\Schedule;
use Illuminate\Http\Request;
use const Grpc\STATUS_CANCELLED;

class ScheduleService
{

    /**
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getSchedules(Request $request): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Schedule::with(['candidates', 'location']);

        $filter = new ScheduleFilter($request);
        $query = $filter->apply($query);

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

        return new ScheduleResource($schedule);
    }

    public function cancelSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->status = Schedule::STATUS_CANCELLED;
        $schedule->save();

        return new ScheduleResource($schedule);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createSchedule(array $data)
    {
        $location = Location::firstOrCreate(
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
            'location_id' => $location->id,
        ]);

        foreach ($data['candidates'] ?? [] as $name) {
            $candidate = Candidate::firstOrCreate(['name' => $name]);
            $schedule->candidates()->attach($candidate->id);
        }

        return $schedule;
    }
}
?>
