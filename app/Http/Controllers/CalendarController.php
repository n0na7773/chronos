<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\User_calendar;
use App\Models\User;

class CalendarController extends Controller
{

    public function createCalendar(Request $request)
    {

        if(!$this->check_auth($request)) {
            return response("You have no auth", 401);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'string',
        ]);
        $user_id = $this->get_auth($request)->id;

        $validated["user_id"] = $user_id;

        $calendar = Calendar::create($validated);

        User_calendar::create([
            'user_id' => $user_id,
            'calendar_id' => $calendar->id,
        ]);

        return $calendar;

    }

    public function getUsersForCalendar(Request $request, $id)
    {
        if(!$this->check_auth($request)) {
            return response("You have no auth", 403);
        }

        $users = \DB::table("user_calendars")->where("calendar_id", $id)->get();

        foreach($users as $user) {
            $user->users_data = User::find($user->user_id);
        }

        return $users;
    }

    public function getCalendarsForUser(Request $request)
    {
        if(!$user = $this->get_auth($request)) {
            return response("You have no auth", 403);
        }

        $calendars = \DB::table("user_calendars")->where("user_id", $user->id)->get();

        foreach($calendars as $calendar) {
            $calendar->calendar_data = Calendar::find($calendar->calendar_id);
        }

        return $calendars;
    }

    public function getCalendar(Request $request, $id)
    {
        if(!$calendar = Calendar::find($id))
            return response("Calendar not found", 404);

        if(!$this->check_auth($request)) {
            return response("You have no auth", 401);
        }

        $calendar = \DB::table("calendars")->where("id", $id)->first();

        if(!$calendar){
            return response("Calendar not found", 404);
        }

        $calendar->users = \DB::table("user_calendars")->where("calendar_id", $id)->pluck("user_id")->toArray();

        if(in_array($this->get_auth($request)->id, $calendar->users)) {
            return $calendar;
        }

        return response("Something wring", 403);

    }

    public function update(Request $request, $id)
    {


        if(!$calendar = Calendar::find($id)) {
            return response("Calendar not found", 404);
        }

        if(!$this->check_auth($request)) {
            return response("You have no auth", 401);
        }

        if($this->get_auth($request)->id == $calendar->user_id) {
            $validated = $request->validate([
                'title'=> 'string',
                'description'=> 'string',
            ]);

            $calendar->update($validated);
            return $calendar;
        }

        return response("Something wring", 403);
    }

    public function destroy(Request $request, $id)
    {

        if(!$calendar = Calendar::find($id)) {
            return response("Calendar not found", 404);
        }

        if(!$this->check_auth($request)) {
            return response("You have no auth", 401);
        }

        if($this->get_auth($request)->id == $calendar->user_id && $calendar->main == 0) {
            return Calendar::destroy($id);
        }

        return response("Something wring", 403);
    }
}
