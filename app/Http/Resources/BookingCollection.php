<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
{
    protected $limited = false;
    protected $expandResources = true;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function (BookingResource $resource) use ($request) {
            return $resource
                ->isLimited($this->limited)
                ->expandResources($this->expandResources)
                ->toArray($request);
        })->all();
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
