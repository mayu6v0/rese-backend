<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = array('id');
    protected $fillable = [
        'user_id', 'restaurant_id', 'datetime', 'number'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public static function get_user_reservation()
    {
        //ユーザーidを直接受け取る
        // $user_id = $reservation_data->user_id;
        $user_id = auth()->user()->id;
        $items = Reservation::with('restaurant')->where('user_id', $user_id)->orderBy('datetime', 'asc')->get();
        return $items;
    }


}
