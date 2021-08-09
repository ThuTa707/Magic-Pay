<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'account_number' => $this->wallet ? $this->wallet->account_number : '',
            'amount' =>  $this->wallet ? $this->wallet->amount : '',
            'image' => 'https://ui-avatars.com/api/?background=3d5af1&color=fff&name='.$this->name,
            'qr_value' => $this->phone,
            'unread_noti_count' => $this->unreadNotifications()->count(),
        ];
    }
}
