<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'orderName' => $this->orderName,
            'date' => $this->date,
            'hours' => $this->hours,
            'payment' => $this->payment,
            'orderId' => $this->orderId,
            'bookingId' => $this->bookingId,
            'order' => $this->whenLoaded('order'),
            'timetable' => new TimetableResource($this->whenLoaded('timetable')),
            'image' => $this->image,
            'confirmation' => $this->confirmation,
            'ratingStatus' => $this->ratingStatus
        ];
    }
}
