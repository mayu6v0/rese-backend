<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class RestaurantReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //各飲食店のレビュー情報を取得
        $items = Review::with('restaurant')->where('restaurant_id', $request->restaurant_id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => $items
        ], 200);
    }
}
