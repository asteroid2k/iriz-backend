<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CourseController extends Controller
{
    public function showCourses(Request $request){

        //get user with token
        $nrequest = request::create('/api/user', 'get');
        $resp = route::dispatch($nrequest)->getdata();
        $user = user::where('email','=',$resp->email)->first();

        $coursenames = $user->getInfo($user->ID)->courses;

        $result = Course::whereIn('code',$coursenames)->get(['code','credits','name','description']);

        return response()->json([
            'body'=> $result,
            'status' =>200
        ]);

    }

    public function name($code){
        if(Course::where('code',$code)->first()){
            return response()->json([
                'name'=>Course::where('code',$code)->first()->name
            ]);
        }else{
            return null;
        }
    }

    public function getStudentList($code){
        //get user with token
        $nrequest = request::create('/api/user', 'get');
        $resp = route::dispatch($nrequest)->getdata();
        $user = user::where('email','=',$resp->email)->first();

        $coursenames = $user->getInfo($user->ID)->courses;

        $result = Course::whereIn('code',$coursenames)->where('code',$code)->first();
        if (!$result){
            return response()->json([
                'message'=>"Course does not exist or you do not have authorisation for this course",
                'status'=>400
            ]);
        }
        $code = $result->code;
        $file = $result->student_list;
        unset($result);
            //todo:Check if file exists
        if (!file_exists($file)){
            return response()->json([
                'message'=>"Class list not found.",
                'status'=>400
            ]);
        }
        $list = explode("\n",file_get_contents($file));
        return response()->json([
            'body' => $list,
            'status' => 200
        ]);


    }

}
