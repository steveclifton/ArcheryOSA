<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class RegisterValidator extends Request
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
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'min:6|required|confirmed',
            'password_confirmation' => 'required|same:password'
        ];
    }
}
