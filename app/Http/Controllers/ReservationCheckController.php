<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Reservation;


class ReservationCheckController extends Controller
{
    // public function generate_signed_url(Request $request) {
    
    //     $reservation_id = $request->reservation_id;
    //     // 署名付きURLの生成
    //     $signed_url = URL::signedRoute('reservation.check', ['reservation_id' => $reservation_id]);

    //     return $signed_url;
    // }


    public function reservationCheck(Request $request)
    {
        // 署名チェックに成功したら(sined middleware)、reservation_idから予約情報取得
        $items = Reservation::with(['restaurant', 'user'])->where('id', $request->reservation_id)->first();
        return response()->json([
            'data' => $items
        ], 200);
    }
}
