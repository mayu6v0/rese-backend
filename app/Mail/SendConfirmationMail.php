<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    // protected $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $restaurant, $datetime, $number, $signed_url)
    {
        $this->name = $name;
        $this->restaurant = $restaurant;
        $this->datetime = $datetime;
        $this->number = $number;
        $this->signed_url = $signed_url;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $prefix = config('app.frontend_url') . config('app.reservation_check_url');
        return $this->from('rese@example.com', 'Rese')
        ->view('emails.confirmation_mail')
        ->subject('ご予約確認メール')
        ->with([
            'name' => $this->name,
            'restaurant' => $this->restaurant,
            'datetime' => $this->datetime,
            'number' => $this->number,
            'signed_url' => $prefix . urlencode($this->signed_url)
        ]);
    }
}
