<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScheduleResource;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ScheduleController extends Controller
{
    protected ScheduleService $service;

    public function __construct(ScheduleService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $paginator = $this->service->getSchedules($request);

        $schedules = ScheduleResource::collection($paginator)->response()->getData(true);

        return Inertia::render('schedules/Index', [
            'schedules' => $schedules['data'],
            'meta' => $schedules['meta'] ?? $paginator->toArray()['meta'] ?? null,
            'filters' => $request->only(['status','candidate','country','date','sort','page']),
        ]);
    }
}
