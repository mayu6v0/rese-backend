<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConfirmationMail;
use DateTime;


class SendConfirmationMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_confirmation_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = new DateTime('today');
        $from = $today->format('Y-m-d H:i:s');
        $until = $today->modify('+1 days')->format('Y-m-d H:i:s');
        $reservations = Reservation::where('datetime','>', $from)->where('datetime', '<', $until)->get();
        foreach($reservations as $reservation) {
            $user = $reservation->user;
            $name = $user->name;
            $datetime = $reservation->datetime;
            $restaurant = $reservation->restaurant->name;
            $number = $reservation->number;
            Mail::to($user)
            ->send(new SendConfirmationMail($name, $restaurant, $datetime, $number));
        }
    }
}
