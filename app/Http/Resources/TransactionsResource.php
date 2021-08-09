<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   

        if($this->type == 1){
            $case = 'From '.$this->sourceUser->name;
            $content = 'Cash In';
        } else if($this->type == 2){
            $case = 'To '.$this->sourceUser->name;
            $content = 'Cash Out';
        };

        return [

            'amount' => number_format($this->amount, 2)."MMK",
            'transaction_id' => $this->transaction_id,
            'money_type' => $this->type, // 1 = income, 2 = expense
            'case' => $case,
            'content' => $content,
            'created_at' => $this->created_at->format('d M Y h:ia'),

        ];
    }
}
