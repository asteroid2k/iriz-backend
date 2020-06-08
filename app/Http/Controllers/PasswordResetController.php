<?php

namespace App\Http\Controllers;

use App\ForgotPasswordToken;
use App\Jobs\EmailJob;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class PasswordResetController extends Controller
{
    use SoftDeletes;


    #Function Changes Password of Authenticated User
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required|min:8',
            'newPassword' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "message" => "Password Reset Failed",
                "body" => $validator->errors()->first(),
                "status" => 400
            ]);
        }

        $nrequest = Request::create('/api/user', 'GET');
        $resp = Route::dispatch($nrequest)->getData();
        $user = User::where('email', '=', $resp->email)->first();

        if ($user->isStudent() or !$user) {
            return response()->json([
                'message' => 'Unauthorised Access',
                'status' => 401
            ]);
        }

        if (!$user->isVerified) {
            return response()->json([
                "message" => "User Email not verified",
                "status" => 401
            ]);
        }

        if (Hash::check($request['oldPassword'], $user->password)) {
            $user->password = Hash::make($request->input('newPassword'));
            $user->save();
            return response()->json([
                "message" => "Your password has been Successfully changed",
                "status" => 200
            ]);
        } else {
            return response()->json([
                "message" => "Wrong old password",
                "status" => 401
            ]);
        }


    }


    #Function sends  Password Reset Verification Code
    public function forgotMailer(Request $request)
    {
        $message = [
            'email.exists' => 'User with this email does not exist'
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users'
        ], $message);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Password reset request Failed",
                "body" => $validator->errors()->first(),
                "status" => 400
            ]);
        }
        $email = $request['email'];
        $newUser = User::where('email', $email);#Get User Object

        if (!$newUser->count()) {
            return response()->json([
                "body" => "User email does not exist",
                "status" => 400
            ]);

        }
        $newUser = $newUser->first();

        try {
            EmailJob::dispatch($newUser->email, 2)->delay(Carbon::now()->addSeconds(10));
        } catch (Exception $e) {
            return response()->json([
                "message" => "Password reset code will be sent",
                "status" => 200
            ]);
        }

        return response()->json([
            "message" => "Password reset code is sent to ". $newUser->email,
            "status" => 200
        ]);


    }


    # RESET FORGOT PASSWORD
    public function resetForgot(Request $request)
    {
        #Error Messages for Validation
        $messages = [
            'code.exists' => 'Verification Code is Invalid',
        ];
        #Validate Input
        $validator = Validator::make($request->all(), [
            'code' => 'required|min:8|exists:forgot_password_tokens',
            'newPassword' => 'required|confirmed|min:8'
        ], $messages);
        if ($validator->fails()) {#Failed Validations
            return response()->json([
                "message" => "Failed",
                'body' => $validator->errors()->first(),
                "status" => 400]);
        }

        #Input passed Validation
        $code = $request['code'];
        $newPassword = $request['newPassword'];

        $token = ForgotPasswordToken::where('code', '=', $code);#Get Reset Code Object

        $now = Carbon::now();
        if (!$token->count() || ($now->diffInMinutes($token->first()->expire_at) <= 0)) {#check if code is expired
            if ($token->count()) {
                $token->delete();
            }
            return response()->json([
                "body" => "Verification code has Expired or is Invalid",
                "status" => 400
            ]);
        }

        $token = $token->first();
        $newUser = $token->user()->get();
        if (!$newUser->count()) {#User does not exist
            return response()->json([
                "body" => "User does not exist",
                "status" => 400
            ]);
        }

        $newUser = $token->user()->first();#retrieve User who requested Password Reset


        if ($newUser->isVerified) {#check if user has verified email
            $newUser->password = Hash::make($newPassword);
            $newUser->save();
            $token->delete();#delete used Verification Code
            return response()->json([
                "message" => "Your password has been successfully changed",
                "status" => 200
            ]);
        } else {
            return response()->json([
                "body" => "User Email not verified. Please check your Mailbox",
                "status" => 400
            ]);
        }

    }


}
