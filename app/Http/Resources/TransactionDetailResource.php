<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
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

        'transaction_id' => $this->transaction_id,
        'ref_no' => $this->ref_no,
        'amount' => number_format($this->amount, 2)."MMK",
        'money_type' => $this->type, // 1 = income, 2 = expense
        'created_at' => $this->created_at,
        'source' => $this->sourceUser->name,
        'description' => $this->description,
    ];
    }
}
