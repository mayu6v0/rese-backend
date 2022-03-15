<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;

class ReservationController extends Controller
{
    public function index()
    {
        //ログインユーザーの予約情報のみ取得
        $items = Reservation::get_user_reservation();
        return response()->json([
            'data' => $items
        ], 200);

        //全ての予約情報を取得
        // $items = Reservation::with('restaurant')->get();
        // return response()->json([
        //     'data' => $items
        // ], 200);
    }

    public function store(ReservationRequest $request)
    {
        $item = Reservation::create($request->all());
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
        //
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
        $update = [
            'user_id' => $request->user_id,
            'restaurant_id' => $request->restaurant_id,
            'datetime' => $request->datetime,
            'number' => $request->number
        ];
        $item = Reservation::where('id', $reservation->id)->update($update);
        if ($item) {
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
