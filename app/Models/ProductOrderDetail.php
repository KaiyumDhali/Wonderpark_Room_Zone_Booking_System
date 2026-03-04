<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_order_id',
        'image',
        'color',
        'created_at',
        'updated_at',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
