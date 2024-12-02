<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
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
            'lokasiVenue' => $this->lokasiVenue,
            'descVenue' => $this->descVenue,
            'fasilitasVenue' => $this->fasilitasVenue,
            'price' => $this->price,
            'rating' => $this->rating,
            'image' => $this->image,
            'mitraId' => $this->mitraId,
            'owner' => $this->whenLoaded('owner'),
            'lapangans' => $this->whenLoaded('lapangans'),
            
        ];
    }
}
