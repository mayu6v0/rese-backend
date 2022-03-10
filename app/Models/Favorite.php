<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $guarded = array('id');
    // public static $rules = array(
    //     'user_id' => 'required',
    //     'restaurant_id' => 'required',
    //     'datetime' => 'required',
    //     'number' => 'required',
    // );

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
