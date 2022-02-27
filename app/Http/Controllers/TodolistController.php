<?php

namespace App\Http\Controllers;

use App\Models\Todolist;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;

class TodolistController extends Controller
{

    /**
     * Home view.
     *
     */
    public function index()
    {
        $todolist = Todolist::all();
        return view('home', compact('todolist'));
    }

    /**
     * Display todolist in user timezone.
     *
     */
    public function getList(Request $request)
    {
        $validator = $request->validate([
            'timezone' => 'required'
        ]);

        if ($validator) {
            $timezone_name = $request->timezone;

            $data = Todolist::all();
            foreach ($data as $value) {
                
                date_default_timezone_set($value->timezone);
                $datetime = new DateTime($value->deadline);
                $userTime = new DateTimeZone($timezone_name);
                $datetime->setTimezone($userTime);
                $userTime = $datetime->format('h:i A, jS F');

                $value->deadline = $userTime;
            }
            return response()->json(['success' => $data]);
        }
    }

    /**
     * Store todo task in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'task' => 'required',
            'date' => 'required',
            'time' => 'required'
        ]);

        if ($validator) {
            $timezone_offset_minutes = $request->timezone;
            $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false); 

            $payload = [
                'task' => $request->task,
                'deadline' => $request->date.' '.$request->time,
                'timezone' => $timezone_name
            ];

            Todolist::create($payload);
            return back();
        }
    }
}
