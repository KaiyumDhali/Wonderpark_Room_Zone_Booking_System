<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;


    protected $guarded = ['id', 'booking_no', 'amount', 'created_at', 'updated_at'];

    protected $fillable = ['id', 'booking_no', 'amount', 'created_at', 'updated_at'];


    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_no');
    }
}
