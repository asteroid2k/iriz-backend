<?php

namespace App\Jobs;

use App\Mail\ResetPassword;
use App\Mail\Welcome;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user, $mail;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $mail
     */
    public function __construct($user, $mail)
    {


        Log::info("Sending email to ". $user);
        $this->user=$user;
        $this->mail=$mail;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info("Handle");

        $uu = User::where('email','=',$this->user)->first();
        if ($this->mail === 1) {
            Mail::to($uu)->send(new Welcome($uu));
        } elseif ($this->mail === 2) {
            Mail::to($uu)->send(new ResetPassword($uu));
        }
    }

}
