<?php

namespace App\Http\Controllers;

use App\StudentInfo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AccountController extends Controller
{
    public function editDetails(Request $request)
    {

        //Get User with Token
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        #Validate DATA
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email,' . $user->id . ',_id',
            'phone' => 'digits:9|unique:users,phone,' . $user->id . ',_id',
            'first_name' => 'string',
            'last_name' => 'string',
            'avatar' => 'image|mimes:png,jpeg,jpg|mimetypes:image/jpeg,image/png,image/jpg',
            'sex' => [Rule::in(['Male', 'Female', 'Other', 'Neutral']), 'string'],
            'title' => [Rule::in(['Mr.', 'Mrs.', 'Miss', 'Prof.', 'Dr.']), 'string']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "message" => "Edit Failed",
                "body" => $validator->errors()->first(),
                "status" => 400
            ]);
        }

        if ($request->first_name) {
            $user->first_name = $request->first_name;
            try {
                $user->save();
                return response()->json([
                    "message" => "Name Updated Succesfully",
                    "status" => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "message" => "A Problem Occured",
                    "status" => 500
                ]);
            }
        }

        if ($request->last_name) {
            $user->last_name = $request->last_name;
            try {
                $user->save();
                return response()->json([
                    "message" => "Name Updated Succesfully",
                    "status" => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "message" => "A Problem Occured",
                    "status" => 500
                ]);
            }
        }

        if ($request->phone) {
            $user->phone = $request->phone;
            try {
                $user->save();
                return response()->json([
                    "message" => "Phone Number Updated Succesfully",
                    "status" => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "message" => "A Problem Occured",
                    "status" => 500
                ]);
            }
        }

        if ($request->email) {
            $user->email = $request->email;
            try {
                $user->save();
                return response()->json([
                    "message" => "Email Updated Succesfully",
                    "status" => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "message" => "A Problem Occured",
                    "status" => 500
                ]);
            }
        }

        if ($request->title) {
            $user->title = $request->title;
            try {
                $user->save();
                return response()->json([
                    "message" => "Title Updated Succesfully",
                    "status" => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "message" => "A Problem Occured",
                    "status" => 500
                ]);
            }
        }

        if ($request->sex) {
            $user->sex = $request->sex;
            try {
                $user->save();
                return response()->json([
                    "message" => "Sex Updated Succesfully",
                    "status" => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "message" => "A Problem Occured",
                    "status" => 500
                ]);
            }
        }

    }

    public function deleteUser()
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => 400
            ]);
        }
    }

    public function getStudentInfo($id)
    {
        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        if (!$user or $user->isStudent()) {
            return response()->json([
                'message' => 'Unathorised Access',
                'status' => 400
            ]);
        }

        $info = StudentInfo::where('student_id',$id)->first();
        if (!$info){
            return response()->json([
                'message' => 'Student Info Not Found',
                'status' => 400
            ]);
        }else{
            return $info;
        }

    }
}
