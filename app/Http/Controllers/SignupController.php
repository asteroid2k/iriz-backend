<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SignupController extends Controller
{
    const TutorRole = 'TUTOR';
    const StudentRole = 'STUDENT';

    public function registerStudent(Request $request)
    {
        //check for validation errors
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|unique:users|string',
            'id' => 'required|bail|unique:users|digits:8',
            'phone' => 'bail|unique:users|required|digits:9',
            'password' => 'required|string|confirmed|min:8',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'avatar' => 'image|mimes:png,jpeg,jpg|mimetypes:image/jpeg,image/png,image/jpg',
            'sex' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => " Registration Failed",
                "body" => $validator->errors()->first(),
                "status" => 400,
            ]);
        }

        try {
            //Create User
            $newUser = new User;
            $email = $request['email'];
            $newUser->sex = $request['sex'];
            $newUser->ID = $request['id'];
            $newUser->email = $request['email'];
            $newUser->password = Hash::make($request['password']);
            $newUser->first_name = $request['firstname'];
            $newUser->last_name = $request['lastname'];
            $newUser->phone = $request['phone'];
            $newUser->role = self::StudentRole;
            $newUser->isVerified = false;

            // Save Avatar
            if ($request->hasFile('avatar')) {
                $this->saveAvatar($newUser, $request['avatar'], $email);
            }


        } catch (Exception $e) {
            return response()->json([
                "message" => "Server is unavailable.Please Try again Later",
                "status" => 500
            ]);

        }

        //Save User in Database
        $newUser->save();

        //Send Verification Mail
        try {
            EmailJob::dispatch($newUser->email, 1)->delay(Carbon::now()->addSeconds(10));

        } catch (Exception $e) {
            $newUser->isVerified = "pending";
            $newUser->save();
            $success['token'] = $newUser->createToken('MyApp')->accessToken;
            return response()->json([
                "message" => "Student Account created.",
                "token" => $success['token'],
                "status" => 2011
            ]);
        }


        //generate Bearer Token
        $success['token'] = $newUser->createToken('MyApp')->accessToken;
        return response()->json([
            "message" => "Student Account created.",
            "token" => $success['token'],
            "status" => 201
        ]);


    }


    public function registerTutor(Request $request)
    {
        //check for validation errors

        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|unique:users|string',
            'id' => 'required|bail|unique:users|digits:8',
            'phone' => 'bail|unique:users|required|digits:9',
            'password' => 'required|string|confirmed|min:8',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'avatar' => 'image|mimes:png,jpeg,jpg|mimetypes:image/jpeg,image/png,image/jpg',
            'sex' => 'required',
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => " Registration Failed",
                "body" => $validator->errors()->first(),
                "status" => 400,
            ]);
        }

        try {
            //Create User
            $newUser = new User;
            $email = $request['email'];
            $newUser->title = $request['title'];
            $newUser->sex = $request['sex'];
            $newUser->ID = $request['id'];
            $newUser->email = $request['email'];
            $newUser->password = Hash::make($request['password']);
            $newUser->first_name = $request['firstname'];
            $newUser->last_name = $request['lastname'];
            $newUser->phone = $request['phone'];
            $newUser->role = self::TutorRole;
            $newUser->isVerified = false;

            // Save Avatar
            if ($request->hasFile('avatar')) {
                $this->saveAvatar($newUser, $request['avatar'], $email);
            }

        } catch (Exception $e) {
            return response()->json([
                "message" => "Server is unavailable.Please Try again Later",
                "status" => 500
            ]);
        }

        //Save
        $newUser->save();

        //Send Mail
        try {
            EmailJob::dispatch($newUser->email, 1)->delay(Carbon::now()->addSeconds(10));

        } catch (Exception $e) {
            $newUser->isVerified = "pending";
            $newUser->save();
            $success['token'] = $newUser->createToken('MyApp')->accessToken;
            return response()->json([
                "message" => "Tutor Account created.",
                "token" => $success['token'],
                "status" => 2011
            ]);
        }

        //generate Bearer Token
        $success['token'] = $newUser->createToken('MyApp')->accessToken;
        return response()->json([
            "message" => "Tutor Account created.",
            "token" => $success['token'],
            "status" => 201
        ]);
    }

// Function to save Avatar
    public function saveAvatar($user, $avatar, $name)
    {
        $image = $avatar;
        $input['imagename'] = $name . '.' . $image->getClientOriginalExtension();
        $path = public_path('\images\avatars');
        $image->move($path, $input['imagename']);
        $user->avatar = $path . '\\' . $input['imagename'];

    }

}
