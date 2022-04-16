<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    // protected $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item, $name, $datetime, $restaurant, $number)
    {
        $this->item = $item;
        $this->name = $name;
        $this->restaurant = $restaurant;
        $this->datetime = $datetime;
        $this->number = $number;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('rese@example.com', 'Rese')
        ->view('emails.reservation_mail')
        ->subject('ご予約完了メール')
        ->with([
            'item' => $this->item,
            'name' => $this->name,
            'restaurant' => $this->restaurant,
            'datetime' => $this->datetime,
            'number' => $this->number,
        ]);
    }
}
