<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Redirect;


class UserController extends Controller
{



    public function PUBLIC_getRegisterView()
    {
        return view ('auth.register');
    }

    public function PUBLIC_getLoginView()
    {
        return view ('auth.login');
    }

    /*****************************************************
     *                                                   *
     *                ADMIN / AUTH METHODS               *
     *                                                   *
     *****************************************************/




    /**
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], true) === false) {

            return Redirect::back()
                ->withInput()
                ->withErrors(['email'=>' ', 'password'=>'Invalid Email or Password']);
        }
        return Redirect::route('home');
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfileView()
    {
        $user = Auth::user();
        $organisations = DB::select("SELECT *
                                    FROM `usermemberships`
                                    JOIN `organisations`
                                    USING (`organisationid`)
                                    WHERE `userid` = '". Auth::id() ."'
        
        ");


        return view('auth.profile', compact('user', 'organisations'));
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        // Used for adding days to the event
        if ($request->input('submit') == 'add') {
            return Redirect::route('createusermembershipview');
        }

        $user = Auth::user();

        $this->validate($request, [
            'firstname' => 'required|max:55',
            'lastname' => 'required|max:55',
            'email' => 'unique:users,email,'.$user->userid.',userid', // ignores the current users id
            'profileimage' => 'image',
        ]);


        $user->email = request('email');
        $user->firstname = request('firstname');
        $user->lastname = request('lastname');
        $user->phone = request('phone');

        if ($request->hasFile('profileimage')) {
            //clean up old image
            if (empty($user->image) !== true) {
                unlink(public_path('content/profile/' . $user->image));
            }

            $image = $request->file('profileimage');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('content/profile/' . $filename);
            Image::make($image)->resize(200,200)->save($location);
            $user->image = $filename;
        }

        $user->save();

        return redirect('/profile')->with('key', 'Update Successful');
    }



    public function forgotpassword()
    {
        $user = Auth::user();

        dd($user);
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        Auth::logout();
        return Redirect::route('home');
    }

} // classend




