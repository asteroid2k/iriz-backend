<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Welcome extends Mailable
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
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcome')->with([
            'user' =>$this->newUser,
            'url' => 'http://127.0.0.1:8000',
        ]);
    }
}
