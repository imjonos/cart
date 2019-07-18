<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Http\Resources;

use CodersStudio\Cart\Http\Resources\ItemResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'meta' => [
                'count' => $this->collection->count(),
                'total' => $this->collection->sum('price')
            ],
            'data' => ItemResource::collection($this->collection),
        ];
    }

    public function with($request)
    {
        return [
            'links'    => [

            ],
        ];
    }
}

