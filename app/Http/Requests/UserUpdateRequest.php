<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        // Taking id from route
        $id = $this->route('user');
        return [
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email,'.$id,
            'phone' => 'required|unique:users,phone,' .$id,
        ];
    }
}
