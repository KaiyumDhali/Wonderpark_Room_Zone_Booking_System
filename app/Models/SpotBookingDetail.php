<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotBookingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'additional_service_id', 'price', 'quantity', 'total_price'
    ];

    public function booking()
    {
        return $this->belongsTo(SpotBooking::class);
    }
}

