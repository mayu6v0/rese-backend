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
        $item = Reservation::with(['restaurant', 'user'])->where('id', $request->reservation_id)->first();
        if($item) {
            return response()->json([
                'data' => $item
            ], 200);
        } else {
        return response()->json([
            'message' => 'Not found',
        ], 404);
        }
    }
}
