<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class OwnerReservationController extends Controller
{
    public function index(Request $request)
    {
        $items = Reservation::with(['restaurant', 'user'])->where('restaurant_id', $request->restaurant_id)->orderBy('datetime', 'asc')->get();
        return response()->json([
            'data' => $items
        ], 200);
    }
}
