<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class WorkbookResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'      	=> $this->name,
            'items'       	=> $this->items,
            'created_by'    => $this->created_by,
            'created_at'    => $this->created_at->toDateString(),
        ];
    }
}
