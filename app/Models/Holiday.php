<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Holiday;

class Holiday extends Model
{
    protected $fillable = [
        'employee_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
//    public function empdesignation()
//    {
//        return $this->belongsTo(EmpDesignation::class, 'designation_id');
//    }
//    public function loanoption()
//    {
//        return $this->belongsTo(LoanOption::class, 'loan_option');
//    }
//    public static $Loantypes=[
//        'fixed'=>'Fixed',
//        'percentage'=> 'Percentage',
//    ];
    
}
