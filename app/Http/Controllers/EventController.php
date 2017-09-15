<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Division;
use App\Organisation;
use App\Round;
use App\Event;
use App\Rules\MaxEventDays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function getEventsView()
    {
        return view('auth.events.events');
    }

    public function getCreateView()
    {
        $divisions = Division::where('visible', 1)->orderBy('organisationid')->get();
        $organisations = Organisation::where('visible', 1)->get();
        $page_id = -1;
        $rounds = Round::where('visible', 1)->get();



        return view('auth.events.createevent', compact('divisions', 'rounds', 'organisations', 'rounds', 'page_id'));
    }

    public function getUpdateEventView(Request $request)
    {
        $event = Event::where('eventid', urlencode($request->eventid))->get();
        //dd($event);
        if ($event->isEmpty()) {
            return redirect('divisions');
        }

        $rounds = Round::where('visible', 1)->get();
        $divisions = Division::where('visible', 1)->orderBy('organisationid')->get();
        $organisations = Organisation::where('visible', 1)->get();

        return view('auth.events.updateevent', compact('divisions', 'organisations', 'rounds', 'event'));
    }


    public function create(Request $request)
    {

//        $this->validate($request, [
//            'name' => 'required',
//            'datetime' => ['required', new MaxEventDays],
//            'eventtype' => 'required',
//            'hostclub' => 'required',
//            'location' => 'required',
//            'contact' => 'required',
//            'email' => 'required',
//            'cost' => 'required',
//        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'datetime' => 'required',
            'eventtype' => 'required',
            'hostclub' => 'required',
            'location' => 'required',
            'contact' => 'required',
            'email' => 'required',
            'cost' => 'required',
        ]);

        // Format date
        $date = explode(' - ', $request->input('datetime'));
        $startdate = Carbon::createFromFormat('d/m/Y', $date[0]);
        $enddate = Carbon::createFromFormat('d/m/Y', $date[1]);

        $dayCount = $startdate->diffInDays($enddate);
        if ($dayCount === 0) {
            $dayCount++;
        }

        // Validate that event is more than 10 days and is not a single event
        $validator->after(function ($validator) use ($startdate, $enddate, $request) {
            if ($startdate->diffInDays($enddate) > 9 && $request->input('eventtype') == 0) {
                $validator->errors()->add('eventerror', 'Single Events cannot be more than 10 days');
            }
        })->validate();


        $visible = 0;
        if (!empty($request->input('visible'))) {
            $visible = 1;
        }




        $event = new Event();
        $event->name = htmlentities($request->input('name'));
        $event->email = htmlentities($request->input('email'));
        $event->contact = htmlentities($request->input('contact'));
        $event->eventtype = htmlentities($request->input('eventtype'));
        $event->startdate = htmlentities($startdate);
        $event->enddate = htmlentities($enddate);
        $event->daycount = htmlentities($dayCount);
        $event->hostclub = htmlentities($request->input('hostclub'));
        $event->location = htmlentities($request->input('location'));
        $event->cost = htmlentities($request->input('cost'));
        $event->schedule = htmlentities(trim($request->input('schedule')));
        $event->visible = $visible;
        $event->save();

        return Redirect::route('updateeventview', ['eventid' => urlencode($event->eventid)]);
    }

    public function update(Request $request)
    {
        $event = Event::where('eventid', $request->eventid)->first();

        if (is_null($event)) {
            return Redirect::route('events');
        }

        $this->validate($request, [
            'name' => 'required|unique:events,name,'. $request->eventid. ',eventid',
            'code' => 'required|unique:events,code,'.$event->code.',code',
        ]);

        if ($request->eventid == $event->eventid) {

            $visible = 0;
            if (!empty($request->input('visible'))) {
                $visible = 1;
            }

            $event->name = htmlentities($request->input('name'));
            $event->visible = $visible;
            $event->description = htmlentities($request->input('description'));
            $event->code = htmlentities($request->input('code'));
            $event->agerange = htmlentities($request->input('agerange'));

            $event->save();

            return Redirect::route('events');
        }


    }


}


