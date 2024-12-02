<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LapanganResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nameLapangan' => $this->nameLapangan,
            'days' => $this->days,
            'hour' => $this->hour,
            'venueId' => $this->venueId,
            'venue' => new VenueResource($this->whenLoaded('venue')),
        ];
    }
}
