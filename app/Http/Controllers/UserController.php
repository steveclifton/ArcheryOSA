<?php

namespace App\Http\Controllers;

use App\ArcherRelation;
use App\LeaguePoint;
use App\Mail\ArcherRelationRequest;
use App\Mail\ConfirmArcherRelation;
use App\Mail\Welcome;
use Carbon\Carbon;
use Image;
use Validator;
use App\User;
use Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\RegisterValidator;
use App\Http\Requests\Users\UpdateProfileValidator;

class UserController extends Controller
{

    public static function getUserTotalPoints($userid, $divisionid, $eventid)
    {
        $result = DB::select("SELECT sum(`points`) as totalpoints
            FROM `leaguepoints`
            WHERE `userid` = :userid
            AND `divisionid` = :divisionid
            AND `eventid` = :eventid
            ORDER BY `points`
            LIMIT 10
            ",['userid'=>$userid, 'divisionid'=>$divisionid, 'eventid' => $eventid]);


        return $result[0]->totalpoints ?? 0;
    }

    public static function getUserWeekPoints($userid, $divisionid, $eventid, $week)
    {
        $result = LeaguePoint::where('userid', $userid)
                ->where('divisionid', $divisionid)
                ->where('eventid', $eventid)
                ->where('week', $week)
                ->pluck('points')
                ->first();

        return $result ?? 0;
    }

    public function PUBLIC_getRegisterView()
    {
        return view ('auth.register');
    }

    public function PUBLIC_getLoginView()
    {
        return view ('auth.login');
    }

    public function getPublicProfile(Request $request)
    {

        $user = User::where('username', $request->username)->get()->first();
        if (is_null($user)) {
            return redirect()->back()->with('failure', 'Invalid Request');
        }

        $results = DB::select("SELECT us.*, lp.`points` as weekspoints
            FROM `userscores` us
            LEFT JOIN `leaguepoints` lp ON (us.`user_id` = lp.`userid` AND us.`week` = lp.`week` AND us.`divisionid` = lp.`divisionid` AND us.`eventid` = lp.`eventid`)
            WHERE `user_id` = :userid   
            ORDER BY `created_at` DESC
        ", ['userid' => $user->userid]);

        $resultssorted = [];
        $leagueresultoverall = [];
        foreach ($results as $result) {
            $resultssorted[$result->eventname][] = $result;

            if ($result->eventtype == 1) {
                $leagueresultoverall[$result->eventname][$result->divisionname]['totalpoints'] = $this->getUserTotalPoints($result->user_id, $result->divisionid, $result->eventid);
                $leagueresultoverall[$result->eventname][$result->divisionname]['averagescore'] = $result->avg_total_score;
            }
        }
        //dd($resultssorted, $leagueresultoverall);

        return view('auth.user.PUBLIC_user_results', compact('resultssorted', 'user', 'leagueresultoverall'));
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
    public function register(RegisterValidator $request)
    {
        $user                   = new User();
        $user->firstname        = htmlentities($request->input('firstname'));
        $user->lastname         = htmlentities($request->input('lastname'));
        $user->email            = htmlentities($request->input('email'));
        $user->password         = Hash::make($request->input('password'));
        $user->lastipaddress    = $request->ip();
        $user->usertype         = 3;
        $user->username = $request->input('firstname') . $request->input('lastname');
        $user->username = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $user->username)) . rand(1,1440);

        $user->save();

        Auth::login($user);

        $this->sendWelcomeEmail();

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

        $relationships = DB::select("SELECT u.`email`, ur.`authorised`, u.`firstname`, u.`lastname`, ur.`hash`
            FROM `userrelationships` ur
            JOIN `users` u ON (ur.`relationuserid` = u.`userid`)
            WHERE ur.`userid` = '". Auth::id() . "'
        ");


        return view('auth.profile', compact('user', 'organisations', 'relationships'));
    }


    public function getUserEventsView()
    {
        $userevents = DB::select("SELECT e.`eventid`, e.`startdate`, es.`name` as usereventstatus, e.`name`, e.`status` as eventstatus 
                        FROM `evententry` ee
                        LEFT JOIN `events` e USING (`eventid`)
                        LEFT JOIN `entrystatuses` es ON (ee.`entrystatusid` = es.`entrystatusid`)
                        WHERE ee.`userid` = '" . Auth::id(). "'
                        ");
        return view('auth.myevents', compact('userevents'));
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateProfile(UpdateProfileValidator $request)
    {

        // Used for adding days to the event
        if ($request->input('submit') == 'add') {
            return Redirect::route('createusermembershipview');
        } else if ($request->input('submit') == 'adduser') {
            return Redirect::route('createaddarcherview');
        }

        $user               = Auth::user();
        $user->email        = htmlentities(request('email'));
        $user->firstname    = htmlentities(request('firstname'));
        $user->lastname     = htmlentities(request('lastname'));
        $user->phone        = htmlentities(request('phone'));
        $user->address        = htmlentities(request('address'));

        if ($request->hasFile('profileimage')) {
            //clean up old image
            if (empty($user->image) !== true) {
                if (is_file(public_path('content/profile/' . $user->image))) {
                    unlink(public_path('content/profile/' . $user->image));
                }
            }

            $image = $request->file('profileimage');
            $filename = time() . rand(0,999) . '.' . $image->getClientOriginalExtension();
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


    public function sendWelcomeEmail()
    {
        //$when = Carbon::now()->addMinutes(1);

        Mail::to(Auth::user()->email)
            ->send(new Welcome(ucwords(Auth::user()->firstname)));
    }


    public function getCreateArcherRelationship()
    {
        return view('auth.user.addarcherrelation');
    }

    public function createArcherRelationship(Request $request)
    {

        Validator::make($request->all(), [
            'email' => 'email|required',
        ])->validate();

        $user = User::where('email', $request->input('email'))->get()->first();

        if (is_null($user)) {
            return back()->with('failure', 'User with that email address is unavailable');
        }

        $existingrequest = ArcherRelation::where('userid', Auth::id())->where('relationuserid', $user->userid)->get()->first();

        if (!is_null($existingrequest)) {
            return back()->with('failure', 'Request already pending');
        }

        $authfullname = ucwords(Auth::user()->firstname ?? '') . ' ' . ucwords(Auth::user()->lastname ?? '') . '(' . Auth::user()->email . ')';

        $hash = password_hash(rand( getenv('RAND_START'), getenv('RAND_END')), PASSWORD_DEFAULT);
        $hash = password_hash($hash, PASSWORD_DEFAULT);
        $hash = substr($hash, 7, 17);
        $hash = str_replace('/',rand(1,999), $hash);

        $archerrelation = new ArcherRelation();
        $archerrelation->userid = Auth::id();
        $archerrelation->relationuserid = $user->userid;
        $archerrelation->hash = $hash;
        $archerrelation->save();

        $this->sendRelationshipEmail($user->email, $user->firstname, $authfullname, $hash);

        return redirect('/profile')->with('key', 'The archer has been alerted to your request. Please wait for confirmation email');

    }

    private function sendRelationshipEmail($email, $firstname, $requestusername, $hash)
    {
        Mail::to($email)
            ->send(new ArcherRelationRequest($firstname, $requestusername, $hash));
    }

    public function authoriseUserRelationship(Request $request) {

        if (!empty($request->hash)) {
            $addarcher = ArcherRelation::where('hash', strval($request->hash))->where('authorised', 0)->get()->first();

            if (!is_null($addarcher)) {
                $archer = User::where('userid', $addarcher->userid)->get()->first();
                $requestarcher = User::where('userid', $addarcher->relationuserid)->get()->first();
                $addarcher->authorised = 1;
                $addarcher->save();

                $success = 'Great! We have authorised the request and now ' . ucwords($requestarcher->firstname ?? '') . ' can score for you!';

                Mail::to($archer->email)
                    ->send(new ConfirmArcherRelation($archer->firstname ?? '', $requestarcher->firstname ?? ''));

                return view('landingpages.addarcherlanding', compact('success'));
            }
        }


        $failure = 'An error has occurred, please contact ArcheryOSA';
        return view('landingpages.addarcherlanding', compact('failure'));


    }

    public function removeUserRelationship(Request $request)
    {
        $relation = ArcherRelation::where('hash', $request->hash)->get()->first();

        if (!is_null($relation)) {
            if ($relation->delete() ) {
                return redirect('/profile')->with('key', 'Update success');
            }
        }

        return redirect('/profile')->with('failure', 'Invalid Request');
    }

} // classend




