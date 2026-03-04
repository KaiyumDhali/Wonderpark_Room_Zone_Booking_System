<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Product extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
    public function subCategory(){
        return $this->belongsTo(ProductSubCategory::class, 'sub_category_id');
    }
    public function brand(){
        return $this->belongsTo(ProductBrand::class, 'brand_id');
    }
    public function color(){
        return $this->belongsTo(ProductColor::class, 'color_id');
    }
    public function size(){
        return $this->belongsTo(ProductSize::class, 'size_id');
    }
    public function unit(){
        return $this->belongsTo(ProductUnit::class, 'unit_id');
    }
    
    public function productStock(){
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    // public function productionDetails()
    // {
    //     return $this->hasManyThrough(
    //         ProductionBillOfMatrial::class,
    //         Production::class,
    //         'product_id', // Foreign key on the Production table...
    //         'production_id', // Foreign key on the ProductionBillOfMatrial table...
    //         'id', // Local key on the projects table...
    //         'id' // Local key on the environments table...
    //     );
    // }
    
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
