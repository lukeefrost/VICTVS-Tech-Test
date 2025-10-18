<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ScheduleFilter
{
    protected Request $request;
    protected Builder $query;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->query = $query;

        $this->filterByStatus();
        $this->filterByCandidate();
        $this->filterByCountry();
        $this->filterByDate();
        $this->applySort();

        return $this->query;
    }

    protected function filterByStatus(): void
    {
        if ($status = $this->request->input('status')) {
            $this->query->where('status', $status);
        }
    }

    protected function filterByCandidate(): void
    {
        if ($candidate = $this->request->input('candidate')) {
            $this->query->whereHas('candidates', function ($q) use ($candidate) {
                $q->where('name', 'like', "%{$candidate}%");
            });
        }
    }

    protected function filterByCountry(): void
    {
        if ($country = $this->request->input('country')) {
            $this->query->whereHas('location', function ($q) use ($country) {
                $q->where('country', 'like', "%{$country}%");
            });
        }
    }

    protected function filterByDate(): void
    {
        if ($date = $this->request->input('date')) {
            $this->query->whereDate('datetime', $date);
        }
    }

    protected function applySort(): void
    {
        $sort = $this->request->input('sort', 'datetime:asc');
        [$field, $direction] = explode(':', $sort);
        $this->query->orderBy($field, $direction);
    }
}
