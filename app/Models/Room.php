<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'room_number',
        'type_id',
        'price',
    ];

    public function type() {
        return $this->hasOne(RoomType::class, 'id', 'type_id');
    }

    public function bookings() {
        return $this->hasMany(Booking::class, 'room_id', 'id');
    }
}
