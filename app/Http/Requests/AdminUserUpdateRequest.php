<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'min:9'],
        ];

        // return [
        //     'name' => 'required|min:3',
        //     'email' => 'required|unique:admin_users,email,'.$this->route('admin_user'),
        //     'phone' => 'required|unique:admin_users,phone,' .$this->route('admin_user'),
        // ];
    }
}
