<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        //s3アップロード
        $image = $request->file('photo');
        // バケットの`image`フォルダへアップロード
        // $path = Storage::disk('s3')->put('image', $image);
        $path = Storage::disk('s3')->put('/', $image, 'public');
        // echo asset('storage/file.txt');
        // アップロードした画像のフルパスを取得
        $image_url = Storage::url($path);
        // $image_url2 = asset($image_url);
        // return $image_url;
        $restaurant_id = auth()->user()->restaurant_id;
        $new_image = Image::create([
            'image_url' => $image_url,
            'restaurant_id' => $restaurant_id
        ]);
        return response()->json([
            'data' => '画像保存に成功しました'
        ], 201);
    }
}
