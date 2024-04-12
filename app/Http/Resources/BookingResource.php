<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    protected $limited = false;
    protected $epxandResources = false;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (!$this->limited) {
            if ($this->expandResources) {
                $user = new UserResource($this->user);
                $room = new RoomResource($this->room);
            } else {
                $user = $this->user->id;
                $room = $this->room->id;
            }

            return [
                'id'          => $this->id,
                'user'        => $user,
                'room'        => $room,
                'start_date'  => Carbon::make($this->start_date)->format('Y-m-d'),
                'end_date'    => Carbon::make($this->end_date)->format('Y-m-d'),
                'amenities'   => [
                    "breakfast" => (bool)$this->with_breakfast,
                    "lunch"     => (bool)$this->with_lunch,
                    "dinner"    => (bool)$this->with_dinner,
                    "minibar"   => (bool)$this->with_minibar,
                    "laundry"   => (bool)$this->with_laundry,
                ],
                'persons'     => $this->people,
                'total_price' => $this->total_price,
            ];
        } else {
            return [
                'booking_id' => $this->id,
                'start_date' => Carbon::make($this->start_date)->format('Y-m-d'),
                'end_date'   => Carbon::make($this->end_date)->format('Y-m-d'),
            ];
        }
    }

    public function isLimited(bool $flag)
    {
        $this->limited = $flag;

        return $this;
    }

    public function expandResources(bool $flag)
    {
        $this->expandResources = $flag;

        return $this;
    }
}
