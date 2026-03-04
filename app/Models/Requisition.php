<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function unit() {
        // Using a hasOneThrough relationship to fetch the unit through the product
        return $this->hasOneThrough(ProductUnit::class, Product::class, 'id', 'id', 'product_id', 'unit_id');
    }
}
