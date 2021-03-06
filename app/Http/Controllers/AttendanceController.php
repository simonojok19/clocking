<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeRequest;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $time = Carbon::now();
        return Attendance::orderBy('created_at', 'DESC')
            -> whereDate('date', $time->toDateString())
            -> get();
    }

    public function userAttendance(Request $request) {
        $user_id = $request -> user() -> id;
        $time = Carbon::now();

        return Attendance::where('user_id', '=', $user_id)
            ->whereDate('date', $time->toDateString())
            -> get();
    }



    public function time_in(Request $request) {
        $user_id = $request -> user() -> id;
        $time = Carbon::now();

        $attendance = Attendance::where('user_id', '=', $user_id)
            ->whereDate('date', $time->toDateString())
            -> get();

        if($attendance->isEmpty()) {
            $attendance = new Attendance;
            $attendance -> user_id = $user_id;
            $attendance -> time_in = $time -> toTimeString();
            $attendance -> date = $time -> toDateString();
            $attendance -> save();
        }

        return [
            "times" => [
                "time_in" => true,
                "time_out" => false
            ]
        ];
    }

    public function time_out(Request $request) {
        $user_id = $request -> user() -> id;
        $time = Carbon::now();

        $attendance = Attendance::where('user_id', '=', $user_id)
            ->whereDate('date', $time->toDateString())
            -> get() -> first();

        if($attendance == null) {
            return [
                "times" => [
                    "time_in" => false,
                    "time_out" => false
                ]
            ];
        }

        $attendance -> time_out = $time -> toTimeString();
        $attendance -> save();
        return [
            "times" => [
                "time_in" => $attendance -> time_in != null,
                "time_out" => $attendance -> time_out != null
            ]
        ];
    }
}
