<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function create(Request $request)
    {
        //s3アップロード
        $image = $request->file('photo');
        // バケットの`image`フォルダへアップロード
        // $path = Storage::disk('s3')->put('image', $image);
        $path = Storage::disk('s3')->put('/', $image, 'image');
        // アップロードした画像のフルパスを取得
        // $image_path = Storage::url($path);
        return $path;
    }
}
