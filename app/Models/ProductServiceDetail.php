<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductServiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_service_id',
        'service_date',
        'service_invoice',
        'actual_service_date',
        'service_number',
        'service_man_name',
        'service_man_mobile',
        'service_status',
        'remarks',
        'done_by',
    ];

    // Relationship with ProductService
    public function productService()
    {
        return $this->belongsTo(ProductService::class);
    }
}
