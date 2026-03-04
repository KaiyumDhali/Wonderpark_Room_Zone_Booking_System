<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotBooking extends Model
{
    use HasFactory;


    protected $fillable = [
        'spot_id',
        'package_id',
        'invoice_number',
        'booking_date',
        'total_persons',
        'total_price',
        'discount_percent',
        'spot_discount_percent',
        'discount_amount',
        'invoice_adjustment_discount',
        'customer_name',
        'customer_mobile',
        'status'
    ];


    // SpotBooking.php
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function package()
    {
        return $this->belongsTo(SpotPackage::class, 'package_id');
    }

    // Relation to additional services
    public function details()
    {
        return $this->hasMany(SpotBookingDetail::class);
    }
}
