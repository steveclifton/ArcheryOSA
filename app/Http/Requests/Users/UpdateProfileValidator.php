<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class UpdateProfileValidator extends Request
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
            'firstname'     => 'required|max:55',
            'lastname'      => 'required|max:55',
            'email'         => 'email|unique:users,email,'.Auth::user()->userid.',userid', // ignores the current users id
            'profileimage'  => 'image',
        ];
    }
}
