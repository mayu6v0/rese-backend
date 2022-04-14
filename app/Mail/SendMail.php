<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    // protected $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $text, $name)
    {
        $this->title = $title;
        $this->text = $text;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('rese@example.com', 'Rese')
        ->view('emails.mail')
        ->subject($this->title)
        ->with([
            'name' => $this->name,
            'text' => $this->text,
        ]);
    }
}
