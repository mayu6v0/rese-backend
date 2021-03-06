<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //ログインユーザーのレビュー情報のみ取得
        $user_id = auth()->user()->id;
        $items = Review::with('restaurant')->where('user_id', $user_id)->get();
        return response()->json([
            'data' => $items
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReviewRequest $request)
    {
        $item = Review::create($request->all());
        return response()->json([
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    // public function show(Review $review)
    // {
    //     $item = Review::with('restaurant')->where('id', $review->id)->get();
    //     if($item) {
    //         return response()->json([
    //             'data' => $item
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'Not found',
    //         ], 404);
    //     }
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Review $review)
    // {
    //     $update = [
    //         'grade' => $request->grade,
    //         'title' => $request->title,
    //         'review' => $request->review
    //     ];
    //     $item = Review::where('id', $review->id)->update($update);
    //     if($item) {
    //         return response()->json([
    //             'message' => 'Updated successfully',
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'Not found',
    //         ], 404);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Review $review)
    // {
    //     $item = Review::where('id', $review->id)->delete();
    //     if($item) {
    //         return response()->json([
    //             'message' => 'Deleted successfully',
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'Not found',
    //         ], 404);
    //     }
    // }
}
