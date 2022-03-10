<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $guarded = array('id');
    public static $rules = array(
        'name' => 'required',
        'area_id' => 'required',
        'genre_id' => 'required',
        'overview' => 'required',
        'image_url' => 'required'

    );
    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    public function genre()
    {
        return $this->belongsTo('App\Models\Genre');
    }
}

