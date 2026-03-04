<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderDetailsChain extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_order_details_id',
        'size_id',
        'quantity',
        'unit_price',
        'total_price',
        'created_at',
        'updated_at',
    ];
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
