<?php

namespace App\Http\Controllers;

use App\ArcherRelation;
use App\LeaguePoint;
use App\Mail\ArcherRelationRequest;
use App\Mail\ConfirmArcherRelation;
use App\Mail\Welcome;
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



    /*****************************************************
     *                PUBLIC STATIC METHODS              *
     *****************************************************/

    public static function getUserTotalPoints($userid, $divisionid, $eventid)
    {
        $result = DB::select("SELECT `points`
            FROM `leaguepoints`
            WHERE `userid` = :userid
            AND `divisionid` = :divisionid
            AND `eventid` = :eventid
            ORDER BY `points` DESC
            LIMIT 10
            ",['userid'=>$userid, 'divisionid'=>$divisionid, 'eventid' => $eventid]);

        $totalpoints = 0;
        foreach ($result as $r) {
            $totalpoints += intval($r->points);
        }

        return $totalpoints;
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

    public static function getUserTop10Scores($userid, $divisionid, $eventid) {

        $result = DB::select("SELECT `total_score`
            FROM `scores`
            WHERE `userid` = :userid
            AND `divisionid` = :divisionid
            AND `eventid` = :eventid
            ORDER BY `total_score` DESC
            LIMIT 10
            ",['userid'=>$userid, 'divisionid'=>$divisionid, 'eventid' => $eventid]);

        $totalscore = 0;
        foreach ($result as $r) {
            $totalscore += intval($r->total_score);
        }
        return $totalscore;
    }




    /*****************************************************
     *                EMAIL METHODS                      *
     *****************************************************/

    /**
     * EMAIL
     * Sends the relationship email
     */
    private function sendRelationshipEmail($email, $firstname, $requestusername, $hash)
    {
        Mail::to($email)
            ->send(new ArcherRelationRequest($firstname, $requestusername, $hash));
    }

    /**
     * EMAIL
     * Sends welcome email
     */
    private function sendWelcomeEmail()
    {
        Mail::to(Auth::user()->email)
            ->send(new Welcome(ucwords(Auth::user()->firstname)));
    }







    /*****************************************************
     *                PRIVATE METHODS                     *
     *****************************************************/








    /*****************************************************
     *                PUBLIC METHODS                     *
     *****************************************************/

    /**
     * GET
     * Returns the register page
     */
    public function PUBLIC_getRegisterView()
    {
        return view ('auth.register');
    }

    /**
     * GET
     * Returns the login page
     */
    public function PUBLIC_getLoginView()
    {
        return view ('auth.login');
    }

    /**
     * GET
     * Returns a users public profile, including scores
     */
    public function getPublicProfile(Request $request)
    {

        $user = User::where('username', $request->username)->get()->first();
        if (is_null($user)) {
            return redirect()->back()->with('failure', 'Invalid Request');
        }

        $results = DB::select("SELECT us.*, lp.`points` as weekspoints
            FROM `userscores` us
            LEFT JOIN `leaguepoints` lp ON (us.`user_id` = lp.`userid` AND us.`week` = lp.`week` AND us.`divisionid` = lp.`divisionid` AND us.`eventid` = lp.`eventid`)
            WHERE us.`user_id` = :userid   
            
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


        return view('auth.user.PUBLIC_user_results', compact('resultssorted', 'user', 'leagueresultoverall'));
    }



    /*****************************************************
     *                ADMIN / AUTH METHODS               *
     *****************************************************/

    /**
     * GET
     * Logs a User in
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
     * GET
     * Creates/Registers a new user
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
        $user->username         = $request->input('firstname') . $request->input('lastname');
        $user->username         = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $user->username)) . rand(1,1440);

        $user->save();

        Auth::login($user);

        $this->sendWelcomeEmail();

        return Redirect::route('home');

    }

    /**
     * GET
     * Returns the current users profile view
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

        $childaccounts = DB::select("SELECT u.`email`, ur.`authorised`, u.`firstname`, u.`lastname`, ur.`hash`, u.`userid`
            FROM `userrelationships` ur
            JOIN `users` u ON (ur.`relationuserid` = u.`userid`)
            WHERE ur.`userid` = '". Auth::id() . "'
            AND ur.`parentuserid` = '". Auth::id() . "'
        ");




        return view('auth.profile', compact('user', 'organisations', 'relationships', 'childaccounts'));
    }

    /**
     * POST
     * Updates a users profile
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

    /**
     * GET
     * Logs a user out, returns them to home
     */
    public function logout()
    {
        Auth::logout();
        return Redirect::route('home');
    }

    /**
     * GET
     * Returns a users registered events view
     */
    public function getUserEventsView()
    {

        $userevents = DB::select("SELECT e.`eventid`, e.`startdate`, es.`name` as usereventstatus, e.`name`, e.`status` as eventstatus, e.`url`
                        FROM `evententry` ee
                        LEFT JOIN `events` e USING (`eventid`)
                        LEFT JOIN `entrystatuses` es ON (ee.`entrystatusid` = es.`entrystatusid`)
                        WHERE ee.`userid` = '" . Auth::id(). "'
                        ");

    

        return view('auth.myevents', compact('userevents'));
    }




    /*****************************************************
     *                RELATIONSHIP METHODS                *
     *****************************************************/

    /**
     * GET
     * Returns the add archery relationship view
     */
    public function getCreateArcherRelationship()
    {
        return view('auth.user.addarcherrelation');
    }

    /**
     * POST
     * Creates the relationship between two users
     * Sends the email to get confirmation
     * Returns back to the Profile page
     */
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

        $archerrelation = new ArcherRelation();
        $archerrelation->userid = Auth::id();
        $archerrelation->relationuserid = $user->userid;
        $archerrelation->hash = $this->createHash();;
        $archerrelation->save();

        $this->sendRelationshipEmail($user->email, $user->firstname, $authfullname, $hash);

        return redirect('/profile')->with('key', 'The archer has been alerted to your request. Please wait for confirmation email');

    }

    /**
     * GET
     * Authorises a users relationship with another
     * Returns to the add archer view
     */
    public function authoriseUserRelationship(Request $request) {

        if (!empty($request->hash)) {
            $addarcher = ArcherRelation::where('hash', strval($request->hash))->where('authorised', 0)->get()->first();

            if (!empty($addarcher)) {
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

    /**
     * GET
     * Removes the relationship
     */
    public function removeUserRelationship(Request $request)
    {
        $relation = ArcherRelation::where('hash', $request->hash)->get()->first();

        if (!empty($relation) && $relation->delete()) {
            return redirect('/profile')->with('key', 'Update success');
        }

        return redirect('/profile')->with('failure', 'Invalid Request');
    }





    /*****************************************************
     *                CHILD ACCOUNT METHODS               *
     *****************************************************/
    /**
     * GET
     * Returns the create child account view
     */
    public function getCreateAccountView()
    {
        return view('auth.user.createchildaccount');

    }

    /**
     * GET
     * Returns the update child account view
     */
    public function getUpdateAccountView(Request $request)
    {
        $relationship = ArcherRelation::where('userid', Auth::id())
                        ->where('relationuserid', $request->childid)
                        ->get()
                        ->first();

        if (empty($relationship)) {
            return back()->with('failure', 'Error, you are not authorised to view this user');
        }

        $child = User::where('userid', $request->childid)
                    ->get()
                    ->first();

        return view('auth.user.updatechildaccount', compact('child'));
    }

    /**
     * POST
     * Creates an bare-basic account which will be related to the logged in user
     */
    public function createChildAccount(Request $request)
    {

        Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'unique:users,email'
        ])->validate();

        $user = new User();
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->usertype = 3;
        $user->email = !empty($request->input('email')) ? $request->input('email') : mt_rand(0, time());
        $user->username = $request->input('firstname') . $request->input('lastname');
        $user->username = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $user->username)) . rand(1,1440);
        $user->semiaccount = 1;
        $user->save();

        $archerrelation = new ArcherRelation();
        $archerrelation->userid = Auth::id();

        $archerrelation->relationuserid = $user->userid;
        $archerrelation->hash = $this->createHash();
        $archerrelation->authorised = 1;
        $archerrelation->parentuserid = Auth::id();
        $archerrelation->save();

        return redirect('/profile')->with('key', 'The archer has been created');

    }

    /**
     * POST
     * Updates an account
     */
    public function updateChildAccount(Request $request)
    {

        $relationship = ArcherRelation::where('userid', Auth::id())
            ->where('relationuserid', $request->userid)
            ->get()
            ->first();

        $child = User::where('userid', $request->userid)
            ->get()
            ->first();

        if (empty($relationship) || empty($child)) {
            return back()->with('failure', 'Error, you are not authorised to view this user');
        }

        $child->firstname = $request->input('firstname');
        $child->lastname = $request->input('lastname');
        $child->email = !empty($request->input('email')) ? $request->input('email') : mt_rand(0, time());
        $child->save();


        return redirect('/profile')->with('key', 'Updated Profile');


    }





} // class




