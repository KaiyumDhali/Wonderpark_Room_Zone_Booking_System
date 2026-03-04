<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayslipType extends Model
{
    protected $fillable = [
        'name',
        'status',
        'created_by',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'created_by');
    }
}
