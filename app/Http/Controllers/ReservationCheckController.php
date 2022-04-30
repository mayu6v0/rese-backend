<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Reservation;


class ReservationCheckController extends Controller
{
    public function reservationCheck(Request $request)
    {
        // 署名チェックに成功したら(sined middleware)、reservation_idから予約情報取得
        $items = Reservation::with(['restaurant', 'user'])->where('id', $request->reservation_id)->first();
        return response()->json([
            'data' => $items
        ], 200);
    }
}
