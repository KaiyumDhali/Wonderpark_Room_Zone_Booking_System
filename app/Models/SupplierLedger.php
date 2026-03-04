<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model {

    use HasFactory;

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    protected $guarded = ['id', 'created_at', 'updated_at'];

}
