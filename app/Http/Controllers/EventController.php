<?php

namespace App\Http\Controllers;

use App\Division;
use App\Organisation;
use App\Round;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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

        $this->validate($request, [
            'name' => 'required',
            'datetime' => 'required',
            'hostclub' => 'required',
            'location' => 'required',
            'contact' => 'required',
            'email' => 'required',
            'cost' => 'required',
        ]);

        $visible = 0;
        if (!empty($request->input('visible'))) {
            $visible = 1;
        }

        // format the date
        $date = explode(' - ', $request->datetime);
        $datediff = date_diff(date_create($date[0]), date_create($date[1]));
        $datediff = $datediff->days + 1;


        $event = new Event();
        $event->name = htmlentities($request->input('name'));
        $event->email = htmlentities($request->input('email'));
        $event->contactname = htmlentities($request->input('contact'));
        $event->startdate = htmlentities($date[0]);
        $event->enddate = htmlentities($date[1]);
        $event->daycount = htmlentities($datediff);
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


