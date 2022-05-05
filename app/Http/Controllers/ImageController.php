<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ImageRequest;


use App\Models\Image;

class ImageController extends Controller
{
    public function index()
    {
        $restaurant_id = auth()->user()->restaurant_id;
        $items = Image::where('restaurant_id', $restaurant_id)->get();
        return response()->json([
            'data' => $items
        ], 200);
    }

    public function create(Request $request)
    {
        //requestからfileを取得
        $image = $request->file('uploadimage');

        //s3のimageフォルダにアップロード
        $path = Storage::disk('s3')->putFile('image', $image, 'public');

        // アップロードした画像のURLを取得
        $image_url = Storage::URL($path);

        $restaurant_id = auth()->user()->restaurant_id;
        Image::create([
            'image_url' => $image_url,
            'restaurant_id' => $restaurant_id
        ]);
        return response()->json([
            'data' => '画像保存に成功しました'
        ], 201);
    }
}
