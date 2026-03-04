<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'supplier_id',
        'purchasePrice',
        'salePrice',
        'damage_quantity',
        'damage_reason',
        'damage_date',
        'is_exchangeable',
        'is_repairable',
        'is_resaleable',
        'status',
        'done_by',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function supplier()
    {
        return $this->belongsTo(FinanceAccount::class, 'supplier_id', 'id');
    }


}
