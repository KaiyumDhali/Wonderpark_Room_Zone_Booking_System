<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public function supplierLedger(){
        return $this->hasMany(SupplierLedger::class, 'supplier_id');
    }    
    
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
}
