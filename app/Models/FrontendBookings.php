<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class FrontendBookings extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $fillable = ['room_id', 'customer_id','customer_type','check_in_date', 'check_out_date','total_amount','booking_no','total_days','Booking_status'];
    public function room(): BelongsTo {

        return $this->belongsTo(FrontendModel::class, 'room_id', 'id');
    }
}


