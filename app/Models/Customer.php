<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = ['customer_type', 'customer_mobile', 'customer_name', 'nid_number', 'customer_address', 'customer_email', 'status'];

    public function customerLedger()
    {
        return $this->hasMany(CustomerLedger::class, 'customer_id');
    }
}
