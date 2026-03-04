<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'product_id',
        'service_type',
        'service_start_date',
        'service_end_date',
        'service_quantity',
        'service_description',
        'service_location',
        'done_by',
        'created_at',
        'updated_at',
    ];

    public function serviceDetails()
    {
        return $this->hasMany(ProductServiceDetail::class);
    }
}
