<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    use HasFactory;

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    protected $guarded = ['id', 'created_at', 'updated_at'];

}
