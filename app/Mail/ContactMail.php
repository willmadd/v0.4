<?php

namespace App\Mail;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $agency;
    public $contact_us;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->name = $request['name'];
        $this->email = $request['email'];
        $this->agency = $request['agency'];
        $this->contact_us = $request['contact_us'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('message from PNR Converter')
        ->from('noreply@pnrconverter.com')
        ->to('william@pnrconverter.com')
        ->view('email.contactmail');
    }
}
