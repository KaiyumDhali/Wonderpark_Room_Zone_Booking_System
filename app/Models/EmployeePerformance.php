<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePerformance extends Model
{
    use HasFactory;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function performance() {
        return $this->belongsTo(PerformanceType::class, 'performance_id');
    }
}
