<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User_event;
use DB;

class EventController extends Controller
{
    public function createEventForCalendar(Request $request, $id)
    {
        $calendar = \DB::table("calendars")->where("id", $id)->get()->first();

        if(!$calendar){
            return response("Calendar not found", 404);
        }

        if(!$this->check_auth($request)){
            return response("You have no auth", 404);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'event_date' => 'required',
            'category' => 'required|in:arrangement,reminder,task',
            'color' => 'required',
        ]);
        $user_id = $this->get_auth($request)->id;

        $validated["calendar_id"] = $id;
        $validated['user_id'] = $user_id;

        $event = Event::create($validated);

        User_event::create([
            'user_id' => $user_id,
            'event_id' => $event->id,
        ]);

        return $event;
    }

    public function getEventsForCalendar(Request $request, $id)
    {
        if(!$this->check_auth($request)){
            return response("You have no auth", 404);
        }

        $calendar = \DB::table("calendars")->where("id", $id)->first();

        if(!$calendar){
            return response("Calendar not found", 404);
        }

        $calendar->users = \DB::table("user_calendars")->where("calendar_id", $id)->pluck("user_id")->toArray();

        if(in_array($this->get_auth($request)->id, $calendar->users)) {
            return \DB::table("events")->where("calendar_id", $id)->get();
        }

        return response($calendar->users, 403);

    }

    public function getEventById(Request $request, $id)
    {
        if(!$this->check_auth($request)){
            return response("You have no auth", 404);
        }

        return \DB::table("events")->where("id", $id)->get();;

    }

    public function delEventById(Request $request, $id)
    {
        if(!$this->check_auth($request)){
            return response("You have no auth", 404);
        }

        return \DB::table("events")->where("id", $id)->delete();;

    }
}
