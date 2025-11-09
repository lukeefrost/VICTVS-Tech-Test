<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    protected $service;

    public function __construct(ScheduleService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->service->getSchedules($request));
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->service->getSchedule($id));
    }

    public function saveStatus($id): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->service->saveScheduleStatus($id));
    }

    public function cancelSchedule($id): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->service->cancelSchedule($id));
    }
}
