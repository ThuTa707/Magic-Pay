<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            'title' => Str::limit($this->data['title'], 40),
            'message' => Str::limit($this->data['message'], 100),
            'created_at' => $this->created_at->format('d/m/Y'),
            'read_at' => $this->read_at ? 1 : 0,
            'sourceable_type' => $this->data['sourceable_type'],
        ];
    }
}
