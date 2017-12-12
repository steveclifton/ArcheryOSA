<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Mail\UpdatePassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;



class PasswordResetController extends Controller
{
    public function PUBLIC_getPasswordResetView()
    {
        return view('auth.passwords.resetpassword');
    }

    public function PUBLIC_getResetPasswordView(Request $request)
    {
        if (is_null($request->hash)) {
            Redirect::route('/');
        }

        $user = User::where('passwordhash', $request->hash)->get()->first();

        if (is_null($user)) {
            Redirect::route('/');
        }
        $hash = $request->hash;
        return view('auth.passwords.updatepassword', compact('hash'));


    }

    public function resetpassword(Request $request)
    {
        //dd($request);
        $user = User::where('email', $request->email)->get()->first();

        if (!is_null($user)) {


            $hash = hash('sha1', time() . rand(1, 1406));
            $hash = preg_replace("/[^A-Za-z0-9 ]/", '', $hash);

            $user->passwordhash = $hash;
            $user->save();

            $this->sendResetPasswordEmail($hash, $user->email, $user->firstname);

            return Redirect::route('passwordresetview')->with('message', 'Password reset successful, please check emails for further instructions');
        }

        return Redirect::route('passwordresetview')->with('failure', 'Unable to find email address');

    }

    public function updatepassword(Request $request)
    {
         $this->validate($request, [
             'password' => 'min:6|required|confirmed',
             'password_confirmation' => 'required|same:password'
         ]);



        $user = User::where('passwordhash', $request->hash)->get()->first();

        if (!is_null($user)) {
            $userid = $user->userid;
            $user->password = Hash::make($request->input('password'));
            $user->passwordhash = null;
            $user->save();

            $user = User::where('userid', $userid)->get()->first();

            Auth::login($user);

            //dd($user);

            $this->sendUpdatePasswordEmail($user->email, $user->firstname);

            return Redirect::route('profile')->with('key', 'Password Update Successful');
        }






    }

    public function sendResetPasswordEmail($hash, $email, $name)
    {
        Mail::to($email)
            ->send(new ResetPassword($hash, $email, ucwords($name)));
    }

    public function sendUpdatePasswordEmail($email, $name)
    {
        Mail::to($email)
            ->send(new UpdatePassword($name));
    }
}
