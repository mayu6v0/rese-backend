<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = array('id');
    protected $fillable = [
        'user_id', 'restaurant_id', 'reservation_id', 'rating', 'title', 'review'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
