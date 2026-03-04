<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLedger extends Model
{
    use HasFactory;

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
