<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Http\Resources;
use Illuminate\Http\Resources\Json\Resource;

class ItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource;
        $array = $resource->toArray();
        //TODO разобраться почему нет через $this данных
        return [
            'type'          => 'items',
            'id'            => $array['id'],
            'attributes'    => [
                'name' => $array['name'],
                'price' => $array['price'],
                'quantity' => $array['quantity'],
                'params' => $array['params']
            ],
        ];
    }
}


