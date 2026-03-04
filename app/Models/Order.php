<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    use HasFactory;

    protected $fillable = [
        'order_no',
        'customer_id',
        'order_date',
        'delivery_date',
        'done_by',
        'approveby',
        'status',
    ];

    public function customer() {
        return $this->belongsTo(FinanceAccount::class, 'customer_id');
    }

//    public function product() {
//        return $this->belongsTo(Product::class, 'product_id');
//    }

    public function orderdetail() {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

}
