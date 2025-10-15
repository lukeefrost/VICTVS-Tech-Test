<?php

namespace Database\Seeders;

use App\Services\ScheduleService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ExamScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = new ScheduleService();
        $file = database_path('seeders/data/exam-schedule-data.json');
        $data = json_decode(File::get($file), true);

        foreach ($data as $item) {
            $service->createSchedule($item);
        }
    }
}
