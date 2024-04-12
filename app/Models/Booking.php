<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'start_date',
        'end_date',
        'people',
        'with_breakfast',
        'with_lunch',
        'with_dinner',
        'with_minibar',
        'with_laundry',
        'total_price'
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function room() {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
}
