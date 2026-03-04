<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_number',
        'room_order',
        'roomtype_id',
        'room_name',
        'capacity',
        'price_per_night',
        'description',
        'status',
        'thumbnail_image', // Add this field
    ];
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function room_type()
    {
        return $this->belongsTo(RoomType::class, 'roomtype_id');
    }

    public function roomimage()
    {
        return $this->hasMany(related: RoomDetail::class, foreignKey: 'room_id');
    }
}
