<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'external_id' => $this->external_id,
            'status'      => $this->status,
            'destination' => $this->destination,
            'departure_date' => $this->departure_date,
            'return_date'    => $this->return_date
        ];
    }
}
