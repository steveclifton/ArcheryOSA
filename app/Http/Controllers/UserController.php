<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Image;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Redirect;


class UserController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLoginView()
    {
        return view ('auth.login');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]) === false) {

            return Redirect::back()
                ->withInput()
                ->withErrors(['email'=>' ', 'password'=>'Invalid Email or Password']);
        }
        return Redirect::route('home');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRegisterView()
    {
        return view ('auth.register');

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $user = new User();

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'min:6|required|confirmed',
            'password_confirmation' => 'required|same:password'
        ]);

        $user->firstname = htmlentities($request->input('firstname'));
        $user->lastname = htmlentities($request->input('lastname'));
        $user->email = htmlentities($request->input('email'));
        $user->password = Hash::make($request->input('password'));
        $user->lastipaddress = $request->ip();
        $user->usertype = 2;

        $user->save();

        Auth::login($user);

        return Redirect::route('home');

    }


    /**
     * @return mixed
     */
    public function logout()
    {
        Auth::logout();
        return Redirect::route('home');
    }


    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateProfile()
    {
        $user = Auth::user();

        $validator = Validator::make(request()->all(), [
            'firstname' => 'required|max:55',
            'lastname' => 'required|max:55',
            'email' => 'unique:users,email,'.$user->userid.',userid', // ignores the current users id
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


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfileView()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }


    public function forgotpassword()
    {
        $user = Auth::user();

        dd($user);
    }


} // classend




