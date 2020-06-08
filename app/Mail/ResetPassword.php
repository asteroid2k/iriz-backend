<?php

namespace App\Mail;

use App\ForgotPasswordToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $newUser;

    /**
     * Create a new message instance.
     *
     * @param $newUser
     */
    public function __construct($newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Build the message.
     *
     * @return ResetPassword
     * @throws Exception
     */
    public function build()
    {
        try {
            $newToken = new ForgotPasswordToken;
            $newToken->user_id = $this->newUser->_id;
            $newToken->code = Str::random(8);
            $tokenExpiry = new Carbon();
            $tokenExpiry->addHours(2);
            $newToken->expire_at = $tokenExpiry;
            $newToken->save();
        } catch (Exception $e) {
            Log::info($e);
        }

        $code = $newToken->code;
        $name = $this->newUser->title . ' ' . $this->newUser->last_name;

        return $this->markdown('emails.resetPassword')->with(compact('code', 'name'));
    }
}
