<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Mail\SendReservationMail;
use App\Mail\SendReservationChangeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;




class ReservationController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $items = Reservation::with('restaurant')->where('user_id', $user_id)->orderBy('datetime', 'asc')->get();
        return response()->json([
            'data' => $items
        ], 200);
    }

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

        return response()->json([
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        $item = Reservation::with('restaurant')->where('id', $reservation->id)->get();
        if ($item) {
            return response()->json([
                'data' => $item
            ],
                200
            );
        } else {
            return response()->json([
                'message' => 'Not found',
            ],
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $user = auth()->user();
        $update = [
            'user_id' => $request->user_id,
            'restaurant_id' => $request->restaurant_id,
            'datetime' => $request->datetime,
            'number' => $request->number
        ];
        $item = Reservation::where('id', $reservation->id)->update($update);

        if ($item) {
            $update_reservation = Reservation::where('id', $reservation->id)->first();
            $name = $user->name;
            $reservation_id = $update_reservation->id;
            $datetime = $update_reservation->datetime;
            $restaurant = $update_reservation->restaurant->name;
            $number = $update_reservation->number;
            $signed_url = URL::signedRoute('reservation.check', ['reservation_id' => $reservation_id]);

            Mail::to($user)
            ->send(new SendReservationChangeMail($update_reservation, $name, $datetime, $restaurant, $number, $signed_url));

            return response()->json([
                'message' => 'Updated successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        $item = Reservation::where('id', $reservation->id)->delete();
        if($item) {
            return response()->json([
                'message' => 'Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ],404);
        }
    }
}
