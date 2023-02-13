<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewEventNotify extends Mailable
{
    use Queueable, SerializesModels;

    public $detail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($detail)
    {
        //
        $this->detail= $detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        $viewName='email.new-event-notify';

        return $this->from('ashirmuneer123@gmail.com','Event Management System')

            ->view($viewName)

            ->subject('New Event Added');
    }
}
