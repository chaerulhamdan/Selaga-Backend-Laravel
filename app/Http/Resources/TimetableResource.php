<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimetableResource extends JsonResource
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
            'nameVenue' => $this->nameVenue,
            'nameLapangan' => $this->nameLapangan,
            'days' => $this->days,
            'availableHour' => $this->availableHour,
            'unavailableHour' => $this->unavailableHour,
            'lapanganId' => $this->lapanganId,
            'lapangan' => new LapanganResource($this->whenLoaded('lapangan')),
        ];
    }
}
