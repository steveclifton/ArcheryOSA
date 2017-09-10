<?php

namespace App\Http\Controllers;

use App\Division;
use App\Round;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getEventsView()
    {
        return view('admin.events.events');
    }

    public function getCreateView(Request $request)
    {
        $divisions = Division::where('visible', 1)->get();
        $rounds = Round::where('visible', 1)->get();



        return view('admin.events.createevent', compact('divisions', 'rounds'));
    }


    public function create(Request $request)
    {
        $event = new Event();

        $this->validate($request, [
            'name' => 'required|unique:events,name',
            'code' => 'unique:events,code'
        ]);

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


