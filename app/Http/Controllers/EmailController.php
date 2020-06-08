<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function verifyMail($email)
    {

        $user = User::where('email', '=', $email);
        if (!$user->count()) {
            return response()->json([
                "message" => "Email Verification Failed. User does not Exist",
                "status" => 400
            ]);
        }
        $user = $user->first();
        $user->isVerified = true;
        $user->save();
        return response()->json([
            "message" => "Email Verification Successful",
            "status" => 200
        ]);

    }

    public function resendEmails(Request $request)
    {
        $message = [
            'email.exists' => 'User with this email does not exist.'
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users'
        ], $message);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Verification mail was not sent",
                "body" => $validator->errors()->first(),
                "status" => 400
            ],400);
        }

        $user = User::where('email','=',$request['email']);
        if(!$user->count()){
            return response()->json([
                "message" => "User with this email does not exist.",
                "status" => 400
            ],400);
        }
        $user = $user->first();

        try {
            EmailJob::dispatch($user->email,1)->delay(now()->addSeconds(10));

        }catch (\Exception $e){
            return response()->json([
                "message" => "Verification mail will be sent",
                "status" => 200
            ],200);
        }
        return response()->json([
            "message" => "Verification mail has been sent",
            "status" => 200
        ],200);

    }
}
