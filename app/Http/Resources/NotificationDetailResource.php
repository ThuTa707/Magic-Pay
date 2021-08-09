<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationDetailResource extends JsonResource
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
            
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'created_at' => $this->created_at->format('d-m-Y h:ia'),
            'read_at' => $this->read_at ? 1 : 0,
            'sourceable_type' => $this->data['sourceable_type'],
            'deep_link' => $this->data['deep_link'],
        ];
    }
}
