<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $items = Favorite::get_user_favorite();
        return response()->json([
            'data' => $items
        ], 200);
        // 全てのお気に入り情報を取得
        // $items = Favorite::with(['restaurant.area', 'restaurant.genre'])->get();
        // return response()->json([
        //     'data' => $items
        // ], 200);
    }

    public function store(Request $request)
    {
        $item = Favorite::create($request->all());
        return response()->json([
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    public function destroy(Favorite $favorite)
    {
        $item = Favorite::where('id', $favorite->id)->delete();
        if($item) {
            return response()->json([
                'message' => 'Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }
}
