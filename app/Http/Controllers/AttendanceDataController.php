<?php

namespace App\Http\Controllers;

use App\AttendanceInfo;
use App\Course;
use App\StudentLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AttendanceDataController extends Controller
{
    public function showAllData(Request $request)
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();


        if ($user->isStudent() or !$user) {
            return response()->json([
                'message' => 'Unauthorised Access',
                'status' => 401
            ]);
        }
        $coursenames = $user->getInfo($user->ID)->courses;
        $result = array();

        foreach ($coursenames as $cname) {
            $atd = AttendanceInfo::where('course_code', $cname)->first();
            if (!$atd) {
                continue;
            }
            $cs = new Course();
            $cs = Course::where('code', $cname)->get()->first()->name;
            $atd->name = $cs;
            unset($atd->data_file);
            unset($atd->day_data);
            unset($atd->month_data);
            $atd->ovr = ($atd->cumm_total / ($atd->pop * $atd->num_days) * 100);

            array_push($result, $atd);
        }

        return response()->json([
            'message' => 'All Attendance Data',
            'body' => $result,
            'status' => 200
        ]);


    }


    public function downloadData($code)
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        if (!$user or $user->isStudent()) {
            return response()->json([
                'message' => 'Unauthorised Access',
                'status' => 401
            ]);
        }
        $coursenames = $user->getInfo($user->ID)->courses;

        $attdata = AttendanceInfo::where('course_code', $code)->first();

        if (!$attdata) {
            return response()->json([
                'message' => 'No Datafile found',
                'status' => 400
            ]);
        }

        if (in_array($code, $coursenames)) {
            $path = $attdata->data_file;
            return response()->download($path);
        } else {
            return response()->json([
                'message' => 'You do not have Authorisation for ' . $code . '.',
                'status' => 401
            ]);
        }
    }

    public function getData($code)
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        if (!$user or $user->isStudent()) {
            return response()->json([
                'message' => 'Unauthorised Access',
                'status' => 401
            ]);
        }
        $coursecodes = $user->getInfo($user->ID)->courses;

        $attdata = AttendanceInfo::where('course_code', $code)->first();
        unset($attdata->data_file);

        if (!$attdata) {
            return response()->json([
                'message' => 'No Datafile found',
                'status' => 400
            ]);
        }


        if (in_array($code, $coursecodes)) {
            return response()->json([
                'body' => $attdata,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'You do not have Authorisation for ' . $code . '.',
                'status' => 401
            ]);
        }

    }


    public function getOverview()
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        if (!$user or $user->isStudent()) {
            return response()->json([
                'message' => 'Unauthorised Access',
                'status' => 401
            ]);
        }
        $coursenames = $user->getInfo($user->ID)->courses;
        $obj = array();
        $max = 0;
        $maxobj = array("course" => "", "total" => 0);
        $result = array();

        $atds = AttendanceInfo::whereIn('course_code', $coursenames)->get();

        foreach ($atds as $atd) {

            if ($atd->cumm_total > $max) {
                $maxobj['course'] = $atd->course_code;
                $maxobj['total'] = $atd->cumm_total;
                $max = $atd->cumm_total;
            }
            $obj[$atd->course_code] = $atd->cumm_total;
        }

        $result['info'] = $obj;
        $result['max'] = $maxobj;
        return $result;

    }


//    LOGS
    public function viewLive(Request $request)
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        if ($user->isStudent() or !$user) {
            return response()->json([
                'message' => 'Unauthorised Access',
                'status' => 401
            ]);
        }
        $coursenames = $user->getInfo($user->ID)->courses;

        //$slogs = StudentLog::orderBy('started_at','desc')->get();
        $slogs = StudentLog::whereIn('ccode', $coursenames)->where('isLive','=', true)
            ->get(['ccode', '_id', 'started_at']);

            return $slogs;

    }

    public function countLive()
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        $coursenames = $user->getInfo($user->ID)->courses;

        //$slogs = StudentLog::orderBy('started_at','desc')->get();
        $slogs = StudentLog::whereIn('ccode', $coursenames)->where('isLive', true)
            ->count();
        return $slogs;
    }

    public function recentLog()
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        $coursenames = $user->getInfo($user->ID)->courses;

        $slogs = StudentLog::orderBy('started_at','desc')->where('isLive', false)->first();
        $date = $slogs->started_at;
        $ago = $date->diffForHumans(Carbon::now(),['options'=>0]);
        $ago = str_replace("before", "ago", $ago);

        $slogs->ago = $ago;
        return $slogs;
    }

    public function viewLiveInfo($id)
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        if ($user->isStudent() or !$user) {
            return response()->json([
                'message' => 'Unauthorised Access',
                'status' => 401
            ]);
        }
        $coursenames = $user->getInfo($user->ID)->courses;

        $log = StudentLog::where('_id', $id)->first();
        $date = $log->started_at;
        $ago = $date->diffForHumans(Carbon::now(),['options'=>0]);
        $ago = str_replace("before", "ago", $ago);
        $log->ago = $ago;

        if (!$log) {
            return response()->json([
                'message' => 'Not found!',
                'status' => 400
            ]);
        }
        if (in_array($log->ccode, $coursenames)) {

                return response()->json([
                    'log'=>$log->log,
                    'ago'=>$log->ago
                ]);

        } else {
            return response()->json([
                'message' => 'You do not have Authorisation for ' . $log->ccode . '.',
                'status' => 401
            ]);
        }

    }

}
