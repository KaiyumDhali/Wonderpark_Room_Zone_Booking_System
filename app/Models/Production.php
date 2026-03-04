<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Production extends Model {

    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = [
        'product_id',
        'quantity',
        'unit_id',
        'done_by',
    ];

    public function productionDetails() {
        return $this->hasMany(ProductionBillOfMatrial::class, 'production_id');
    }
    public function unit(){
       return $this->belongsTo(ProductUnit::class, 'unit_id');
    }
    public function product(){
       return $this->belongsTo(Product::class, 'product_id');
    }
//    public function category(){
//        return $this->belongsTo(ProductCategory::class, 'category_id');
//    }
//    public function subCategory(){
//        return $this->belongsTo(ProductSubCategory::class, 'sub_category_id');
//    }
//    public function brand(){
//        return $this->belongsTo(ProductBrand::class, 'brand_id');
//    }
//    public function color(){
//        return $this->belongsTo(ProductColor::class, 'color_id');
//    }
//    public function size(){
//        return $this->belongsTo(ProductSize::class, 'size_id');
//    }
//    public function unit(){
//        return $this->belongsTo(ProductUnit::class, 'unit_id');
//    }
//    
//    public function productStock(){
//        return $this->hasMany(Stock::class, 'product_id');
//    }
//    
//    protected $guarded = ['id', 'created_at', 'updated_at'];
}
