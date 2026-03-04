<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'stock_type',
        'stock_date',
        'product_id',
        'supplier_id',
        'purchase_price',
        'supplier_invoice_no',
        'stock_in_quantity',
        'stock_in_unit_price',
        'stock_in_discount',
        'stock_in_total_amount',
        'customer_id',
        'invoice_no',
        'stock_out_quantity',
        'stock_out_unit_price',
        'stock_out_discount',
        'stock_out_total_amount',
        'done_by',
        'remarks',
        'product_service_detail_id',
        'status',
        'created_at',
        'updated_at',
    ];
    
    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    // Access unit through the product
    public function unit(){
        return $this->hasOneThrough(ProductUnit::class, Product::class, 'id', 'id', 'product_id', 'unit_id');
    }
    
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    
    public function supplier_finance_account(){
        return $this->belongsTo(FinanceAccount::class, 'supplier_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function customer_finance_account(){
        return $this->belongsTo(FinanceAccount::class, 'customer_id');
    }
    public function employee_finance_account(){
        return $this->belongsTo(Employee::class, 'customer_id');
    }

    protected $guarded = ['id', 'created_at', 'updated_at'];
    
}
