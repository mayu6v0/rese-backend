<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Mail\SendReservationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Stripe\Stripe;
use App\Models\User;
use Stripe\Checkout\Session;

class ReservationWithCardController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $item = Reservation::create($request->all());
        $user = auth()->user();
        $name = $user->name;
        $reservation_id = $item->id;
        $datetime = $item->datetime;
        $restaurant = $item->restaurant->name;
        $number = $item->number;
        $signed_url = URL::signedRoute('reservation.check', ['reservation_id' => $reservation_id]);

        Mail::to($user)
            ->send(new SendReservationMail($item, $name, $datetime, $restaurant, $number, $signed_url));

        header(
            'Content-Type: application/json',
        );

        Stripe::setApiKey('sk_test_51KvglMLC0jpceEiOw0evJrnvNHFvAEgQpL9zRnS454ONSihXkmTsgQOR9uJ5SRIw8Hr8vea8oJyyWFTWjX77eXCs00M20tIOBJ');

        return $session = Session::create([
            'line_items' => [
                [
                    'price' => 'price_1KvgpFLC0jpceEiO8RC8jZbs',
                    'quantity' => $request->number,
                ]
            ],
            'mode' => 'payment',
            'success_url' => config('app.frontend_url') . '/done',
            'cancel_url' => config('app.frontend_url') . '/payment/cancel',
        ]);
    }
}
