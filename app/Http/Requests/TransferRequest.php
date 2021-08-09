<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|min:9|max:11',
            'amount' => 'required|integer|min:1000',
        ];
    }       

    public function messages(){
        return [

            'amount.min' => 'The amount must be at least 1000MMK'
        ];
    }
}
