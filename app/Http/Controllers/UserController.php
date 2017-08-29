<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Auth;
use Image;
use Validator;

class UserController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}


    public function index()
    {
    	$user = Auth::user();
    	return view ('auth.profile', compact('user'));
    }


    public function updateProfile()
    {
        $user = Auth::user();

        $validator = Validator::make(request()->all(), [
            'firstname' => 'required|max:55',
            'lastname' => 'required|max:55',
            'email' => 'unique:users,email,'.$user->id, // ignores the current users id
        ]);

        if ($validator->fails()) {
            return redirect('/profile')->withErrors($validator)->withInput();
        }

        $user->email = request('email');
        $user->firstname = request('firstname');
        $user->lastname = request('lastname');
        $user->phone = request('phone');


        if (request()->hasFile('profileimage')) {
           $image = request()->file('profileimage');
           $filename = time() . '.' . $image->getClientOriginalExtension();
           $location = public_path('image/' . $filename);
           Image::make($image)->resize(200,200)->save($location);
           $user->image = $filename;
        }

        $user->save();

        return redirect('/profile')->with('key', 'Update Successful');
    }
}



// if ( ! request('password') == '') {
//     $user->password = bcrypt(request('password'));
// }


