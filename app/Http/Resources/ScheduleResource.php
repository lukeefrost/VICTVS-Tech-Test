<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'status'    => $this->status,
            'datetime'  => optional($this->datetime)->toIso8601String(),
            'language'  => $this->language,

            'location'  => $this->whenLoaded('location', [
                'id'        => $this->location->id,
                'country'   => $this->location->country,
                'latitude'  => $this->location->latitude,
                'longitude' => $this->location->longitude,
            ]),

            'candidates' => $this->whenLoaded('candidates', function () {
                return $this->candidates->map(fn ($c) => [
                    'id'   => $c->id,
                    'name' => $c->name,
                ]);
            }),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
